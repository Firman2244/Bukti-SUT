# NFR-015: Logika penting diberi komentar atau docblock

Aspek: Documentation & Comment  
Berkas tes: [`DocumentationInspectionTest.php`](../DocumentationInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr015`

**Total test case: 6, sukses: 1, gagal: 5** (ditulis 6 / 1)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- GAGAL: nfr015 core method has docblock or comment with data set "Report::scoreBreakdown"
  - App\Models\Report::scoreBreakdown() tidak punya docblock maupun komentar.
- GAGAL: nfr015 core method has docblock or comment with data set "User::hasActiveDoubleShiftPermit"
  - App\Models\User::hasActiveDoubleShiftPermit() tidak punya docblock maupun komentar.
- GAGAL: nfr015 core method has docblock or comment with data set "ReportController::store"
  - App\Http\Controllers\ReportController::store() tidak punya docblock maupun komentar.
- GAGAL: nfr015 core method has docblock or comment with data set "ReportController::updateFinal"
  - App\Http\Controllers\ReportController::updateFinal() tidak punya docblock maupun komentar.
- GAGAL: nfr015 core method has docblock or comment with data set "ReportController::resolveReportDate"
  - App\Http\Controllers\ReportController::resolveReportDate() tidak punya docblock maupun komentar.
- LULUS: nfr015 core method has docblock or comment with data set "DashboardController::index"
