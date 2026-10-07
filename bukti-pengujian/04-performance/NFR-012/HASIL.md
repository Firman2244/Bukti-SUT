# NFR-012: Eager loading dan pagination dipakai

Aspek: Performance  
Berkas tes: [`PerformanceInspectionTest.php`](../PerformanceInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr012`

**Total test case: 4, sukses: 4, gagal: 0** (ditulis 4 / 4)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- LULUS: nfr012 list pages use eager loading and pagination with data set "Admin/ReportController with()"
- LULUS: nfr012 list pages use eager loading and pagination with data set "Admin/ReportController paginate()"
- LULUS: nfr012 list pages use eager loading and pagination with data set "Admin/WorkloadController paginate()"
- LULUS: nfr012 list pages use eager loading and pagination with data set "Admin/DashboardController paginate()"
