<?php
require_once 'Tiket.php';

class TiketIMAX extends Tiket {
    private $kacamata3dId;
    private $efekGerakFitur;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $kacamata3dId, $efekGerakFitur) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->kacamata3dId = $kacamata3dId;
        $this->efekGerakFitur = $efekGerakFitur;
    }

    // OVERRIDING: Ditambah biaya flat Rp 35.000 untuk teknologi proyeksi & audio IMAX
    public function hitungTotalHarga() {
        return ($this->jumlah_kursi * $this->harga_dasar_tiket) + 35000;
    }

    public function tampilkanInfoFasilitas() {
        return "Studio IMAX [Kacamata 3D ID: {$this->kacamata3dId}, Efek: {$this->efekGerakFitur}]";
    }
}