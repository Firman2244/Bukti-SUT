# NFR-010: Input divalidasi dan otorisasi dicek

Aspek: Security  
Berkas tes: [`SecurityInspectionTest.php`](../SecurityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr010`

**Total test case: 15, sukses: 15, gagal: 0** (ditulis 15 / 15)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- LULUS: nfr010 state changing routes require authentication
- LULUS: nfr010 admin routes require admin role
- LULUS: nfr010 guest is redirected to login from protected pages
- LULUS: nfr010 writing method validates input with data set "ReportController::store"
- LULUS: nfr010 writing method validates input with data set "ReportController::updateFinal"
- LULUS: nfr010 writing method validates input with data set "NoteController::store"
- LULUS: nfr010 writing method validates input with data set "Admin\StaffController::store"
- LULUS: nfr010 writing method validates input with data set "Admin\StaffController::update"
- LULUS: nfr010 writing method validates input with data set "Admin\TaskController::store"
- LULUS: nfr010 writing method validates input with data set "Admin\TaskController::update"
- LULUS: nfr010 writing method validates input with data set "HotelController::store"
- LULUS: nfr010 writing method validates input with data set "HotelController::update"
- LULUS: nfr010 writing method validates input with data set "Admin\ShiftController::updateShift"
- LULUS: nfr010 writing method validates input with data set "Admin\ShiftController::grantDoubleShift"
- LULUS: nfr010 writing method validates input with data set "Admin\StorageController::updateSettings"
