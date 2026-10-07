# NFR-004: Tanpa kode yang tidak terpakai

Aspek: Maintainability  
Berkas tes: [`MaintainabilityInspectionTest.php`](../MaintainabilityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr004`

**Total test case: 11, sukses: 10, gagal: 1** (ditulis 11 / 10)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- LULUS: nfr004 model is referenced somewhere with data set "app/Models/ActivityLog.php"
- GAGAL: nfr004 model is referenced somewhere with data set "app/Models/DailyReport.php"
  - Model DailyReport tidak dirujuk oleh berkas mana pun (kode mati).
- LULUS: nfr004 model is referenced somewhere with data set "app/Models/Hotel.php"
- LULUS: nfr004 model is referenced somewhere with data set "app/Models/Note.php"
- LULUS: nfr004 model is referenced somewhere with data set "app/Models/PointHistory.php"
- LULUS: nfr004 model is referenced somewhere with data set "app/Models/Report.php"
- LULUS: nfr004 model is referenced somewhere with data set "app/Models/ReportItem.php"
- LULUS: nfr004 model is referenced somewhere with data set "app/Models/Setting.php"
- LULUS: nfr004 model is referenced somewhere with data set "app/Models/Shift.php"
- LULUS: nfr004 model is referenced somewhere with data set "app/Models/Task.php"
- LULUS: nfr004 model is referenced somewhere with data set "app/Models/User.php"
