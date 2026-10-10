public class Segitiga extends BangunDatar {

    private final double a, b, c;

    public Segitiga(double a, double b, double c) {
        super("Segitiga");
        if (Double.isNaN(a) || Double.isNaN(b) || Double.isNaN(c) || a <= 0 || b <= 0 || c <= 0) {
            throw new IllegalArgumentException(
                    "Sisi segitiga harus lebih dari 0, tetapi diterima: " + a + ", " + b + ", " + c);
        }
        // Pertidaksamaan segitiga: jumlah dua sisi harus lebih panjang dari sisi ketiga
        if (a + b <= c || a + c <= b || b + c <= a) {
            throw new IllegalArgumentException(
                    "Sisi " + a + ", " + b + ", " + c + " tidak dapat membentuk segitiga");
        }
        this.a = a;
        this.b = b;
        this.c = c;
    }

    @Override public double keliling() { return a + b + c; }

    // Rumus Heron: sqrt(s(s-a)(s-b)(s-c)), s = setengah keliling
    @Override public double luas() {
        double s = keliling() / 2;
        return Math.sqrt(s * (s - a) * (s - b) * (s - c));
    }
}