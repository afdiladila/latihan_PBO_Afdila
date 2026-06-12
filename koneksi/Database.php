<?php
class Database {
    // Konfigurasi atribut database
    private $host     = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name  = "db_latihan_pbo_trpl1a_afdila_dwiyani"; // Sesuai dengan nama database kamu
    protected $conn;

    // Method untuk membuka koneksi
    public function connect() {
        $this->conn = null;

       try {
    $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
    $this->conn = new PDO($dsn, $this->username, $this->password);
    
    // Set error mode ke Exception
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // HILANGKAN DENGAN MEMBERI TANDA // DI DEPAN ECHO:
    // echo "<div style='padding: 10px; margin: 10px 0; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; font-family: Arial, sans-serif;'>";
    // echo "<strong>Sukses!</strong> Koneksi ke database <u>" . $this->db_name . "</u> BERHASIL!";
    // echo "</div>";

} catch(PDOException $e) {

        } catch(PDOException $e) {
            // Tampilkan pesan jika gagal terkoneksi
            echo "<div style='padding: 10px; margin: 10px 0; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; font-family: Arial, sans-serif;'>";
            echo "<strong>Gagal!</strong> Koneksi Database Bermasalah: " . $e->getMessage();
            echo "</div>";
        }

        return $this->conn;
    }
}

// =========================================================================
// OTOMATIS JALANKAN TES KONEKSI
// =========================================================================
// Kode di bawah ini akan langsung mengeksekusi class di atas saat file diakses
//$test_koneksi = new Database();
//$test_koneksi->connect();
?>