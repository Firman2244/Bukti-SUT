<?php

namespace Tests\Inspection;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Aspek Documentation & Comment.
 * NFR-015: logika penting diberi komentar atau docblock.
 * NFR-016: ada README khusus proyek.
 */
class DocumentationInspectionTest extends TestCase
{
    use InspectionHelpers;

    #[DataProvider('coreMethods')]
    public function test_nfr015_core_method_has_docblock_or_comment(string $class, string $method): void
    {
        $reflection = new ReflectionMethod($class, $method);
        $body = implode('', array_slice(
            file($reflection->getFileName()),
            $reflection->getStartLine() - 1,
            $reflection->getEndLine() - $reflection->getStartLine() + 1
        ));
        $documented = $reflection->getDocComment() !== false
            || preg_match('#^\s*(//|/\*|\*)#m', $body) === 1;
        $this->assertTrue($documented, "{$class}::{$method}() tidak punya docblock maupun komentar.");
    }

    public static function coreMethods(): array
    {
        $controllers = 'App\Http\Controllers\\';

        return [
            'Report::scoreBreakdown' => ['App\Models\Report', 'scoreBreakdown'],
            'User::hasActiveDoubleShiftPermit' => ['App\Models\User', 'hasActiveDoubleShiftPermit'],
            'ReportController::store' => [$controllers.'ReportController', 'store'],
            'ReportController::updateFinal' => [$controllers.'ReportController', 'updateFinal'],
            'ReportController::resolveReportDate' => [$controllers.'ReportController', 'resolveReportDate'],
            'DashboardController::index' => [$controllers.'DashboardController', 'index'],
        ];
    }

    public function test_nfr016_readme_is_not_the_default_laravel_readme(): void
    {
        $this->assertStringNotContainsString('laravel.com" target="_blank"', self::read('README.md'));
        $this->assertStringNotContainsString('Laravel is a web application framework', self::read('README.md'));
    }

    public function test_nfr016_readme_describes_the_application(): void
    {
        $this->assertMatchesRegularExpression('/laporan harian/i', self::read('README.md'));
    }

    public function test_nfr016_readme_contains_installation_instructions(): void
    {
        $this->assertMatchesRegularExpression('/instalasi|installation|composer install/i', self::read('README.md'));
    }
}
