**Nama : Ramadhan Rizky Athalloh**
**NIM  : 102022400261**
**Kelas : SI 4809**


# Analisis Transaksi Kritis

Pada service yang saya kerjakan terdapat beberapa endpoint yang digunakan untuk melihat daftar properti, melihat detail properti, mengecek ketersediaan properti, dan menambahkan properti baru. Dari beberapa endpoint tersebut, saya memilih endpoint POST /api/v1/listings sebagai transaksi kritis.

Endpoint ini digunakan untuk menambahkan data properti baru ke dalam sistem. Berbeda dengan endpoint yang hanya digunakan untuk melihat data, endpoint ini mengubah isi database karena menghasilkan data baru yang akan digunakan pada proses bisnis berikutnya. Setelah data properti berhasil ditambahkan, informasi tersebut dapat digunakan oleh penyewa untuk melihat detail properti & mengecek ketersediaan unit sewa.

Karena transaksi ini menghasilkan perubahan data dan memiliki dampak pada proses bisnis lainnya, maka transaksi ini dianggap sebagai transaksi yang paling penting untuk diintegrasikan dengan layanan audit dan message broker.

# Analisis Federated SSO

Pada tugas ini aplikasi dihubungkan dengan Cloud SSO yang disediakan oleh dosen. Sebelum mengakses layanan terpusat lainnya, aplikasi perlu meminta JWT Token ke Cloud SSO menggunakan API Key yang telah diberikan.

Setelah API Key berhasil diverifikasi, Cloud SSO akan mengembalikan JWT Token yang nantinya digunakan saat aplikasi berkomunikasi dengan layanan SOAP Audit. Dengan cara ini proses autentikasi tidak dikelola secara terpisah oleh setiap service, tetapi dipusatkan pada layanan SSO.
