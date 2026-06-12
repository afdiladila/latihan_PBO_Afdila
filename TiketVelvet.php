<?php
require_once 'Tiket.php';

class TiketVelvet extends Tiket {
    // Properti tambahan spesifik
    private $bantalSelimutPack;
    private $layananButler;

    // Constructor
    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $bantalSelimutPack, $layananButler) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->bantalSelimutPack = $bantalSelimutPack;
        $this->layananButler = $layananButler;
    }

    // Implementasi hitung total harga (Velvet Kelas VIP: Ada tambahan biaya kelas premium Rp 75.000 per kursi)
    public function hitungTotalHarga() {
        $biayaTambahanVelvet = 75000;
        return ($this->harga_dasar_tiket + $biayaTambahanVelvet) * $this->jumlah_kursi;
    }

    // Implementasi tampilkan info fasilitas
    public function tampilkanInfoFasilitas() {
        return "Studio Velvet Class [Fasilitas: {$this->bantalSelimutPack}, Layanan: {$this->layananButler}]";
    }
}