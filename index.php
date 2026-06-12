<?php
// 1. Include semua file yang dibutuhkan
require_once 'koneksi/database.php';
require_once 'Tiket.php';
require_once 'TiketRegular.php';
require_once 'TiketIMAX.php';
require_once 'TiketVelvet.php';

// 2. Inisialisasi Database dan Koneksi
$database = new Database();
$db = $database->connect();

// Wadah untuk mengelompokkan objek tiket berdasarkan jenis studio
$studio_regular = [];
$studio_imax    = [];
$studio_velvet  = [];

if ($db) {
    try {
        // Ambil seluruh 25 data baris dari database
        $query = "SELECT * FROM tabel_tiket ORDER BY id_tiket ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();

        // 3. Polimorfisme: Mengubah baris database menjadi Objek Spesifik
        while ($row = $stmt->fetch()) {
            $jenis = strtolower($row['jenis_studio']);

            if ($jenis == 'regular') {
                $studio_regular[] = new TiketRegular(
                    $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], 
                    $row['jumlah_kursi'], $row['harga_dasar_tiket'], 
                    $row['tipe_audio'], $row['lokasi_baris']
                );
            } 
            elseif ($jenis == '3d' || $jenis == '4dx' || $jenis == 'imax') { 
                // Kita petakan data studio efek/3D ke kelas TiketIMAX sesuai instruksi soal sebelumnya
                // Jika kolom kacamata_3d_efek atau efek_gerak_fitur kosong di db, kita beri nilai default
                $kacamata = $row['kacamata_3d_efek'] ?? 'Standard IMAX 3D';
                $efek = $row['efek_gerak_fitur'] ?? 'Proyeksi Digital / Audio Flat';
                
                $studio_imax[] = new TiketIMAX(
                    $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], 
                    $row['jumlah_kursi'], $row['harga_dasar_tiket'], 
                    $kacamata, $efek
                );
            } 
            elseif ($jenis == 'premiere' || $jenis == 'vip' || $jenis == 'velvet') {
                $bantal = $row['bantal_selimut_pack'] ?? 'Premium Cushion Pack';
                $butler = $row['layanan_butler'] ?? 'On-Call Butler Service';

                $studio_velvet[] = new TiketVelvet(
                    $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], 
                    $row['jumlah_kursi'], $row['harga_dasar_tiket'], 
                    $bantal, $butler
                );
            }
        }
    } catch (PDOException $e) {
        echo "Gagal mengambil data: " . $e->getMessage();
    }
}

// Fungsi helper untuk merender tabel secara dinamis agar tidak menulis ulang HTML
function renderTabelTiket($daftar_tiket, $warna_tema) {
    if (empty($daftar_tiket)) {
        echo "<tr><td colspan='7' style='text-align:center; color:#888;'>Belum ada pesanan tiket untuk kategori ini.</td></tr>";
        return;
    }

    foreach ($daftar_tiket as $tiket) {
        echo "<tr>";
        echo "<td>" . $tiket->getIdTiket() . "</td>";
        echo "<td><strong>" . $tiket->getNamaFilm() . "</strong></td>";
        echo "<td>" . date('d M Y - H:i', strtotime($tiket->getJadwalTayang())) . " WIB</td>";
        echo "<td>" . $tiket->getJumlahKursi() . " Kursi</td>";
        echo "<td>Rp " . number_format($tiket->getHargaDasarTiket(), 0, ',', '.') . "</td>";
        
        // Memanfaatkan metode polimorfik hitungTotalHarga()
        echo "<td style='color:{$warna_tema}; font-weight:bold;'>Rp " . number_format($tiket->hitungTotalHarga(), 0, ',', '.') . "</td>";
        
        // Memanfaatkan metode polimorfik tampilkanInfoFasilitas()
        echo "<td><small><em>" . $tiket->tampilkanInfoFasilitas() . "</em></small></td>";
        echo "</tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Manajemen Tiket Bioskop - OOP View</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f6f9; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; color: #222; margin-bottom: 5px; }
        .sub-title { text-align: center; color: #666; margin-bottom: 30px; font-size: 14px; }
        
        /* Desain Section Kelompok Studio */
        .studio-section { background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 35px; overflow: hidden; border-top: 5px solid #6c757d; }
        .studio-section.regular { border-top-color: #2b5876; }
        .studio-section.imax { border-top-color: #e65c00; }
        .studio-section.velvet { border-top-color: #833ab4; }
        
        .studio-header { padding: 15px 20px; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center; }
        .studio-title { margin: 0; font-size: 18px; font-weight: bold; }
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; color: #fff; font-weight: bold; }
        .bg-regular { background: #2b5876; }
        .bg-imax { background: #e65c00; }
        .bg-velvet { background: #833ab4; }

        /* Desain Tabel */
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 12px 20px; border-bottom: 1px solid #eee; font-size: 14px; }
        th { background-color: #fafafa; color: #555; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
        tr:hover { background-color: #fcfcfc; }
    </style>
</head>
<body>

<div class="container">
    <h1>Daftar Reservasi Tiket Bioskop</h1>
    <div class="sub-title">Implementasi Pilar Abstraksi, Enkapsulasi, dan Polimorfisme Berbasis Objek (PBO)</div>

    <div class="studio-section regular">
        <div class="studio-header">
            <h2 class="studio-title">🍿 Kategori Studio Regular</h2>
            <span class="badge bg-regular">Tarif Standar murni</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Film</th>
                    <th>Jadwal Tayang</th>
                    <th>Jumlah</th>
                    <th>Harga Dasar</th>
                    <th>Total Harga</th>
                    <th>Spesifikasi Fasilitas Unik (Polimorfik)</th>
                </tr>
            </thead>
            <tbody>
                <?php renderTabelTiket($studio_regular, '#2b5876'); ?>
            </tbody>
        </table>
    </div>

    <div class="studio-section imax">
        <div class="studio-header">
            <h2 class="studio-title">🎬 Kategori Studio IMAX / 3D / 4DX</h2>
            <span class="badge bg-imax">Surcharge +Rp 35.000 Flat</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Film</th>
                    <th>Jadwal Tayang</th>
                    <th>Jumlah</th>
                    <th>Harga Dasar</th>
                    <th>Total Harga</th>
                    <th>Spesifikasi Fasilitas Unik (Polimorfik)</th>
                </tr>
            </thead>
            <tbody>
                <?php renderTabelTiket($studio_imax, '#e65c00'); ?>
            </tbody>
        </table>
    </div>

    <div class="studio-section velvet">
        <div class="studio-header">
            <h2 class="studio-title">🛋️ Kategori Studio Velvet / Premiere</h2>
            <span class="badge bg-velvet">Surcharge +50% Premium</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Film</th>
                    <th>Jadwal Tayang</th>
                    <th>Jumlah</th>
                    <th>Harga Dasar</th>
                    <th>Total Harga</th>
                    <th>Spesifikasi Fasilitas Unik (Polimorfik)</th>
                </tr>
            </thead>
            <tbody>
                <?php renderTabelTiket($studio_velvet, '#833ab4'); ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>