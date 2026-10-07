<?php

namespace Tests\Inspection;

use Tests\TestCase;

/**
 * Aspek Adherence to Standards (PSR-12).
 * NFR-017: tanpa import yang tak terpakai.
 * NFR-018: tanpa spasi sisa di akhir baris.
 * NFR-019: konfigurasi gaya kode tersedia.
 * NFR-020: panjang baris sesuai PSR-12 (batas lunak 120) dan lolos Laravel Pint.
 */
class StandardsInspectionTest extends TestCase
{
    use InspectionHelpers;

    private const MAX_LINE_LENGTH = 120;

    public function test_nfr017_no_unused_imports(): void
    {
        $bad = [];
        foreach (self::phpFiles('app', 'routes', 'database', 'bootstrap') as $file) {
            $source = self::read($file);
            if (! preg_match_all('/^use\s+([\w\\\\]+)(?:\s+as\s+(\w+))?;/m', $source, $uses, PREG_SET_ORDER)) {
                continue;
            }
            $withoutImports = preg_replace('/^use\s+[^;]+;/m', '', $source);
            foreach ($uses as $use) {
                $alias = $use[2] ?? substr(strrchr('\\'.$use[1], '\\'), 1);
                if (! preg_match('/\b'.preg_quote($alias, '/').'\b/', $withoutImports)) {
                    $bad[] = self::rel($file).' => '.$use[1];
                }
            }
        }
        $this->assertSame([], $bad, "Import tak terpakai:\n".implode("\n", $bad));
    }

    public function test_nfr018_no_trailing_whitespace(): void
    {
        $bad = [];
        foreach (self::phpFiles('app', 'routes') as $file) {
            foreach (file($file, FILE_IGNORE_NEW_LINES) as $no => $line) {
                $line = rtrim($line, "\r");
                if (preg_match('/[ \t]+$/', $line)) {
                    $bad[] = self::rel($file).':'.($no + 1);
                }
            }
        }
        $this->assertSame([], $bad, "Spasi sisa akhir baris:\n".implode("\n", $bad));
    }

    public function test_nfr019_editorconfig_exists(): void
    {
        $this->assertFileExists(self::root().'/.editorconfig');
    }

    public function test_nfr020_lines_do_not_exceed_120_characters(): void
    {
        $bad = [];
        foreach (self::phpFiles('app') as $file) {
            foreach (file($file, FILE_IGNORE_NEW_LINES) as $no => $line) {
                $line = rtrim($line, "\r");
                if (mb_strlen($line) > self::MAX_LINE_LENGTH) {
                    $bad[] = self::rel($file).':'.($no + 1).' ('.mb_strlen($line).' karakter)';
                }
            }
        }
        $this->assertSame([], $bad, count($bad)." baris > 120 karakter:\n".implode("\n", array_slice($bad, 0, 15)));
    }

    public function test_nfr020_code_passes_laravel_pint(): void
    {
        $command = escapeshellarg(PHP_BINARY).' '.escapeshellarg(self::root().'/vendor/bin/pint')
            .' --test '.escapeshellarg(self::root().'/app').' 2>&1';
        exec($command, $output, $exitCode);
        $this->assertSame(0, $exitCode, "Pint menemukan masalah gaya kode:\n".implode("\n", array_slice($output, 0, 25)));
    }
}
