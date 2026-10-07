<?php

namespace Tests\Inspection;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Aspek Maintainability.
 * NFR-003: tanpa angka aturan bisnis hardcoded.
 * NFR-004: tanpa kode yang tidak terpakai (model mati).
 * NFR-005: tanpa logika duplikat (aturan shift 3 lewat tengah malam).
 * NFR-006: query tidak terikat satu jenis DB.
 */
class MaintainabilityInspectionTest extends TestCase
{
    use InspectionHelpers;

    /** Angka yang wajar muncul di logika (0, 1, 100 untuk persen). */
    private const ALLOWED_NUMBERS = [0, 1, 100];

    #[DataProvider('coreMethods')]
    public function test_nfr003_no_hardcoded_business_numbers(string $class, string $method): void
    {
        $reflection = new ReflectionMethod($class, $method);
        $lines = array_slice(
            file($reflection->getFileName()),
            $reflection->getStartLine() - 1,
            $reflection->getEndLine() - $reflection->getStartLine() + 1
        );
        $found = [];
        foreach (token_get_all('<?php '.implode('', $lines)) as $token) {
            if (is_array($token) && in_array($token[0], [T_LNUMBER, T_DNUMBER], true)
                && ! in_array((float) $token[1], self::ALLOWED_NUMBERS, true)) {
                $found[] = $token[1];
            }
        }
        $this->assertSame([], $found, "Angka hardcoded di {$class}::{$method}(): ".implode(', ', $found));
    }

    public static function coreMethods(): array
    {
        return [
            'Report::scoreBreakdown' => ['App\Models\Report', 'scoreBreakdown'],
            'User::hasActiveDoubleShiftPermit' => ['App\Models\User', 'hasActiveDoubleShiftPermit'],
            'ReportController::resolveReportDate' => ['App\Http\Controllers\ReportController', 'resolveReportDate'],
        ];
    }

    #[DataProvider('models')]
    public function test_nfr004_model_is_referenced_somewhere(string $class): void
    {
        $short = substr(strrchr($class, '\\'), 1);
        $own = self::root().'/app/Models/'.$short.'.php';
        $used = false;
        foreach (self::phpFiles('app', 'routes', 'database', 'tests', 'config') as $file) {
            if ($file === $own || str_contains($file, 'tests/Inspection')) {
                continue;
            }
            if (preg_match('/\b'.$short.'\b/', self::read($file))) {
                $used = true;
                break;
            }
        }
        $this->assertTrue($used, "Model {$short} tidak dirujuk oleh berkas mana pun (kode mati).");
    }

    public static function models(): array
    {
        return self::classesIn('Models');
    }

    public function test_nfr005_shift_midnight_rule_is_not_duplicated(): void
    {
        $hits = [];
        foreach (self::phpFiles('app') as $file) {
            foreach (file($file) as $no => $line) {
                if (preg_match('/shift_?id\s*===?\s*3/i', $line)) {
                    $hits[] = self::rel($file).':'.($no + 1);
                }
            }
        }
        $this->assertLessThanOrEqual(
            1,
            count($hits),
            'Aturan "shift 3" ditulis '.count($hits)." kali:\n".implode("\n", $hits)
        );
    }

    public function test_nfr006_queries_use_no_mysql_only_functions(): void
    {
        $bad = [];
        foreach (self::phpFiles('app') as $file) {
            foreach (file($file) as $no => $line) {
                if (preg_match('/\b(YEAR|MONTH|DAY|DATE_FORMAT|GROUP_CONCAT|IFNULL|CURDATE)\s*\(/', $line)) {
                    $bad[] = self::rel($file).':'.($no + 1).' => '.trim($line);
                }
            }
        }
        $this->assertSame([], $bad, "Fungsi SQL khusus MySQL:\n".implode("\n", $bad));
    }

    public function test_nfr006_migrations_have_no_raw_vendor_statements(): void
    {
        $bad = [];
        foreach (self::phpFiles('database/migrations') as $file) {
            if (preg_match('/DB::(statement|unprepared)\(/', self::read($file))) {
                $bad[] = self::rel($file);
            }
        }
        $this->assertSame([], $bad, "Migrasi memakai SQL mentah:\n".implode("\n", $bad));
    }
}
