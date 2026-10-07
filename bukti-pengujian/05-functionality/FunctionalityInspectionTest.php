<?php

namespace Tests\Inspection;

use Tests\TestCase;

/**
 * Aspek Functionality.
 * NFR-014: perilaku sesuai SRS FR-SHF-04 (tenggat shift dapat dikonfigurasi admin).
 */
class FunctionalityInspectionTest extends TestCase
{
    use InspectionHelpers;

    public function test_nfr014_late_logic_reads_deadline_from_shifts_table(): void
    {
        $this->assertTrue(
            str_contains(self::read('app/Http/Controllers/ReportController.php'), 'deadline_time'),
            'ReportController tidak membaca shifts.deadline_time yang diatur admin.'
        );
    }

    public function test_nfr014_late_logic_has_no_hardcoded_deadline_tables(): void
    {
        $this->assertSame(
            0,
            preg_match('/const\s+\w*DEADLINE_HOURS\s*=/', self::read('app/Http/Controllers/ReportController.php')),
            'Tenggat terlambat masih konstanta hardcoded (*_DEADLINE_HOURS), bukan dari konfigurasi admin.'
        );
    }
}
