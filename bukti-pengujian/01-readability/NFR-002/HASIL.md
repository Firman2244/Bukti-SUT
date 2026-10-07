# NFR-002: Method tidak terlalu panjang (maks. 60 baris)

Aspek: Readability  
Berkas tes: [`ReadabilityInspectionTest.php`](../ReadabilityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr002`

**Total test case: 24, sukses: 21, gagal: 3** (ditulis 24 / 21)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/ActivityLogController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Admin/DashboardController.php"
- GAGAL: nfr002 controller methods are not too long with data set "app/Http/Controllers/Admin/ReportController.php"
  - Method > 60 baris:
  - index() = 63 baris
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Admin/ShiftController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Admin/StaffController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Admin/StorageController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Admin/TaskController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Admin/WorkloadController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Auth/AuthenticatedSessionController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Auth/ConfirmablePasswordController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Auth/EmailVerificationNotificationController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Auth/EmailVerificationPromptController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Auth/ForgotPasswordController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Auth/NewPasswordController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Auth/PasswordController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Auth/PasswordResetLinkController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Auth/RegisteredUserController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Auth/VerifyEmailController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/Controller.php"
- GAGAL: nfr002 controller methods are not too long with data set "app/Http/Controllers/DashboardController.php"
  - Method > 60 baris:
  - index() = 126 baris
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/HotelController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/NoteController.php"
- LULUS: nfr002 controller methods are not too long with data set "app/Http/Controllers/ProfileController.php"
- GAGAL: nfr002 controller methods are not too long with data set "app/Http/Controllers/ReportController.php"
  - Method > 60 baris:
  - store() = 69 baris
  - updateFinal() = 121 baris
