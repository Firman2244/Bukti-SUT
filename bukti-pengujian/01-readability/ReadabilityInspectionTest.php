<?php

namespace Tests\Inspection;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use Tests\TestCase;

/**
 * Aspek Readability.
 * NFR-001: nama class/method jelas dan konsisten (PSR-1).
 * NFR-002: method tidak terlalu panjang (maksimal MAX_METHOD_LINES baris).
 */
class ReadabilityInspectionTest extends TestCase
{
    use InspectionHelpers;

    private const MAX_METHOD_LINES = 60;

    public function test_nfr001_class_names_are_studlycaps_and_match_filename(): void
    {
        $bad = [];
        foreach (self::phpFiles('app') as $file) {
            if (preg_match('/^\s*(?:final\s+|abstract\s+)*(?:class|trait|interface|enum)\s+(\w+)/m', self::read($file), $m)) {
                if ($m[1] !== basename($file, '.php') || ! preg_match('/^[A-Z][A-Za-z0-9]*$/', $m[1])) {
                    $bad[] = self::rel($file).' => '.$m[1];
                }
            }
        }
        $this->assertSame([], $bad, "Nama class tidak sesuai PSR-1:\n".implode("\n", $bad));
    }

    public function test_nfr001_method_names_are_camelcase(): void
    {
        $bad = [];
        foreach (self::phpFiles('app') as $file) {
            preg_match_all('/function\s+&?(\w+)\s*\(/', self::read($file), $m);
            foreach ($m[1] as $name) {
                if (! str_starts_with($name, '__') && ! preg_match('/^[a-z][A-Za-z0-9]*$/', $name)) {
                    $bad[] = self::rel($file).' => '.$name.'()';
                }
            }
        }
        $this->assertSame([], $bad, "Nama method bukan camelCase:\n".implode("\n", $bad));
    }

    #[DataProvider('controllers')]
    public function test_nfr002_controller_methods_are_not_too_long(string $class): void
    {
        $long = [];
        foreach ((new ReflectionClass($class))->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() !== $class) {
                continue;
            }
            $lines = $method->getEndLine() - $method->getStartLine() + 1;
            if ($lines > self::MAX_METHOD_LINES) {
                $long[] = $method->getName()."() = {$lines} baris";
            }
        }
        $this->assertSame([], $long, 'Method > '.self::MAX_METHOD_LINES." baris:\n".implode("\n", $long));
    }

    public static function controllers(): array
    {
        return self::classesIn('Http/Controllers');
    }
}
