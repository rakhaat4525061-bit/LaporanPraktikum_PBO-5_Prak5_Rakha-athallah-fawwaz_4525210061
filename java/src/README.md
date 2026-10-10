# Sesi 5 — Polimorfisme

Latihan kelima PBO: **polimorfisme**. Satu perulangan memproses banyak jenis objek, dan menambah jenis baru cukup dengan **menambah kelas**, tanpa mengubah logika perulangan. Terdiri dari dua bagian:

1. **Bangun Datar** (Java dan PHP)
2. **Notifikasi** (latihan mandiri, PHP)

## Identitas

| Nama                  | NIM        |
|-----------------------|------------|
| Rakha Athallah Fawwaz | 4525210061 |

---

## Bagian 1 — Bangun Datar

### Hierarki Kelas

```
BangunDatar (abstract)
├── Lingkaran
├── Persegi
├── Segitiga
└── Trapesium
```

| Kelas        | Parameter                                          | Luas                        | Keliling            |
|--------------|----------------------------------------------------|-----------------------------|---------------------|
| `Lingkaran`  | `jariJari`                                         | π × r²                      | 2 × π × r           |
| `Persegi`    | `sisi`                                             | sisi × sisi                 | 4 × sisi            |
| `Segitiga`   | `a, b, c` (tiga sisi)                              | Rumus Heron                 | a + b + c           |
| `Trapesium`  | `sisiAtas, sisiBawah, tinggi, kakiKiri, kakiKanan` | (atas + bawah) / 2 × tinggi | jumlah keempat sisi |

> Parameter `Trapesium` adalah asumsi penulis karena soal tidak menetapkannya. Lima parameter dipilih supaya `keliling()` bisa dihitung.

### Invariant

| No | Invariant                                                 | Cara Menjaganya                                                  |
|----|-----------------------------------------------------------|------------------------------------------------------------------|
| 1  | Jari-jari, sisi, dan ukuran lain harus lebih dari 0       | Validasi di constructor, lempar exception                        |
| 2  | Tiga sisi segitiga memenuhi pertidaksamaan segitiga       | Pemeriksaan `a + b > c` untuk ketiga kombinasi                   |
| 3  | Kaki trapesium tidak boleh lebih pendek dari tingginya    | Pemeriksaan `kaki >= tinggi` di constructor                      |
| 4  | Ukuran bangun tidak berubah setelah dibuat                | `final` (Java) / `readonly` (PHP)                                |
| 5  | Nilai `NaN` tidak dianggap ukuran yang sah                | `Double.isNaN` / `is_nan` dicek terpisah                         |

### Hasil yang Diharapkan

```java
BangunDatar[] daftar = {
    new Lingkaran(7),
    new Persegi(5),
    new Segitiga(3, 4, 5),
    new Trapesium(4, 10, 4, 5, 5),
};
```

| Bangun                    | Luas       | Keliling |
|---------------------------|------------|----------|
| Lingkaran(7)              | 153,94     | 43,98    |
| Persegi(5)                | 25,00      | 20,00    |
| Segitiga(3, 4, 5)         | 6,00       | 12,00    |
| Trapesium(4, 10, 4, 5, 5) | 28,00      | 24,00    |
| **Total luas**            | **212,94** |          |

Versi PHP di `main.php` memakai tiga bangun pertama saja (sebelum Langkah 4), sehingga total luasnya **184,94**.

Contoh keluaran PHP:

```
=== Bangun Datar ===
  Lingkaran    luas=    153.94  keliling=     43.98
  Persegi      luas=     25.00  keliling=     20.00
  Segitiga     luas=      6.00  keliling=     12.00

  Total luas: 184.94
```

---

## Bagian 2 — Notifikasi (Latihan Mandiri, PHP)

### Hierarki Kelas

```
Notifikasi (abstract)
├── Email
├── SMS
└── WhatsApp
```

| Kelas          | Format Pesan                                                    |
|----------------|-----------------------------------------------------------------|
| `Notifikasi`   | Induk: properti `readonly $tujuan`, `kirim()` abstract, `saluran()` |
| `Email`        | Ada baris `Subjek` dan `Isi`                                    |
| `SMS`          | Satu baris, dipotong jika lebih dari 160 karakter               |
| `WhatsApp`     | Judul bertanda `*...*` (huruf tebal) lalu isi pesan             |

### Aturan Latihan

`kirimSemua()` **tidak boleh** memakai `instanceof` maupun `match`/`switch` atas jenis notifikasi. Jika terasa perlu, berarti hierarkinya belum benar.

```php
function kirimSemua(array $daftar, string $pesan): void
{
    foreach ($daftar as $notifikasi) {
        $notifikasi->kirim($pesan);
    }
}
```

### Contoh Keluaran

```
[Email] Kepada: ani@univpancasila.ac.id
    Subjek : Pemberitahuan
    Isi    : Buku yang Anda pesan sudah tersedia.
[SMS] ke 081234567890: Buku yang Anda pesan sudah tersedia.
[WhatsApp] ke 081234567890
    *Pemberitahuan*
    Buku yang Anda pesan sudah tersedia.
```

---

## Struktur Berkas

```
.
├── java/
│   ├── BangunDatar.java
│   ├── Lingkaran.java
│   ├── Persegi.java
│   ├── Segitiga.java
│   ├── Trapesium.java
│   └── Main.java
├── php/
│   ├── BangunDatar.php    (BangunDatar dan seluruh turunannya)
│   ├── main.php
│   └── notifikasi.php
└── README.md
```

## Konsep Utama

### 1. Upcasting

Array bertipe `BangunDatar[]` (atau `Notifikasi[]`) menampung objek turunan. Variabel bertipe induk boleh merujuk objek bertipe turunan.

### 2. Polimorfisme (dynamic dispatch)

Pada `b.luas()`, `toString()`, atau `kirim()`, versi method dipilih berdasarkan **kelas objek sebenarnya** saat program berjalan, bukan tipe variabelnya.

### 3. Perulangan tidak boleh diubah

Menambah bangun atau saluran baru hanya boleh menambah kelas dan satu baris di array. Jika perulangan harus diubah, rancangannya belum polimorfik. Prinsipnya: terbuka untuk penambahan, tertutup untuk perubahan.

### 4. Downcasting hanya bila perlu

`getJariJari()` hanya ada di `Lingkaran`. Untuk memakainya, objek diperiksa dulu:

```java
if (b instanceof Lingkaran l) {                 // Java 16+
    System.out.printf("  %s punya jari-jari %.1f%n", l.getNama(), l.getJariJari());
}
```

```php
if ($b instanceof Lingkaran) {                  // PHP
    printf('  %s punya jari-jari %.1f%s', $b->getNama(), $b->getJariJari(), PHP_EOL);
}
```

### 5. Rumus Heron

```
s    = (a + b + c) / 2
luas = √( s · (s − a) · (s − b) · (s − c) )
```

Untuk 3-4-5: s = 6, luas = √(6·3·2·1) = **6**.

### 6. `static::class` pada `saluran()`

`saluran()` cukup ditulis sekali di `Notifikasi`. Karena memakai `static::class`, ia mengembalikan nama kelas turunan yang sebenarnya (`Email`, `SMS`, `WhatsApp`), tanpa perlu ditulis ulang di tiap turunan.

## Cara Menjalankan

### Java

Prasyarat: **JDK 16 atau lebih baru** (`instanceof` dengan pattern matching).

```bash
cd java
javac -d out *.java
java -cp out Main
```

### PHP

Prasyarat: **PHP 8.3 atau lebih baru** (konstanta bertipe seperti `private const int`).
Untuk PHP 8.0–8.2, hapus kata `int` pada deklarasi konstanta. `readonly` pada properti butuh PHP 8.1+.

```bash
cd php
php main.php
php notifikasi.php
```

> `BangunDatar.php` hanya berisi kelas. Jangan menaruh kode uji di dalamnya, karena `main.php` memuatnya dengan `require_once` dan keluaran akan tercetak dua kali.

## Perbandingan Java vs PHP

| Konsep                    | Java                                   | PHP                                             |
|---------------------------|----------------------------------------|-------------------------------------------------|
| Konstanta π               | `Math.PI`                              | `M_PI`                                          |
| Pangkat dua               | `r * r`                                | `r ** 2`                                        |
| Akar kuadrat              | `Math.sqrt(x)`                         | `sqrt($x)`                                      |
| Cek NaN                   | `Double.isNaN(x)`                      | `is_nan($x)`                                    |
| Exception validasi        | `IllegalArgumentException`             | `InvalidArgumentException`                      |
| Cek tipe                  | `b instanceof Lingkaran l`             | `$b instanceof Lingkaran`                       |
| Nama kelas sebenarnya     | `getClass().getSimpleName()`           | `static::class`                                 |
| Panggil constructor induk | `super("Nama")` (wajib pertama)        | `parent::__construct('Nama')`                   |
| Atribut tak berubah       | `final`                                | `readonly`                                      |

## Catatan Desain

- **Validasi di constructor:** objek yang tidak sah tidak pernah selesai dibuat karena exception dilempar sebelum objek bisa dipakai.
- **`super(...)` lebih dulu (Java):** validasi diletakkan setelah `super("Nama")` karena `super(...)` harus menjadi pernyataan pertama.
- **`Math.PI` / `M_PI`, bukan 3.14:** presisi penuh dan tanpa angka ajaib.
- **Trapesium sama kaki sebagai contoh:** `(4, 10, 4, 5, 5)` valid karena selisih alas 6 dibagi dua menjadi 3, dan segitiga 3-4-5 membuat kakinya tepat 5.
- **Yang belum divalidasi:** `$tujuan` pada `Notifikasi` belum ditolak jika kosong, dan kombinasi kaki trapesium yang mustahil secara geometri belum diperiksa (hanya syarat `kaki >= tinggi`).