# NFR-013: Tidak ada query di dalam loop

Aspek: Performance  
Berkas tes: [`PerformanceInspectionTest.php`](../PerformanceInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr013`

**Total test case: 24, sukses: 22, gagal: 2** (ditulis 24 / 22)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/ActivityLogController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Admin/DashboardController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Admin/ReportController.php"
- GAGAL: nfr013 no database query inside loops with data set "app/Http/Controllers/Admin/ShiftController.php"
  - Query tulis di dalam loop:
  - loop baris 93: ->update(
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Admin/StaffController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Admin/StorageController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Admin/TaskController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Admin/WorkloadController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Auth/AuthenticatedSessionController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Auth/ConfirmablePasswordController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Auth/EmailVerificationNotificationController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Auth/EmailVerificationPromptController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Auth/ForgotPasswordController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Auth/NewPasswordController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Auth/PasswordController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Auth/PasswordResetLinkController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Auth/RegisteredUserController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Auth/VerifyEmailController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/Controller.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/DashboardController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/HotelController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/NoteController.php"
- LULUS: nfr013 no database query inside loops with data set "app/Http/Controllers/ProfileController.php"
- GAGAL: nfr013 no database query inside loops with data set "app/Http/Controllers/ReportController.php"
  - Query tulis di dalam loop:
  - loop baris 149: ->update(
  - loop baris 175: ::create(
