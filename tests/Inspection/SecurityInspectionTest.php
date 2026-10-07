<?php

namespace Tests\Inspection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use ReflectionNamedType;
use Tests\TestCase;

/**
 * Aspek Security.
 * NFR-007: model terlindungi dari mass assignment.
 * NFR-008: APP_DEBUG nonaktif.
 * NFR-009: error internal tidak tampil ke pengguna.
 * NFR-010: input divalidasi dan otorisasi dicek.
 * NFR-011: tanpa SQL injection / XSS / kredensial hardcoded.
 */
class SecurityInspectionTest extends TestCase
{
    use InspectionHelpers;

    #[DataProvider('models')]
    public function test_nfr007_model_is_protected_from_mass_assignment(string $class): void
    {
        $model = new $class;
        $open = $model->getFillable() === [] && $model->getGuarded() === [];
        $this->assertFalse($open, "{$class} memakai \$guarded = [] (semua kolom bisa di-mass-assign).");
    }

    public static function models(): array
    {
        return self::classesIn('Models');
    }

    public function test_nfr008_env_file_has_debug_disabled(): void
    {
        if (! file_exists(self::root().'/.env')) {
            $this->markTestSkipped('Berkas .env tidak ada.');
        }
        $this->assertSame(0, preg_match('/^APP_DEBUG\s*=\s*true\s*$/mi', self::read('.env')), 'APP_DEBUG=true pada .env');
    }

    public function test_nfr008_env_example_has_debug_disabled(): void
    {
        $this->assertSame(
            0,
            preg_match('/^APP_DEBUG\s*=\s*true\s*$/mi', self::read('.env.example')),
            'APP_DEBUG=true pada .env.example'
        );
    }

    public function test_nfr008_config_default_for_debug_is_false(): void
    {
        $this->assertMatchesRegularExpression("/'debug'\s*=>\s*\(bool\)\s*env\('APP_DEBUG',\s*false\)/", self::read('config/app.php'));
    }

    public function test_nfr009_exception_message_is_not_shown_to_users(): void
    {
        $bad = [];
        foreach (self::phpFiles('app/Http') as $file) {
            $source = self::read($file);
            preg_match_all('/(->with\(|json\(|abort\()[^;]*getMessage\(\)/s', $source, $m, PREG_OFFSET_CAPTURE);
            foreach ($m[0] as [, $offset]) {
                $bad[] = self::rel($file).':'.(substr_count(substr($source, 0, $offset), "\n") + 1);
            }
        }
        $this->assertSame([], $bad, "getMessage() dikirim ke pengguna:\n".implode("\n", $bad));
    }

    public function test_nfr010_state_changing_routes_require_authentication(): void
    {
        $open = [];
        foreach (Route::getRoutes() as $route) {
            $isWrite = array_intersect($route->methods(), ['POST', 'PUT', 'PATCH', 'DELETE']) !== [];
            $middleware = $route->gatherMiddleware();
            $framework = preg_match('#^(livewire|_|up|storage|broadcasting)#', $route->uri());
            if ($isWrite && ! $framework && ! in_array('guest', $middleware, true) && ! in_array('auth', $middleware, true)) {
                $open[] = implode('|', $route->methods()).' /'.$route->uri();
            }
        }
        $this->assertSame([], $open, "Route tulis tanpa auth:\n".implode("\n", $open));
    }

    public function test_nfr010_admin_routes_require_admin_role(): void
    {
        $open = [];
        foreach (Route::getRoutes() as $route) {
            if (str_starts_with($route->uri(), 'admin') && ! in_array('role:admin', $route->gatherMiddleware(), true)) {
                $open[] = '/'.$route->uri();
            }
        }
        $this->assertSame([], $open, "Route admin tanpa role:admin:\n".implode("\n", $open));
    }

    public function test_nfr010_guest_is_redirected_to_login_from_protected_pages(): void
    {
        foreach (['/dashboard', '/reports/create', '/admin/dashboard'] as $uri) {
            $this->get($uri)->assertRedirect(route('login'));
        }
    }

    #[DataProvider('writingMethods')]
    public function test_nfr010_writing_method_validates_input(string $class, string $method): void
    {
        if (! method_exists($class, $method)) {
            $this->markTestSkipped("{$class}::{$method}() tidak ada.");
        }
        $reflection = new ReflectionMethod($class, $method);
        $body = implode('', array_slice(
            file($reflection->getFileName()),
            $reflection->getStartLine() - 1,
            $reflection->getEndLine() - $reflection->getStartLine() + 1
        ));
        $usesFormRequest = false;
        foreach ($reflection->getParameters() as $param) {
            $type = $param->getType();
            if ($type instanceof ReflectionNamedType && is_subclass_of($type->getName(), FormRequest::class)) {
                $usesFormRequest = true;
            }
        }
        $this->assertTrue(
            $usesFormRequest
                || str_contains($body, 'validate(')
                || preg_match('/\$this->\w*[Vv]alidat\w*\(/', $body) === 1,
            "{$class}::{$method}() tidak memvalidasi input."
        );
    }

    public static function writingMethods(): array
    {
        $ns = 'App\Http\Controllers\\';

        return [
            'ReportController::store' => [$ns.'ReportController', 'store'],
            'ReportController::updateFinal' => [$ns.'ReportController', 'updateFinal'],
            'NoteController::store' => [$ns.'NoteController', 'store'],
            'Admin\StaffController::store' => [$ns.'Admin\StaffController', 'store'],
            'Admin\StaffController::update' => [$ns.'Admin\StaffController', 'update'],
            'Admin\TaskController::store' => [$ns.'Admin\TaskController', 'store'],
            'Admin\TaskController::update' => [$ns.'Admin\TaskController', 'update'],
            'HotelController::store' => [$ns.'HotelController', 'store'],
            'HotelController::update' => [$ns.'HotelController', 'update'],
            'Admin\ShiftController::updateShift' => [$ns.'Admin\ShiftController', 'updateShift'],
            'Admin\ShiftController::grantDoubleShift' => [$ns.'Admin\ShiftController', 'grantDoubleShift'],
            'Admin\StorageController::updateSettings' => [$ns.'Admin\StorageController', 'updateSettings'],
        ];
    }

    public function test_nfr011_views_do_not_output_unescaped_blade(): void
    {
        $bad = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(self::root().'/resources/views', \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if (str_contains(self::read($file->getPathname()), '{!!')) {
                $bad[] = self::rel($file->getPathname());
            }
        }
        $this->assertSame([], $bad, "Blade tanpa escape {!! !!}:\n".implode("\n", $bad));
    }

    public function test_nfr011_raw_sql_does_not_interpolate_variables(): void
    {
        $bad = [];
        $pattern = '/(selectRaw|whereRaw|orderByRaw|groupByRaw|havingRaw|DB::raw|DB::statement|DB::select)\(\s*"([^"]*)"|'
            .'(selectRaw|whereRaw|orderByRaw|groupByRaw|havingRaw|DB::raw|DB::statement|DB::select)\(\s*\'[^\']*\'\s*\./';
        foreach (self::phpFiles('app') as $file) {
            foreach (file($file) as $no => $line) {
                if (preg_match($pattern, $line, $m) && (! empty($m[3]) || str_contains($m[2] ?? '', '$'))) {
                    $bad[] = self::rel($file).':'.($no + 1);
                }
            }
        }
        $this->assertSame([], $bad, "Raw SQL dengan variabel:\n".implode("\n", $bad));
    }

    public function test_nfr011_no_hardcoded_credentials_in_app_code(): void
    {
        $bad = [];
        foreach (self::phpFiles('app') as $file) {
            if (preg_match('/(bcrypt|Hash::make)\(\s*[\'"][^\'"]+[\'"]\s*\)|\$(password|secret|apikey)\s*=\s*[\'"][^\'"]+[\'"]/i', self::read($file))) {
                $bad[] = self::rel($file);
            }
        }
        $this->assertSame([], $bad, "Kredensial hardcoded:\n".implode("\n", $bad));
    }
}
