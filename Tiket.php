<?php

// 1. Membuat abstract class bernama Tiket
abstract class Tiket {
    
    // 2. Properti/Atribut terenkapsulasi (protected)
    // Atribut-atribut ini memetakan kolom Atribut Global dari tabel_tiket
    protected $id_tiket;
    protected $nama_film;
    protected $jadwal_tayang;
    protected $jumlah_kursi;
    protected $harga_dasar_tiket;

    // Constructor untuk menginisialisasi data saat objek dibuat
    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket) {
        $this->id_tiket = $id_tiket;
        $this->nama_film = $nama_film;
        $this->jadwal_tayang = $jadwal_tayang;
        $this->jumlah_kursi = $jumlah_kursi;
        $this->harga_dasar_tiket = $harga_dasar_tiket;
    }

    // Getter (Opsional, berguna jika class luar ingin mengambil nilai properti protected)
    public function getIdTiket() { return $this->id_tiket; }
    public function getNamaFilm() { return $this->nama_film; }
    public function getJadwalTayang() { return $this->jadwal_tayang; }
    public function getJumlahKursi() { return $this->jumlah_kursi; }
    public function getHargaDasarTiket() { return $this->harga_dasar_tiket; }

    // =========================================================================
    // 3. Metode Abstrak (Tanpa isi/body)
    // Setiap class anak WAJIB mengimplementasikan (override) kedua method ini.
    // =========================================================================
    
    // Menghitung total harga (misal: harga dasar * jumlah kursi + biaya tambahan studio)
    abstract public function hitungTotalHarga();

    // Menampilkan informasi fasilitas spesifik yang didapatkan dari jenis studio
    abstract public function tampilkanInfoFasilitas();
}