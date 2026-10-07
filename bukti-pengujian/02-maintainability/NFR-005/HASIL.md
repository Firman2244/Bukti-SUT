# NFR-005: Tidak ada logika duplikat

Aspek: Maintainability  
Berkas tes: [`MaintainabilityInspectionTest.php`](../MaintainabilityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr005`

**Total test case: 1, sukses: 0, gagal: 1** (ditulis 1 / 0)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- GAGAL: nfr005 shift midnight rule is not duplicated
  - Aturan "shift 3" ditulis 4 kali:
  - app/Http/Controllers/DashboardController.php:30
  - app/Http/Controllers/ReportController.php:215
  - app/Http/Controllers/ReportController.php:411
  - app/Models/User.php:55
