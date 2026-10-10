public class Persegi extends BangunDatar {

    private final double sisi;

    public Persegi(double sisi) {
        super("Persegi");
        // TODO 1: tolak sisi <= 0
        if (Double.isNaN(sisi) || sisi <= 0) {
            throw new IllegalArgumentException(
                    "Sisi harus lebih dari 0, tetapi diterima: " + sisi);
        }
        this.sisi = sisi;
    }

    // TODO 2: luas = sisi × sisi, keliling = 4 × sisi
    @Override public double luas()     { return sisi * sisi; }
    @Override public double keliling() { return 4 * sisi; }
}