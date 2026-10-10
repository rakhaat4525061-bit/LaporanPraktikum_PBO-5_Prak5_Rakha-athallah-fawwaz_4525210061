<?php
declare(strict_types=1);

abstract class BangunDatar
{
    public function __construct(private readonly string $nama) {}

    abstract public function luas(): float;
    abstract public function keliling(): float;

    public function getNama(): string { return $this->nama; }

    public function __toString(): string
    {
        return sprintf('%-12s luas=%10.2f  keliling=%10.2f',
            $this->nama, $this->luas(), $this->keliling());
    }
}

class Lingkaran extends BangunDatar
{
    public function __construct(private readonly float $jariJari)
    {
        parent::__construct('Lingkaran');
        // TODO 1: tolak jari-jari <= 0
        if (is_nan($jariJari) || $jariJari <= 0) {
            throw new InvalidArgumentException(
                'Jari-jari harus lebih dari 0, tetapi diterima: ' . $jariJari
            );
        }
    }

    // TODO 2: luas = π × r², keliling = 2 × π × r (M_PI, bukan 3.14)
    public function luas(): float     { return M_PI * $this->jariJari ** 2; }
    public function keliling(): float { return 2 * M_PI * $this->jariJari; }

    public function getJariJari(): float { return $this->jariJari; }
}

class Persegi extends BangunDatar
{
    public function __construct(private readonly float $sisi)
    {
        parent::__construct('Persegi');
        // TODO 1: tolak sisi <= 0
        if (is_nan($sisi) || $sisi <= 0) {
            throw new InvalidArgumentException(
                'Sisi harus lebih dari 0, tetapi diterima: ' . $sisi
            );
        }
    }

    // TODO 2: luas = sisi × sisi, keliling = 4 × sisi
    public function luas(): float     { return $this->sisi * $this->sisi; }
    public function keliling(): float { return 4 * $this->sisi; }
}

// TODO Langkah 2: Segitiga (rumus Heron)
class Segitiga extends BangunDatar
{
    public function __construct(
        private readonly float $a,
        private readonly float $b,
        private readonly float $c,
    ) {
        parent::__construct('Segitiga');

        if (is_nan($a) || is_nan($b) || is_nan($c) || $a <= 0 || $b <= 0 || $c <= 0) {
            throw new InvalidArgumentException(
                "Sisi segitiga harus lebih dari 0, tetapi diterima: $a, $b, $c"
            );
        }
        // Pertidaksamaan segitiga: jumlah dua sisi harus lebih panjang dari sisi ketiga
        if ($a + $b <= $c || $a + $c <= $b || $b + $c <= $a) {
            throw new InvalidArgumentException(
                "Sisi $a, $b, $c tidak dapat membentuk segitiga"
            );
        }
    }

    public function keliling(): float { return $this->a + $this->b + $this->c; }

    // Rumus Heron: sqrt(s(s-a)(s-b)(s-c)), s = setengah keliling
    public function luas(): float
    {
        $s = $this->keliling() / 2;
        return sqrt($s * ($s - $this->a) * ($s - $this->b) * ($s - $this->c));
    }
}

// TODO Langkah 4: Trapesium
class Trapesium extends BangunDatar
{
    public function __construct(
        private readonly float $sisiAtas,
        private readonly float $sisiBawah,
        private readonly float $tinggi,
        private readonly float $kakiKiri,
        private readonly float $kakiKanan,
    ) {
        parent::__construct('Trapesium');

        self::pastikanPositif('sisiAtas', $sisiAtas);
        self::pastikanPositif('sisiBawah', $sisiBawah);
        self::pastikanPositif('tinggi', $tinggi);
        self::pastikanPositif('kakiKiri', $kakiKiri);
        self::pastikanPositif('kakiKanan', $kakiKanan);

        // Kaki miring tidak mungkin lebih pendek daripada tinggi
        if ($kakiKiri < $tinggi || $kakiKanan < $tinggi) {
            throw new InvalidArgumentException('Kaki trapesium tidak boleh lebih pendek dari tinggi');
        }
    }

    private static function pastikanPositif(string $nama, float $nilai): void
    {
        if (is_nan($nilai) || $nilai <= 0) {
            throw new InvalidArgumentException("$nama harus lebih dari 0, tetapi diterima: $nilai");
        }
    }

    public function luas(): float     { return ($this->sisiAtas + $this->sisiBawah) / 2 * $this->tinggi; }
    public function keliling(): float { return $this->sisiAtas + $this->sisiBawah + $this->kakiKiri + $this->kakiKanan; }
}

// ── Program uji (perulangan tidak boleh diubah, hanya boleh menambah isi array) ──
$daftar = [
    new Lingkaran(7),
    new Persegi(5),
    new Segitiga(3, 4, 5),
    new Trapesium(4, 10, 4, 5, 5),
];

echo '=== Bangun Datar ===', PHP_EOL;
foreach ($daftar as $b) {
    echo '  ', $b, PHP_EOL;
}

$total = 0.0;
foreach ($daftar as $b) {
    $total += $b->luas();
}
printf('%s  Total luas: %.2f%s', PHP_EOL, $total, PHP_EOL);

echo PHP_EOL, '=== Downcasting hanya bila benar-benar perlu ===', PHP_EOL;
foreach ($daftar as $b) {
    if ($b instanceof Lingkaran) {
        printf('  %s punya jari-jari %.1f%s', $b->getNama(), $b->getJariJari(), PHP_EOL);
    }
}