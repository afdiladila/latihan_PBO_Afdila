<?php
require_once 'Tiket.php';

class TiketRegular extends Tiket {
    private $tipeAudio;
    private $lokasiBaris;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $tipeAudio, $lokasiBaris) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->tipeAudio = $tipeAudio;
        $this->lokasiBaris = $lokasiBaris;
    }

    // OVERRIDING: Tarif standar murni tanpa biaya tambahan fasilitas
    public function hitungTotalHarga() {
        return $this->jumlah_kursi * $this->harga_dasar_tiket;
    }

    public function tampilkanInfoFasilitas() {
        return "Studio Regular [Audio: {$this->tipeAudio}, Baris: {$this->lokasiBaris}]";
    }
}