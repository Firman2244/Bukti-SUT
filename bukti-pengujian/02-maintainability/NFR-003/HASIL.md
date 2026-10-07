# NFR-003: Tanpa angka aturan bisnis hardcoded

Aspek: Maintainability  
Berkas tes: [`MaintainabilityInspectionTest.php`](../MaintainabilityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr003`

**Total test case: 3, sukses: 0, gagal: 3** (ditulis 3 / 0)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- GAGAL: nfr003 no hardcoded business numbers with data set "Report::scoreBreakdown"
  - Angka hardcoded di App\Models\Report::scoreBreakdown(): 0, 0, 100, 0, 100, 0, 1, 10, 15, 0, 15, 0, 0
- GAGAL: nfr003 no hardcoded business numbers with data set "User::hasActiveDoubleShiftPermit"
  - Angka hardcoded di App\Models\User::hasActiveDoubleShiftPermit(): 3
- GAGAL: nfr003 no hardcoded business numbers with data set "ReportController::resolveReportDate"
  - Angka hardcoded di App\Http\Controllers\ReportController::resolveReportDate(): 3, 12
