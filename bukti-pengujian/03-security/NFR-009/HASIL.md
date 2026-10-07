# NFR-009: Error internal tidak tampil ke pengguna

Aspek: Security  
Berkas tes: [`SecurityInspectionTest.php`](../SecurityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr009`

**Total test case: 1, sukses: 0, gagal: 1** (ditulis 1 / 0)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- GAGAL: nfr009 exception message is not shown to users
  - getMessage() dikirim ke pengguna:
  - app/Http/Controllers/ReportController.php:127
  - app/Http/Controllers/ReportController.php:249
