# NFR-006: Query tidak terikat satu jenis DB

Aspek: Maintainability  
Berkas tes: [`MaintainabilityInspectionTest.php`](../MaintainabilityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr006`

**Total test case: 2, sukses: 1, gagal: 1** (ditulis 2 / 1)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- GAGAL: nfr006 queries use no mysql only functions
  - Fungsi SQL khusus MySQL:
  - app/Http/Controllers/DashboardController.php:79 => ->selectRaw('YEAR(created_at) as year')
- LULUS: nfr006 migrations have no raw vendor statements
