<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hubungi Kami | DigitalService</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/user/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<?php include __DIR__ . "/../layout/navbar.php"; ?>

<section class="hero-contact">
    <div class="container">
        <h1>Hubungi Tim DigitalService</h1>
        <p>Kami siap membantu mewujudkan kebutuhan digitalmu. Kirim pesan sekarang!</p>
    </div>
</section>

<section class="contact-info-grid">
    <div class="container info-cards">
        <div class="info-card">
            <i class="fa-solid fa-envelope"></i>
            <h4>Email</h4>
            <p>admin@digitalservice.com</p>
        </div>
        <div class="info-card">
            <i class="fa-solid fa-globe"></i>
            <h4>Website</h4>
            <p>www.digitalservice.com</p>
        </div>
        <div class="info-card">
            <i class="fa-solid fa-headset"></i>
            <h4>Jam Kerja</h4>
            <p>09:00 - 17:00 WIB</p>
        </div>
    </div>

    <section class="section-form">
        <div class="container">
            <div class="form-card">
                <h2>Kirim Pesan</h2>
                <p class="subtitle">Kami akan membalas melalui email yang kamu masukkan.</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form action="<?= $BASE_URL ?>public/views/user/actions/contact_create.php" method="POST">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Nama kamu di sini">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required placeholder="example@email.com">
                    </div>

                    <div class="form-group">
                        <label>Subjek</label>
                        <input type="text" name="subject" required placeholder="Apa yang ingin dibahas?">
                    </div>

                    <div class="form-group">
                        <label>Pesan</label>
                        <textarea name="message" rows="5" required placeholder="Tulis pesan kamu di sini..."></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>

        <section class="section-faq">
            <div class="container">
                <h2>Pertanyaan Umum</h2>
                <div class="faq-grid">
                    <div class="faq">
                        <h4><i class="fa-solid fa-circle-question"></i> Berapa lama responnya?</h4>
                        <p>Admin akan membalas email dalam 1–6 jam kerja.</p>
                    </div>
                    <div class="faq">
                        <h4><i class="fa-solid fa-shield-halved"></i> Apakah data saya aman?</h4>
                        <p>Kami menjaga kerahasiaan email dan pesan Anda sepenuhnya.</p>
                    </div>
                    <div class="faq">
                        <h4><i class="fa-solid fa-money-bill"></i> Apakah konsultasi berbayar?</h4>
                        <p>Konsultasi awal 100% gratis tanpa syarat apa pun.</p>
                    </div>
                    <div class="faq">
                        <h4><i class="fa-solid fa-envelope-open-text"></i> Apakah saya akan dapat balasan via email?</h4>
                        <p>Ya, admin akan membalas pesan melalui email Anda.</p>
                    </div>
                    <div class="faq">
                        <h4><i class="fa-solid fa-gears"></i> Apakah pesan saya akan dicek setiap hari?</h4>
                        <p>Ya, pesan dicek setiap hari kerja oleh tim kami.</p>
                    </div>
                    <div class="faq">
                        <h4><i class="fa-solid fa-comment-dots"></i> Bisa tanya dulu sebelum order?</h4>
                        <p>Tentu boleh! Kami siap bantu konsultasi sebelum memilih layanan.</p>
                    </div>
                </div>
            </div>
        </section>

    </section>
</section>

<section class="section-cta">
    <div class="container">
        <h2>Yuk Mulai Diskusi Bersama Kami!</h2>
        <p>Berikan peluang baru untuk bisnismu di dunia digital.</p>
        <a href="#top" class="btn-cta">
            <i class="fa-solid fa-comments"></i> Konsultasi Sekarang
        </a>
    </div>
</section>

</body>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/digital_service/public/views/user/layout/footer.php"; ?>
</html>
