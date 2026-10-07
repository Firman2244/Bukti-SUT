# NFR-008: APP_DEBUG nonaktif

Aspek: Security  
Berkas tes: [`SecurityInspectionTest.php`](../SecurityInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr008`

**Total test case: 3, sukses: 2, gagal: 1** (ditulis 3 / 2)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- LULUS: nfr008 env file has debug disabled
- GAGAL: nfr008 env example has debug disabled
  - APP_DEBUG=true pada .env.example
- LULUS: nfr008 config default for debug is false
