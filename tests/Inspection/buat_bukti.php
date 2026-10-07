<?php

/**
 * Menyiapkan folder repo bukti pengujian dari hasil JUnit.
 *
 * Pemakaian (dari root proyek SUT):
 *   php vendor/bin/phpunit tests/Inspection --log-junit storage/junit.xml
 *   php tests/Inspection/buat_bukti.php storage/junit.xml ../dailyreporthotel-testing
 *
 * Hasil: <folder-repo>/tests/Inspection (salinan tes) dan
 *        <folder-repo>/bukti-pengujian/<aspek>/<NFR>/HASIL.md (+ folder lulus/ dan gagal/ untuk screenshot).
 */
$junit = $argv[1] ?? 'storage/junit.xml';
$repo = rtrim($argv[2] ?? '../dailyreporthotel-testing', '/\\');
$source = __DIR__;

if (! is_file($junit)) {
    fwrite(STDERR, "Berkas $junit tidak ditemukan. Jalankan phpunit dengan --log-junit dulu.\n");
    exit(1);
}

$aspects = [
    '01-readability' => ['Readability', 'ReadabilityInspectionTest', [
        'NFR-001' => 'Nama class dan method jelas dan konsisten',
        'NFR-002' => 'Method tidak terlalu panjang (maks. 60 baris)']],
    '02-maintainability' => ['Maintainability', 'MaintainabilityInspectionTest', [
        'NFR-003' => 'Tanpa angka aturan bisnis hardcoded',
        'NFR-004' => 'Tanpa kode yang tidak terpakai',
        'NFR-005' => 'Tidak ada logika duplikat',
        'NFR-006' => 'Query tidak terikat satu jenis DB']],
    '03-security' => ['Security', 'SecurityInspectionTest', [
        'NFR-007' => 'Model terlindungi dari mass assignment',
        'NFR-008' => 'APP_DEBUG nonaktif',
        'NFR-009' => 'Error internal tidak tampil ke pengguna',
        'NFR-010' => 'Input divalidasi dan otorisasi dicek',
        'NFR-011' => 'Tanpa SQL injection, XSS, dan kredensial hardcoded']],
    '04-performance' => ['Performance', 'PerformanceInspectionTest', [
        'NFR-012' => 'Eager loading dan pagination dipakai',
        'NFR-013' => 'Tidak ada query di dalam loop']],
    '05-functionality' => ['Functionality', 'FunctionalityInspectionTest', [
        'NFR-014' => 'Perilaku sesuai SRS FR-SHF-04 (tenggat shift dapat dikonfigurasi admin)']],
    '06-documentation' => ['Documentation & Comment', 'DocumentationInspectionTest', [
        'NFR-015' => 'Logika penting diberi komentar atau docblock',
        'NFR-016' => 'Ada README khusus proyek']],
    '07-standards' => ['Adherence to Standards', 'StandardsInspectionTest', [
        'NFR-017' => 'Tidak ada import yang tak terpakai',
        'NFR-018' => 'Tidak ada spasi sisa di akhir baris',
        'NFR-019' => 'Konfigurasi gaya kode tersedia',
        'NFR-020' => 'Panjang baris sesuai PSR-12 dan lolos Laravel Pint']],
    '08-testing' => ['Testing', 'TestingInspectionTest', [
        'NFR-021' => 'Ada unit/feature test untuk logika inti',
        'NFR-022' => 'Konfigurasi tes tersedia']],
];

// 1. Baca hasil JUnit per NFR.
$cases = [];
foreach (simplexml_load_file($junit)->xpath('//testcase') as $case) {
    if (! preg_match('/nfr(\d{3})/i', (string) $case['name'], $m)) {
        continue;
    }
    $failed = isset($case->failure) || isset($case->error);
    $reason = [];
    if ($failed) {
        $text = (string) ($case->failure ?? $case->error);
        foreach (array_slice(explode("\n", $text), 1) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, 'Failed asserting') || str_starts_with($line, '---')) {
                break;
            }
            $reason[] = $line;
        }
    }
    $cases['NFR-'.$m[1]][] = [
        'name' => trim(str_replace(['test_', '_'], ['', ' '], (string) $case['name'])),
        'ok' => ! $failed,
        'reason' => array_slice($reason, 0, 8),
    ];
}

// 2. Salin tes dan bangun folder bukti.
@mkdir("$repo/tests/Inspection", 0777, true);
foreach (glob("$source/*.php") as $file) {
    copy($file, "$repo/tests/Inspection/".basename($file));
}

$index = "# Bukti Pengujian per Aspek dan NFR\n\n| Aspek | NFR | Total | Sukses | Gagal |\n|---|---|---|---|---|\n";
foreach ($aspects as $folder => [$label, $class, $nfrs]) {
    @mkdir("$repo/bukti-pengujian/$folder", 0777, true);
    copy("$source/$class.php", "$repo/bukti-pengujian/$folder/$class.php");

    foreach ($nfrs as $id => $title) {
        $dir = "$repo/bukti-pengujian/$folder/$id";
        foreach (['lulus', 'gagal'] as $sub) {
            @mkdir("$dir/$sub", 0777, true);
            touch("$dir/$sub/.gitkeep");
        }
        $list = $cases[$id] ?? [];
        $pass = count(array_filter($list, fn ($c) => $c['ok']));
        $total = count($list);

        $md = "# $id: $title\n\nAspek: $label  \nBerkas tes: [`$class.php`](../$class.php)  \n";
        $md .= 'Perintah: `php vendor/bin/phpunit tests/Inspection --filter '.strtolower(str_replace('-', '', $id))."`\n\n";
        $md .= "**Total test case: $total, sukses: $pass, gagal: ".($total - $pass)."** (ditulis $total / $pass)\n\n";
        $md .= "Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.\n\n## Daftar test case\n\n";
        foreach ($list as $c) {
            $md .= ($c['ok'] ? '- LULUS: ' : '- GAGAL: ').$c['name']."\n";
            foreach ($c['reason'] as $r) {
                $md .= "  - $r\n";
            }
        }
        file_put_contents("$dir/HASIL.md", $md);
        $index .= "| $label | [$id]($folder/$id/HASIL.md) | $total | $pass | ".($total - $pass)." |\n";
    }
}
file_put_contents("$repo/bukti-pengujian/README.md", $index);
echo "Selesai. Folder repo: $repo\n";
