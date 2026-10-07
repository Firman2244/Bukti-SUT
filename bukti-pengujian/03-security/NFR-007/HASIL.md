# NFR-007: Model terlindungi dari mass assignment

Aspek: Security  
Berkas tes: [`SecurityInspectionTest.php`](../SecurityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr007`

**Total test case: 11, sukses: 7, gagal: 4** (ditulis 11 / 7)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- LULUS: nfr007 model is protected from mass assignment with data set "app/Models/ActivityLog.php"
- LULUS: nfr007 model is protected from mass assignment with data set "app/Models/DailyReport.php"
- GAGAL: nfr007 model is protected from mass assignment with data set "app/Models/Hotel.php"
  - App\Models\Hotel memakai $guarded = [] (semua kolom bisa di-mass-assign).
- LULUS: nfr007 model is protected from mass assignment with data set "app/Models/Note.php"
- LULUS: nfr007 model is protected from mass assignment with data set "app/Models/PointHistory.php"
- GAGAL: nfr007 model is protected from mass assignment with data set "app/Models/Report.php"
  - App\Models\Report memakai $guarded = [] (semua kolom bisa di-mass-assign).
- GAGAL: nfr007 model is protected from mass assignment with data set "app/Models/ReportItem.php"
  - App\Models\ReportItem memakai $guarded = [] (semua kolom bisa di-mass-assign).
- LULUS: nfr007 model is protected from mass assignment with data set "app/Models/Setting.php"
- LULUS: nfr007 model is protected from mass assignment with data set "app/Models/Shift.php"
- GAGAL: nfr007 model is protected from mass assignment with data set "app/Models/Task.php"
  - App\Models\Task memakai $guarded = [] (semua kolom bisa di-mass-assign).
- LULUS: nfr007 model is protected from mass assignment with data set "app/Models/User.php"
