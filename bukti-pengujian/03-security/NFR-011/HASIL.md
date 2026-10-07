# NFR-011: Tanpa SQL injection, XSS, dan kredensial hardcoded

Aspek: Security  
Berkas tes: [`SecurityInspectionTest.php`](../SecurityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr011`

**Total test case: 3, sukses: 3, gagal: 0** (ditulis 3 / 3)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- LULUS: nfr011 views do not output unescaped blade
- LULUS: nfr011 raw sql does not interpolate variables
- LULUS: nfr011 no hardcoded credentials in app code
