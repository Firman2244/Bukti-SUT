<?php

/**
 * Rekap hasil tes inspeksi per ID kriteria (NFR-xxx) dari berkas JUnit.
 *
 * Pemakaian (dari root proyek):
 *   php vendor/bin/phpunit tests/Inspection --log-junit storage/junit.xml
 *   php tests/Inspection/rekap.php storage/junit.xml
 */
$file = $argv[1] ?? 'storage/junit.xml';
if (! is_file($file)) {
    fwrite(STDERR, "Berkas $file tidak ditemukan. Jalankan phpunit dengan --log-junit dulu.\n");
    exit(1);
}

$rekap = [];
foreach (simplexml_load_file($file)->xpath('//testcase') as $case) {
    if (! preg_match('/nfr(\d{3})/i', (string) $case['name'], $m)) {
        continue;
    }
    $id = 'NFR-'.$m[1];
    $rekap[$id] ??= ['total' => 0, 'lulus' => 0, 'gagal' => 0];
    $rekap[$id]['total']++;
    $failed = isset($case->failure) || isset($case->error);
    $rekap[$id][$failed ? 'gagal' : 'lulus']++;
}
ksort($rekap);

printf("%-9s %6s %6s %6s   %s\n", 'ID', 'TOTAL', 'LULUS', 'GAGAL', 'TOTAL/SUKSES');
foreach ($rekap as $id => $r) {
    printf("%-9s %6d %6d %6d   %d / %d\n", $id, $r['total'], $r['lulus'], $r['gagal'], $r['total'], $r['lulus']);
}
printf("%-9s %6d %6d %6d\n", 'JUMLAH', array_sum(array_column($rekap, 'total')),
    array_sum(array_column($rekap, 'lulus')), array_sum(array_column($rekap, 'gagal')));
