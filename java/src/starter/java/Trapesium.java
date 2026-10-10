public class Trapesium extends BangunDatar {

    private final double sisiAtas, sisiBawah, tinggi, kakiKiri, kakiKanan;

    public Trapesium(double sisiAtas, double sisiBawah, double tinggi, double kakiKiri, double kakiKanan) {
        super("Trapesium");
        pastikanPositif("sisiAtas", sisiAtas);
        pastikanPositif("sisiBawah", sisiBawah);
        pastikanPositif("tinggi", tinggi);
        pastikanPositif("kakiKiri", kakiKiri);
        pastikanPositif("kakiKanan", kakiKanan);
        // Kaki miring tidak mungkin lebih pendek daripada tinggi
        if (kakiKiri < tinggi || kakiKanan < tinggi) {
            throw new IllegalArgumentException("Kaki trapesium tidak boleh lebih pendek dari tinggi");
        }
        this.sisiAtas = sisiAtas;
        this.sisiBawah = sisiBawah;
        this.tinggi = tinggi;
        this.kakiKiri = kakiKiri;
        this.kakiKanan = kakiKanan;
    }

    private static void pastikanPositif(String nama, double nilai) {
        if (Double.isNaN(nilai) || nilai <= 0) {
            throw new IllegalArgumentException(nama + " harus lebih dari 0, tetapi diterima: " + nilai);
        }
    }

    @Override public double luas()     { return (sisiAtas + sisiBawah) / 2 * tinggi; }
    @Override public double keliling() { return sisiAtas + sisiBawah + kakiKiri + kakiKanan; }
}