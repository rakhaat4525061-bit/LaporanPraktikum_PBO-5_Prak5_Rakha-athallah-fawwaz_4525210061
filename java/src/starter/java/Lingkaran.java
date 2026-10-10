public class Lingkaran extends BangunDatar {

    private final double jariJari;

    public Lingkaran(double jariJari) {
        super("Lingkaran");
        // TODO 1: tolak jari-jari <= 0
        if (Double.isNaN(jariJari) || jariJari <= 0) {
            throw new IllegalArgumentException(
                    "Jari-jari harus lebih dari 0, tetapi diterima: " + jariJari);
        }
        this.jariJari = jariJari;
    }

    // TODO 2: luas = π × r², keliling = 2 × π × r
    @Override public double luas()     { return Math.PI * jariJari * jariJari; }
    @Override public double keliling() { return 2 * Math.PI * jariJari; }

    public double getJariJari() { return jariJari; }
}