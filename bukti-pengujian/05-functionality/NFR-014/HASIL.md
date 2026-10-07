# NFR-014: Perilaku sesuai SRS FR-SHF-04 (tenggat shift dapat dikonfigurasi admin)

Aspek: Functionality  
Berkas tes: [`FunctionalityInspectionTest.php`](../FunctionalityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr014`

**Total test case: 2, sukses: 0, gagal: 2** (ditulis 2 / 0)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- GAGAL: nfr014 late logic reads deadline from shifts table
  - ReportController tidak membaca shifts.deadline_time yang diatur admin.
- GAGAL: nfr014 late logic has no hardcoded deadline tables
  - Tenggat terlambat masih konstanta hardcoded (*_DEADLINE_HOURS), bukan dari konfigurasi admin.
