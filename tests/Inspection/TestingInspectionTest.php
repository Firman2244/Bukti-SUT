<?php

namespace Tests\Inspection;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Aspek Testing.
 * NFR-021: ada unit/feature test untuk logika inti aplikasi.
 * NFR-022: konfigurasi tes tersedia.
 */
class TestingInspectionTest extends TestCase
{
    use InspectionHelpers;

    #[DataProvider('coreAreas')]
    public function test_nfr021_core_logic_has_automated_tests(string $label, string $pattern): void
    {
        $matches = [];
        foreach (self::phpFiles('tests') as $file) {
            if (str_contains($file, 'tests/Inspection') || basename($file) === 'TestCase.php') {
                continue;
            }
            if (preg_match($pattern, self::read($file))) {
                $matches[] = self::rel($file);
            }
        }
        $this->assertNotEmpty($matches, "Belum ada tes untuk: {$label}");
    }

    public static function coreAreas(): array
    {
        return [
            'perhitungan skor laporan' => ['perhitungan skor laporan', '/scoreBreakdown|Report::|ReportController/'],
            'pengaturan shift dan tenggat' => ['pengaturan shift dan tenggat', '/Shift|deadline/i'],
            'verifikasi tugas oleh admin' => ['verifikasi tugas oleh admin', '/updateItemStatus|verifyAllTasks|verifyTask|verify-all|report-items/'],
            'tiket perbaikan (catatan)' => ['tiket perbaikan (catatan)', '/Note\b|notes\.|NoteController/'],
        ];
    }

    public function test_nfr022_phpunit_uses_isolated_in_memory_database(): void
    {
        $xml = self::read('phpunit.xml');
        $this->assertStringContainsString('name="DB_CONNECTION" value="sqlite"', $xml);
        $this->assertStringContainsString('name="DB_DATABASE" value=":memory:"', $xml);
    }

    public function test_nfr022_phpunit_defines_unit_and_feature_suites(): void
    {
        $xml = self::read('phpunit.xml');
        $this->assertStringContainsString('<testsuite name="Unit">', $xml);
        $this->assertStringContainsString('<testsuite name="Feature">', $xml);
    }
}
