<?php

namespace Tests\Inspection;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Fungsi bantu untuk tes otomatis berbasis Inspection Checklist (static testing).
 */
trait InspectionHelpers
{
    public static function root(): string
    {
        return str_replace('\\', '/', dirname(__DIR__, 2));
    }

    /**
     * Daftar path absolut berkas .php (rekursif) pada folder-folder relatif.
     *
     * @return string[]
     */
    public static function phpFiles(string ...$dirs): array
    {
        $files = [];
        foreach ($dirs as $dir) {
            $path = self::root().'/'.$dir;
            if (! is_dir($path)) {
                continue;
            }
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $files[] = str_replace('\\', '/', $file->getPathname());
                }
            }
        }
        sort($files);

        return $files;
    }

    public static function rel(string $absolute): string
    {
        return ltrim(str_replace(self::root(), '', str_replace('\\', '/', $absolute)), '/');
    }

    public static function read(string $relative): string
    {
        $isAbsolute = preg_match('#^([A-Za-z]:)?[/\\\\]#', $relative) === 1;
        $path = $isAbsolute ? $relative : self::root().'/'.$relative;

        return (string) file_get_contents($path);
    }

    /** Nama class (namespace penuh) dari path berkas di dalam app/. */
    public static function classFromFile(string $absolute): string
    {
        $relative = preg_replace('#^app/#', '', self::rel($absolute));

        return 'App\\'.str_replace('/', '\\', substr($relative, 0, -4));
    }

    /**
     * Provider: semua class di folder app/<sub> (mis. 'Http/Controllers', 'Models').
     *
     * @return array<string, array{0: class-string}>
     */
    public static function classesIn(string $sub): array
    {
        $cases = [];
        foreach (self::phpFiles('app/'.$sub) as $file) {
            $class = self::classFromFile($file);
            if (class_exists($class)) {
                $cases[self::rel($file)] = [$class];
            }
        }

        return $cases;
    }

    /** Ambil isi blok { ... } pertama setelah posisi $offset (pencocokan kurung kurawal). */
    public static function blockAfter(string $source, int $offset): string
    {
        $start = strpos($source, '{', $offset);
        if ($start === false) {
            return '';
        }
        $depth = 0;
        $length = strlen($source);
        for ($i = $start; $i < $length; $i++) {
            if ($source[$i] === '{') {
                $depth++;
            } elseif ($source[$i] === '}') {
                $depth--;
                if ($depth === 0) {
                    return substr($source, $start, $i - $start + 1);
                }
            }
        }

        return substr($source, $start);
    }
}
