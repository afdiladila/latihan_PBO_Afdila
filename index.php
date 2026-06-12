<?php
// 1. Include semua file class yang dibutuhkan
require_once 'koneksi/database.php';
require_once 'Tiket.php';
require_once 'TiketRegular.php';
require_once 'TiketIMAX.php';
require_once 'TiketVelvet.php';

// Instansiasi Koneksi Database
$database = new Database(); 
$db = $database->connect(); 

// Mengambil parameter halaman dari URL
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Inisialisasi variabel statistik
$total_tiket_count = 0;
$total_pendapatan = 0;
$total_kursi_terjual = 0;
$porsi_velvet_count = 0;

$daftar_tiket = [];
$judul_halaman = "ALL DATA";

if ($db) {
    try {
        // Ambil SEMUA data tiket untuk akumulasi kalkulasi kartu metrik (Info Cards)
        $query_stats = "SELECT * FROM tabel_tiket";
        $stmt_stats = $db->prepare($query_stats);
        $stmt_stats->execute();
        
        while ($row = $stmt_stats->fetch()) {
            $jenis_stat = strtolower($row['jenis_studio']);
            $total_tiket_count++;
            $total_kursi_terjual += $row['jumlah_kursi'];
            
            $harga_dasar = $row['harga_dasar_tiket'];
            $jumlah = $row['jumlah_kursi'];
            
            if ($jenis_stat == 'regular') {
                $total_pendapatan += ($harga_dasar * $jumlah);
            } elseif (in_array($jenis_stat, ['imax', '3d', '4dx'])) {
                $total_pendapatan += (($harga_dasar * $jumlah) + 35000);
            } elseif (in_array($jenis_stat, ['velvet', 'premiere', 'vip'])) {
                $total_pendapatan += (($harga_dasar * $jumlah) * 1.5);
                $porsi_velvet_count++;
            }
        }

        // Filter query data tabel sesuai dengan halaman aktif
        if ($page == 'imax') {
            $query = "SELECT * FROM tabel_tiket WHERE LOWER(jenis_studio) IN ('imax', '3d', '4dx') ORDER BY id_tiket ASC";
            $judul_halaman = "TIKET IMAX 3D";
        } elseif ($page == 'regular') {
            $query = "SELECT * FROM tabel_tiket WHERE LOWER(jenis_studio) = 'regular' ORDER BY id_tiket ASC";
            $judul_halaman = "TIKET REGULAR";
        } elseif ($page == 'velvet') {
            $query = "SELECT * FROM tabel_tiket WHERE LOWER(jenis_studio) IN ('velvet', 'premiere', 'vip') ORDER BY id_tiket ASC";
            $judul_halaman = "TIKET VELVET CLASS";
        } else {
            $query = "SELECT * FROM tabel_tiket ORDER BY id_tiket ASC";
            $judul_halaman = "DASHBOARD UTAMA";
        }

        $stmt = $db->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $jenis = strtolower($row['jenis_studio']);
            if ($jenis == 'regular') {
                $daftar_tiket[] = new TiketRegular($row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], $row['jumlah_kursi'], $row['harga_dasar_tiket'], $row['tipe_audio'], $row['lokasi_baris']);
            } elseif (in_array($jenis, ['3d', '4dx', 'imax'])) { 
                $daftar_tiket[] = new TiketIMAX($row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], $row['jumlah_kursi'], $row['harga_dasar_tiket'], $row['kacamata_3d_efek'] ?? 'Standard IMAX 3D', $row['efek_gerak_fitur'] ?? 'Proyeksi Digital');
            } elseif (in_array($jenis, ['premiere', 'vip', 'velvet'])) {
                $daftar_tiket[] = new TiketVelvet($row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], $row['jumlah_kursi'], $row['harga_dasar_tiket'], $row['bantal_selimut_pack'] ?? 'Premium Pack', $row['layanan_butler'] ?? 'On-Call Butler');
            }
        }
    } catch (PDOException $e) {
        echo "Gagal memuat data: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinePro Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS LAYOUT RE-ARRANGEMENT */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif; background-color: #f4f6f9; color: #333; display: flex; min-height: 100vh; }
        
        /* SIDEBAR COMPONENT */
        .sidebar { width: 260px; height: 100vh; background-color: #1a1e25; color: #fff; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; box-shadow: 2px 0 10px rgba(0,0,0,0.1); z-index: 100; }
        .sidebar-brand { padding: 20px 25px; font-size: 19px; font-weight: 700; border-bottom: 1px solid #262c37; display: flex; align-items: center; gap: 10px; color: #3b7ddd; }
        .sidebar-header-text { padding: 18px 25px 8px 25px; font-size: 11px; text-transform: uppercase; color: #4b5464; font-weight: 700; letter-spacing: 0.5px; }
        
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li a, .menu-trigger { display: flex; align-items: center; justify-content: space-between; padding: 12px 25px; color: #a2aab2; text-decoration: none; font-size: 13px; transition: all 0.2s; cursor: pointer; }
        .sidebar-menu li a i:first-child, .menu-trigger i:first-child { width: 18px; margin-right: 10px; font-size: 14px; }
        
        .sidebar-menu li a:hover, .menu-trigger:hover, .sidebar-menu li.active > a, .sidebar-menu li.active > .menu-trigger { background-color: #222731; color: #fff; }
        .sidebar-menu li.active > a { background-color: #3b7ddd !important; color: #fff !important; }
        
        /* SUBMENU STYLE (Tanpa Bullet & Variasi Font) */
        .submenu { list-style: none; padding: 0; margin: 0; background-color: #13161c; max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
        .submenu.open { max-height: 200px; }
        .submenu li { list-style: none; }
        .submenu li a { padding-left: 45px; display: flex; align-items: center; height: 40px; color: #a2aab2; text-decoration: none; font-size: 13px; }
        .submenu li.active a { color: #fff !important; background-color: #3b7ddd !important; font-weight: 700; }
        .arrow { transition: transform 0.3s; font-size: 11px; }
        .arrow.rotate { transform: rotate(180deg); }

        /* MAIN CONTENT AREA CONTAINER FIX */
        .main-content { margin-left: 260px; padding: 25px 30px; width: calc(100% - 260px); min-height: 100vh; display: block; }
        .top-breadcrumb { display: flex; align-items: center; justify-content: space-between; font-size: 13px; color: #6c757d; margin-bottom: 25px; }
        .breadcrumb-path a { color: #3b7ddd; text-decoration: none; }
        
        /* INFO CARDS BOX GRID */
        .cards-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; width: 100%; }
        .info-card { background: #fff; border-radius: 6px; padding: 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border-left: 4px solid #ddd; }
        .info-card.blue { border-left-color: #3b7ddd; }
        .info-card.red { border-left-color: #dc3545; }
        .info-card.green { border-left-color: #28a745; }
        .info-card.purple { border-left-color: #833ab4; }
        
        .card-title { font-size: 11px; font-weight: 700; color: #8e98a5; text-transform: uppercase; margin-bottom: 5px; display: block; }
        .card-value { font-size: 20px; font-weight: 700; color: #212529; }
        .card-icon-box { font-size: 22px; opacity: 0.2; }

        /* FILTER ROW CONTROLS */
        .filter-row { background: #fff; border-radius: 6px; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); width: 100%; }
        .search-container { display: flex; gap: 10px; width: 45%; }
        .search-container input { width: 100%; padding: 8px 12px; border: 1px solid #ced4da; border-radius: 4px; font-size: 13px; outline: none; }
        .btn-filter { background: #212529; color: #fff; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; }

        /* DATA TABLE CONTAINER */
        .table-card { background: #fff; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden; border: 1px solid #e9ecef; width: 100%; }
        .table-card-header { padding: 15px 20px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; background: #fff; }
        .table-card-title { font-size: 13px; font-weight: 700; color: #495057; text-transform: uppercase; display: flex; align-items: center; gap: 8px; }
        .row-count-badge { background: #e3effd; color: #3b7ddd; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 20px; font-size: 13px; border-bottom: 1px solid #edf2f9; text-align: left; }
        th { background-color: #f8f9fa; color: #6c757d; text-transform: uppercase; font-size: 11px; font-weight: 700; }
        tr:hover { background-color: #fcfcfc; }
        
        .object-badge { background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; }
        .status-active { background: #eaf6ed; color: #28a745; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; display: inline-block; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-film"></i> <span>CinePro PANEL</span>
    </div>
    
    <div class="sidebar-header-text">HEADER</div>
    <ul class="sidebar-menu">
        <li class="<?= ($page == 'dashboard') ? 'active' : ''; ?>">
            <a href="index.php?page=dashboard">
                <span><i class="fa-solid fa-chart-pie"></i> Dashboard </span>
            </a>
        </li>
        
        <li class="<?= in_array($page, ['imax', 'regular', 'velvet']) ? 'active' : ''; ?>">
            <div class="menu-trigger" onclick="toggleDropdown()">
                <span><i class="fa-solid fa-ticket"></i> Kategori Tiket</span>
                <i class="fa-solid fa-chevron-down arrow <?= in_array($page, ['imax', 'regular', 'velvet']) ? 'rotate' : ''; ?>" id="dropdown-arrow"></i>
            </div>
            
            <ul class="submenu <?= in_array($page, ['imax', 'regular', 'velvet']) ? 'open' : ''; ?>" id="tiket-submenu">
                <li class="<?= ($page == 'imax') ? 'active' : ''; ?>">
                    <a href="index.php?page=imax" style="font-family: 'Impact', sans-serif;">
                       🎬 1. Tiket IMAX 3D
                    </a>
                </li>
                <li class="<?= ($page == 'regular') ? 'active' : ''; ?>">
                    <a href="index.php?page=regular" style="font-family: 'Arial', sans-serif; font-style: italic;">
                       🎟️ 2. Tiket Regular
                    </a>
                </li>
                <li class="<?= ($page == 'velvet') ? 'active' : ''; ?>">
                    <a href="index.php?page=velvet" style="font-family: 'Georgia', serif; font-weight: bold;">
                       👑 3. Tiket Velvet Class
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>

<div class="main-content">
    
    <div class="top-breadcrumb">
        <div class="breadcrumb-path">
            <i class="fa-solid fa-house" style="font-size:12px;"></i> <a href="index.php">Home</a> &gt; <span>Dashboard</span> &gt; <strong style="color:#212529; text-transform:uppercase;"><?= $judul_halaman; ?></strong>
        </div>
    </div>

    <div class="cards-row">
        <div class="info-card blue">
            <div class="card-data">
                <span class="card-title">TOTAL DATA TIKET</span>
                <div class="card-value"><?= $total_tiket_count; ?> <span style="font-size:12px; font-weight:normal; color:#8e98a5;">pesanan</span></div>
            </div>
            <div class="card-icon-box"><i class="fa-solid fa-ticket" style="color:#3b7ddd;"></i></div>
        </div>

        <div class="info-card red">
            <div class="card-data">
                <span class="card-title">TOTAL OMSET / PENDAPATAN</span>
                <div class="card-value" style="color:#dc3545;">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></div>
            </div>
            <div class="card-icon-box"><i class="fa-solid fa-money-bill-wave" style="color:#dc3545;"></i></div>
        </div>

        <div class="info-card green">
            <div class="card-data">
                <span class="card-title">TOTAL KURSI OKUPASI</span>
                <div class="card-value" style="color:#28a745;"><?= $total_kursi_terjual; ?> <span style="font-size:12px; font-weight:normal; color:#8e98a5;">kursi</span></div>
            </div>
            <div class="card-icon-box"><i class="fa-solid fa-couch" style="color:#28a745;"></i></div>
        </div>

        <div class="info-card purple">
            <div class="card-data">
                <span class="card-title">RATIO SUITE PREMIUM</span>
                <div class="card-value" style="color:#833ab4;"><?= $porsi_velvet_count; ?> / <?= $total_tiket_count; ?></div>
            </div>
            <div class="card-icon-box"><i class="fa-solid fa-crown" style="color:#833ab4;"></i></div>
        </div>
    </div>

    <div class="filter-row">
        <div class="search-container">
            <input type="text" placeholder="Cari judul film atau ID transaksi di halaman ini...">
            <button class="btn-filter">Filter</button>
        </div>
        <div style="font-size:12px; color:#8e98a5; font-weight: 500;">
            <i class="fa-solid fa-circle-nodes"></i> Real-time Polymorphic Engine Active
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <i class="fa-solid fa-table-list" style="color:#6c757d;"></i> Ringkasan Rekapitulasi Inventaris Data Transaksi Bioskop
            </div>
            <div class="row-count-badge"><?= count($daftar_tiket); ?> Baris Ditemukan</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>ID TIKET & JUDUL FILM</th>
                    <th>JADWAL TAYANG</th>
                    <th>CONCRETE OBJECT CLASS</th>
                    <th>JUMLAH</th>
                    <th>HARGA DASAR</th>
                    <th>TOTAL TARIF BEBAN</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_tiket)): ?>
                    <tr><td colspan='8' style='text-align:center; padding:30px; color:#888;'>Tidak ada data pesanan tiket pada kategori ini.</td></tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($daftar_tiket as $tiket): ?>
                        <tr>
                            <td style="font-weight: 600; color: #6c757d;"><?= $no++; ?></td>
                            <td>
                                <strong style="font-size:14px; color:#212529; display:block;"><?= $tiket->getNamaFilm(); ?></strong>
                                <small style="color:#8e98a5; font-family: monospace; font-weight: bold;"><?= $tiket->getIdTiket(); ?></small>
                            </td>
                            <td><?= date('d M Y - H:i', strtotime($tiket->getJadwalTayang())); ?> WIB</td>
                            <td><span class="object-badge"><?= get_class($tiket); ?></span></td>
                            <td><?= $tiket->getJumlahKursi(); ?> Kursi</td>
                            <td>Rp <?= number_format($tiket->getHargaDasarTiket(), 0, ',', '.'); ?></td>
                            <td style="font-weight:700; color:#212529;">Rp <?= number_format($tiket->hitungTotalHarga(), 0, ',', '.'); ?></td>
                            <td><span class="status-active">ACTIVE</span></td>
                        </tr>
                        <tr style="background:#fafbfc;">
                            <td></td>
                            <td colspan="7" style="padding: 8px 20px; font-size:11.5px; color:#555; border-bottom: 1px solid #edf2f9;">
                                <i class="fa-solid fa-wand-magic-sparkles" style="color:#3b7ddd; margin-right:5px;"></i>
                                <strong>Fasilitas Khusus Studio :</strong> <em><?= $tiket->tampilkanInfoFasilitas(); ?></em>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
// Fungsi toggle dropdown sidebar menu agar mulus saat dibuka-tutup
function toggleDropdown() {
    const submenu = document.getElementById('tiket-submenu');
    const arrow = document.getElementById('dropdown-arrow');
    submenu.classList.toggle('open');
    arrow.classList.toggle('rotate');
}
</script>
</body>
</html>