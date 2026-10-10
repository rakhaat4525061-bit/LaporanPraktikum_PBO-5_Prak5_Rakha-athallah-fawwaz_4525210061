<?php
declare(strict_types=1);

/**
 * Langkah 6 — latihan mandiri.
 *
 * Hierarki Notifikasi dengan tiga turunan: Email, SMS, WhatsApp.
 * kirimSemua() tanpa satu pun pemeriksaan tipe.
 */

// TODO 1: kelas abstrak Notifikasi
abstract class Notifikasi
{
    public function __construct(protected readonly string $tujuan) {}

    abstract public function kirim(string $pesan): void;

    /** Nama saluran diambil dari nama kelas sebenarnya (late static binding). */
    public function saluran(): string
    {
        return static::class;
    }
}

// TODO 2: tiga turunan, masing-masing dengan format pesan berbeda
class Email extends Notifikasi
{
    public function kirim(string $pesan): void
    {
        echo "[{$this->saluran()}] Kepada: {$this->tujuan}", PHP_EOL;
        echo "    Subjek : Pemberitahuan", PHP_EOL;
        echo "    Isi    : {$pesan}", PHP_EOL;
    }
}

class SMS extends Notifikasi
{
    private const int BATAS_KARAKTER = 160;

    public function kirim(string $pesan): void
    {
        // SMS dibatasi 160 karakter, sisanya dipotong
        $isi = mb_strlen($pesan) > self::BATAS_KARAKTER
            ? mb_substr($pesan, 0, self::BATAS_KARAKTER - 3) . '...'
            : $pesan;

        echo "[{$this->saluran()}] ke {$this->tujuan}: {$isi}", PHP_EOL;
    }
}

class WhatsApp extends Notifikasi
{
    public function kirim(string $pesan): void
    {
        echo "[{$this->saluran()}] ke {$this->tujuan}", PHP_EOL;
        echo "    *Pemberitahuan*", PHP_EOL;
        echo "    {$pesan}", PHP_EOL;
    }
}

/**
 * TODO 3: kirim pesan ke seluruh notifikasi dalam daftar.
 * Tanpa instanceof, tanpa match/switch atas jenis: polimorfisme yang memilih.
 *
 * @param Notifikasi[] $daftar
 */
function kirimSemua(array $daftar, string $pesan): void
{
    foreach ($daftar as $notifikasi) {
        $notifikasi->kirim($pesan);
    }
}

// Uji
kirimSemua([
    new Email('ani@univpancasila.ac.id'),
    new SMS('081234567890'),
    new WhatsApp('081234567890'),
], 'Buku yang Anda pesan sudah tersedia.');