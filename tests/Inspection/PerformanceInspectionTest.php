<?php

namespace Tests\Inspection;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Aspek Performance.
 * NFR-012: eager loading dan pagination dipakai.
 * NFR-013: tidak ada query di dalam loop.
 */
class PerformanceInspectionTest extends TestCase
{
    use InspectionHelpers;

    #[DataProvider('expectedOptimisations')]
    public function test_nfr012_list_pages_use_eager_loading_and_pagination(string $file, string $needle): void
    {
        $this->assertTrue(str_contains(self::read($file), $needle), "{$file} tidak memakai {$needle}");
    }

    public static function expectedOptimisations(): array
    {
        return [
            'Admin/ReportController with()' => ['app/Http/Controllers/Admin/ReportController.php', '->with('],
            'Admin/ReportController paginate()' => ['app/Http/Controllers/Admin/ReportController.php', '->paginate('],
            'Admin/WorkloadController paginate()' => ['app/Http/Controllers/Admin/WorkloadController.php', '->paginate('],
            'Admin/DashboardController paginate()' => ['app/Http/Controllers/Admin/DashboardController.php', '->paginate('],
        ];
    }

    #[DataProvider('controllerFiles')]
    public function test_nfr013_no_database_query_inside_loops(string $file): void
    {
        $source = self::read($file);
        $found = [];
        preg_match_all('/\b(foreach|for|while)\s*\(/', $source, $loops, PREG_OFFSET_CAPTURE);
        foreach ($loops[0] as [, $offset]) {
            $body = self::blockAfter($source, $offset);
            $queries = [];
            foreach (explode("\n", $body) as $text) {
                // Penghapusan berkas di disk (Storage/$disk) bukan query database.
                if (preg_match('/Storage::|\$disk->|->disk\(/', $text)) {
                    continue;
                }
                if (preg_match_all('/(->(update|save|delete|increment|decrement)\(|::(create|insert|firstOrCreate|updateOrCreate)\()/', $text, $m)) {
                    $queries = array_merge($queries, $m[0]);
                }
            }
            if ($queries !== []) {
                $line = substr_count(substr($source, 0, $offset), "\n") + 1;
                $found[] = "loop baris {$line}: ".implode(', ', array_unique($queries));
            }
        }
        $this->assertSame([], $found, "Query tulis di dalam loop:\n".implode("\n", $found));
    }

    public static function controllerFiles(): array
    {
        $cases = [];
        foreach (self::phpFiles('app/Http/Controllers') as $file) {
            $cases[self::rel($file)] = [self::rel($file)];
        }

        return $cases;
    }
}
