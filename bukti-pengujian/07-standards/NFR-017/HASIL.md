# NFR-017: Tidak ada import yang tak terpakai

Aspek: Adherence to Standards  
Berkas tes: [`StandardsInspectionTest.php`](../StandardsInspectionTest.php)  
Perintah: `php vendor/bin/phpunit tests/Inspection --filter nfr017`

**Total test case: 1, sukses: 0, gagal: 1** (ditulis 1 / 0)

Screenshot bukti diletakkan di folder `lulus/` dan `gagal/` di sebelah berkas ini.

## Daftar test case

- GAGAL: nfr017 no unused imports
  - Import tak terpakai:
  - routes/auth.php => App\Http\Controllers\Auth\RegisteredUserController
