# NFR-020: Panjang baris sesuai PSR-12 dan lolos Laravel Pint

Aspek: Adherence to Standards  
Berkas tes: [`StandardsInspectionTest.php`](../StandardsInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr020`

**Total test case: 2, sukses: 0, gagal: 2** (ditulis 2 / 0)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- GAGAL: nfr020 lines do not exceed 120 characters
  - 45 baris > 120 karakter:
  - app/Exports/ReportsExport.php:91 (170 karakter)
  - app/Exports/ReportsExport.php:123 (159 karakter)
  - app/Exports/ReportsExport.php:126 (145 karakter)
  - app/Exports/ReportsExport.php:128 (158 karakter)
  - app/Http/Controllers/ActivityLogController.php:45 (134 karakter)
  - app/Http/Controllers/Admin/DashboardController.php:34 (148 karakter)
  - app/Http/Controllers/Admin/DashboardController.php:35 (139 karakter)
- GAGAL: nfr020 code passes laravel pint
  - Pint menemukan masalah gaya kode:
