<?php
$level = (int)($_SESSION['level'] ?? 0);
$namaUser = htmlspecialchars($_SESSION['nama'] ?? 'User', ENT_QUOTES, 'UTF-8');
?>
<style>
    .top-navbar {
        background: linear-gradient(180deg, #00529b 0%, #00386b 100%);
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 24px;
        height: 48px;
        font-family: "Segoe UI", Arial, sans-serif;
        font-size: 14px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        position: relative;
        z-index: 100;
    }
    .nav-left, .nav-right {
        display: flex;
        align-items: center;
        height: 100%;
    }
    .brand-title {
        font-weight: 700;
        font-size: 16px;
        color: #ffffff;
        text-decoration: none;
        margin-right: 24px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .nav-link, .dropdown-toggle {
        color: #e6f0fa;
        text-decoration: none;
        padding: 0 14px;
        height: 100%;
        display: flex;
        align-items: center;
        background: transparent;
        border: none;
        font-family: inherit;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.15s ease, color 0.15s ease;
    }
    .nav-link:hover, .dropdown-toggle:hover, .dropdown.open .dropdown-toggle {
        background: rgba(255, 255, 255, 0.14);
        color: #ffffff;
    }
    .dropdown {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
    }
    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 185px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 0 0 6px 6px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        padding: 6px 0;
        z-index: 999;
    }
    .nav-right .dropdown-menu {
        left: auto;
        right: 0;
        min-width: 150px;
    }
    .dropdown.open .dropdown-menu {
        display: block;
    }
    .dropdown-item {
        display: block;
        padding: 9px 16px;
        color: #1e293b;
        text-decoration: none;
        font-size: 13.5px;
    }
    .dropdown-item:hover {
        background: #eff6ff;
        color: #004584;
    }
    .dropdown-item.logout-item {
        color: #b91c1c;
        font-weight: 600;
    }
    .dropdown-item.logout-item:hover {
        background: #fef2f2;
        color: #991b1b;
    }
</style>

<nav class="top-navbar">
    <div class="nav-left">
        <a href="index.php" class="brand-title">
            <span>&#9776;</span>
            <span>Sistem Penjualan</span>
        </a>

        <a href="index.php" class="nav-link">Home</a>

        <?php if ($level === 1): ?>
            <div class="dropdown" id="masterDropdown">
                <button type="button" class="dropdown-toggle" onclick="toggleDropdown(event, 'masterDropdown')">
                    Data Master &#9662;
                </button>
                <div class="dropdown-menu">
                    <a href="barang.php" class="dropdown-item">Data Barang</a>
                    <a href="supplier.php" class="dropdown-item">Data Supplier</a>
                    <a href="pelanggan.php" class="dropdown-item">Data Pelanggan</a>
                    <a href="user.php" class="dropdown-item">Data User</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($level === 1 || $level === 2): ?>
            <a href="transaksi.php" class="nav-link">Transaksi</a>
            <a href="laporan.php" class="nav-link">Laporan</a>
        <?php endif; ?>
    </div>

    <div class="nav-right">
        <div class="dropdown" id="userDropdown">
            <button type="button" class="dropdown-toggle" onclick="toggleDropdown(event, 'userDropdown')">
                <?= $namaUser ?> &#9662;
            </button>
            <div class="dropdown-menu">
                <a href="logout.php" class="dropdown-item logout-item">Logout</a>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleDropdown(event, id) {
        event.stopPropagation();
        document.querySelectorAll('.dropdown').forEach(function (el) {
            if (el.id !== id) {
                el.classList.remove('open');
            }
        });
        var target = document.getElementById(id);
        if (target) {
            target.classList.toggle('open');
        }
    }

    document.addEventListener('click', function () {
        document.querySelectorAll('.dropdown').forEach(function (el) {
            el.classList.remove('open');
        });
    });
</script>
