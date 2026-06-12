<?php
require_once 'Tiket.php';

class TiketIMAX extends Tiket {
    // Properti tambahan spesifik
    private $kacamata3dId;
    private $efekGerakFitur;

    // Constructor
    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $kacamata3dId, $efekGerakFitur) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->kacamata3dId = $kacamata3dId;
        $this->efekGerakFitur = $efekGerakFitur;
    }

    // Implementasi hitung total harga (IMAX: Ada tambahan biaya studio Rp 30.000 per kursi)
    public function hitungTotalHarga() {
        $biayaTambahanIMAX = 30000;
        return ($this->harga_dasar_tiket + $biayaTambahanIMAX) * $this->jumlah_kursi;
    }

    // Implementasi tampilkan info fasilitas
    public function tampilkanInfoFasilitas() {
        return "Studio IMAX [Kacamata 3D ID: {$this->kacamata3dId}, Efek: {$this->efekGerakFitur}]";
    }
}