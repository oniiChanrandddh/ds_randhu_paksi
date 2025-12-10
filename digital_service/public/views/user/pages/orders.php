<?php
require_once __DIR__ . "/../../../../middleware/user_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['package_id']) || !is_numeric($_GET['package_id'])) {
    header("Location: " . $BASE_URL . "public/views/user/pages/services.php");
    exit;
}

$package = null;
$benefits = [];
$rawDesc = "";

$pid = (int) $_GET['package_id'];

$q = $conn->query("
    SELECT p.*, s.title AS service_name 
    FROM packages p
    JOIN services s ON p.service_id = s.id
    WHERE p.id = {$pid}
    LIMIT 1
");

if ($q && $q->num_rows > 0) {
    $package = $q->fetch_assoc();
    $rawDesc = $package["description"] ?? "";
    $benefits = array_filter(array_map("trim", preg_split("/\r\n|\r|\n/", $rawDesc)));
    $benefits = array_slice($benefits, 0, 5);
} else {
    header("Location: " . $BASE_URL . "public/views/user/pages/services.php");
    exit;
}

$userName  = $_SESSION["user_name"] ?? "";
$userEmail = $_SESSION["user_email"] ?? "";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Order Layanan - <?= htmlspecialchars($package["name"]) ?></title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/user/orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php include __DIR__ . "/../layout/navbar.php"; ?>

    <div class="order-page">

        <section class="order-hero">
            <div>
                <div class="hero-main-title">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Form Pemesanan Layanan Digital</span>
                </div>
                <p class="hero-subtitle">
                    Lengkapi detail pesananmu, pilih metode pembayaran, dan upload bukti transfer.
                    Tim kami akan memproses order-mu dan mengirimkan hasil melalui kontak yang kamu cantumkan.
                </p>
                <div class="hero-tags">
                    <span class="hero-tag">
                        <i class="fa-solid fa-layer-group"></i>
                        <?= htmlspecialchars($package["service_name"]) ?>
                    </span>
                    <span class="hero-tag">
                        <i class="fa-solid fa-box"></i>
                        Paket: <?= htmlspecialchars($package["name"]) ?>
                    </span>
                    <span class="hero-tag">
                        <i class="fa-solid fa-clock"></i>
                        Estimasi pengerjaan menyesuaikan brief
                    </span>
                </div>
                <div class="hero-highlight">
                    <span>Terakhir dicek sebelum dikumpulkan besok</span>
                </div>
            </div>

            <div class="hero-side-panel">
                <div class="hero-chip">
                    <i class="fa-solid fa-shield"></i>
                    Ringkasan Order
                </div>
                <div class="hero-side-title">
                    <?= htmlspecialchars($package["service_name"]) ?>
                </div>
                <div class="hero-price">
                    Rp <?= number_format($package["price"], 0, ',', '.') ?>
                    <small>/ paket</small>
                </div>
                <div class="hero-badges">
                    <span class="hero-badge">
                        <i class="fa-solid fa-pen-ruler"></i> Layanan profesional
                    </span>
                    <span class="hero-badge">
                        <i class="fa-solid fa-comments"></i> Komunikasi via email/WA
                    </span>
                </div>
                <div class="hero-cta">
                    <div class="hero-cta-text">
                        Setelah pembayaran terkonfirmasi, admin akan menghubungi kamu lewat email atau WhatsApp
                        untuk mengirimkan hasil dan update progres.
                    </div>
                    <div class="hero-cta-meta">
                        <i class="fa-solid fa-circle-check"></i>
                        Status pesanan akan tersimpan di sistem dan bisa dilihat kapan saja.
                    </div>
                </div>
            </div>
        </section>

        <section class="info-grid">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fa-solid fa-money-check-dollar"></i>
                </div>
                <div class="info-title">Metode Pembayaran</div>
                <div class="info-text">
                    Pilih metode pembayaran yang paling nyaman untuk kamu. Setelah transfer, upload bukti pembayaran
                    agar admin dapat memverifikasi pesananmu.
                </div>
                <div class="info-list">
                    <span><i class="fa-solid fa-circle"></i>BCA Virtual Account</span>
                    <span><i class="fa-solid fa-circle"></i>QRIS Semua E-Wallet</span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <div class="info-title">Konfirmasi & Hasil</div>
                <div class="info-text">
                    Hasil akhir dan progres pengerjaan akan dikirim melalui email yang kamu masukkan atau kontak lain
                    yang kamu tulis pada catatan pesanan.
                </div>
                <div class="info-list">
                    <span><i class="fa-solid fa-circle"></i>Notifikasi via email</span>
                    <span><i class="fa-solid fa-circle"></i>Opsional: WA follow-up</span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="info-title">Keamanan Data</div>
                <div class="info-text">
                    Data yang kamu kirim hanya digunakan untuk keperluan pengerjaan layanan dan tidak dibagikan ke pihak lain.
                </div>
                <div class="info-list">
                    <span><i class="fa-solid fa-circle"></i>Disimpan di sistem internal</span>
                    <span><i class="fa-solid fa-circle"></i>Dapat dihapus admin</span>
                </div>
            </div>
        </section>

        <section class="steps-section">
            <div class="steps-header">
                <div>
                    <div class="steps-title">Alur Pemesanan</div>
                    <div class="steps-subtitle">
                        Ikuti langkah singkat ini agar pesananmu diproses dengan lancar.
                    </div>
                </div>
            </div>
            <div class="steps-items">
                <div class="step-item">
                    <div class="step-label">Langkah 1</div>
                    <div class="step-title">Lengkapi form</div>
                    <div class="step-text">
                        Isi identitas, kontak, dan detail kebutuhanmu pada form yang tersedia.
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-label">Langkah 2</div>
                    <div class="step-title">Transfer & upload bukti</div>
                    <div class="step-text">
                        Lakukan pembayaran sesuai metode yang dipilih lalu upload bukti transfer.
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-label">Langkah 3</div>
                    <div class="step-title">Tunggu konfirmasi</div>
                    <div class="step-text">
                        Admin akan memeriksa data dan menghubungimu ketika pesanan mulai diproses atau selesai.
                    </div>
                </div>
            </div>
        </section>

        <section class="order-main">
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title">Ringkasan Paket</div>
                    <span class="summary-chip">Detail layanan</span>
                </div>

                <div class="summary-service">
                    Layanan: <?= htmlspecialchars($package["service_name"]) ?>
                </div>

                <div class="summary-pkg">
                    Paket: <?= htmlspecialchars($package["name"]) ?>
                </div>

                <div class="summary-price-row">
                    <span>Harga paket</span>
                    <span class="summary-price-value">
                        Rp <?= number_format($package["price"], 0, ',', '.') ?>
                    </span>
                </div>

                <div class="benefit-title">Apa saja yang kamu dapat?</div>
                <ul class="benefit-list">
                    <?php if (!empty($benefits)): ?>
                        <?php foreach ($benefits as $b): ?>
                            <li>
                                <i class="fa-solid fa-check"></i>
                                <span><?= htmlspecialchars($b) ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            <span>
                                <?= htmlspecialchars($rawDesc ?: "Paket layanan profesional yang siap membantu kebutuhan digital kamu.") ?>
                            </span>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="summary-note">
                    Jika ada kebutuhan khusus di luar paket, tuliskan secara detail pada bagian catatan agar admin
                    dapat menyesuaikan pengerjaan.
                </div>
            </div>

            <div class="form-card">
                <div class="form-title">Form Pemesanan</div>
                <div class="form-subtitle">
                    Pastikan data yang kamu isi sudah benar sebelum mengirim pesanan.
                </div>

                <form action="<?= $BASE_URL ?>public/views/user/actions/order_submit.php"
                      method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="package_id" value="<?= (int) $package["id"]; ?>">
                    <input type="hidden" name="service_id" value="<?= (int) $package["service_id"]; ?>">
                    <input type="hidden" name="price" value="<?= (int) $package["price"]; ?>">

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="customer_name"
                                   value="<?= htmlspecialchars($userName) ?>"
                                   placeholder="Nama kamu" required>
                        </div>

                        <div class="form-group">
                            <label>Email Aktif</label>
                            <input type="email" name="customer_email"
                                   value="<?= htmlspecialchars($userEmail) ?>"
                                   placeholder="emailkamu@example.com" required>
                            <small>Hasil dan konfirmasi akan dikirim ke email ini.</small>
                        </div>

                        <div class="form-group">
                            <label>Kontak WhatsApp</label>
                            <input type="text" name="customer_whatsapp" placeholder="Contoh: 08xxxxxxxxxx">
                            <small>Opsional, untuk follow-up lebih cepat.</small>
                        </div>

                        <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <select name="payment_method" required>
                                <option value="">Pilih metode pembayaran</option>

                                <option value="BANK_BCA">
                                    Bank BCA — 1234567890 a/n DigitalService ID
                                </option>
                                <option value="BANK_MANDIRI">
                                    Bank Mandiri — 9876543210 a/n DigitalService ID
                                </option>
                                <option value="BANK_BRI">
                                    Bank BRI — 1029384756 a/n DigitalService ID
                                </option>

                                <option value="EWALLET_DANA">
                                    DANA — 0812-3456-7890 a/n DigitalService
                                </option>
                                <option value="EWALLET_OVO">
                                    OVO — 0812-3456-7890 a/n DigitalService
                                </option>
                                <option value="EWALLET_GOPAY">
                                    GoPay — 0812-3456-7890 a/n DigitalService
                                </option>

                                <option value="QRIS">
                                    QRIS — Semua E-wallet & Bank | Scan pada halaman konfirmasi
                                </option>
                            </select>

                            <div class="payment-hint">
                                Silakan lakukan pembayaran ke metode yang telah dipilih.
                                Upload bukti pembayaran agar pesanan dapat diproses oleh admin.
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:8px;">
                        <label>Detail Kebutuhan</label>
                        <textarea name="notes"
                                  placeholder="Tuliskan brief atau kebutuhan yang ingin dikerjakan (misal: jenis desain, gaya warna, deadline, dll)"
                                  required></textarea>
                    </div>

                    <div class="form-group" style="margin-top:8px;">
                        <label>Upload Bukti Pembayaran</label>
                        <input type="file" name="payment_proof" accept="image/*" class="file-input" required>
                        <small>Format: JPG/PNG. Pastikan teks pada bukti transfer terlihat jelas.</small>
                    </div>

                    <div class="submit-row">
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-paper-plane"></i>
                            Kirim Pesanan
                        </button>
                        <div class="submit-note">
                            Dengan mengirim pesanan ini, kamu menyetujui bahwa data yang kamu berikan benar dan dapat
                            digunakan untuk proses pengerjaan layanan.
                        </div>
                    </div>

                </form>
            </div>
        </section>

        <section class="footer-note">
            Pesananmu akan disimpan di sistem beserta statusnya. Kamu dapat melihat kembali riwayat dan status
            order pada halaman akun atau menu pesanan.
        </section>

    </div>
</body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/digital_service/public/views/user/layout/footer.php"; ?>
</html>
