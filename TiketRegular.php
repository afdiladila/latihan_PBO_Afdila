<?php
require_once 'Tiket.php';

class TiketRegular extends Tiket {
    // Properti tambahan spesifik
    private $tipeAudio;
    private $lokasiBaris;

    // Constructor
    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $tipeAudio, $lokasiBaris) {
        // Panggil constructor milik abstract class Tiket
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->tipeAudio = $tipeAudio;
        $this->lokasiBaris = $lokasiBaris;
    }

    // Implementasi hitung total harga (Regular: Tanpa biaya tambahan studio)
    public function hitungTotalHarga() {
        return $this->harga_dasar_tiket * $this->jumlah_kursi;
    }

    // Implementasi tampilkan info fasilitas
    public function tampilkanInfoFasilitas() {
        return "Studio Regular [Audio: {$this->tipeAudio}, Baris: {$this->lokasiBaris}]";
    }
}