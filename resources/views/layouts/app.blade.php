<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kost Bunda')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <style>
    /* =========================
       GLOBAL
    ========================= */
    body {
        background-color: #F8FAFC;
        font-family: 'Segoe UI', Tahoma, sans-serif;
        color: #0F172A;
    }

    .container {
        max-width: 1200px;
    }

    /* =========================
       NAVBAR
    ========================= */
    .navbar {
        background-color: #274c77 !important;
        padding: 14px 0;
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 22px;
        color: #ffffff !important;
    }

  .nav-link {
    position: relative;
    color: #ffffff !important; /* teks tetap putih */
    font-weight: 600; /* semi-bold */
    letter-spacing: 0.5px; 
    padding: 8px 14px;
}

.nav-link:hover {
    color: #ffffff !important; /* cegah berubah jadi hitam saat hover */
}

.nav-link::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: -6px;
    width: 90%;
    height: 3px;
    background: linear-gradient(90deg, #4f8fcf, #8ec5ff);
    border-radius: 2px;
    transform: translateX(-50%) scaleX(0);
    transform-origin: center;
    transition: transform 0.25s ease;
}

.nav-link:hover::after,
.nav-link.active::after {
    transform: translateX(-50%) scaleX(1);
}


    /*log out*/
  .btn-logout {
    background: linear-gradient(135deg, #3a5a80, #6c8fb3);
    color: white;
    border-radius: 12px;
    padding: 8px 18px;
    font-weight: 500;
    border: none;
    transition: 0.3s;
}

.btn-logout:hover {
    opacity: 0.9;
}



    /* ================= BUTTON GLOBAL STYLE ================= */
.btn-premium {
    border: none;
    border-radius: 12px;
    padding: 8px 18px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

/* Hover Effect */
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.15);
}

/* Primary Navy */
.btn-navy {
    background: linear-gradient(135deg, #1e3a5f, #2c5282);
    color: #fff;
}

/* Success Green */
.btn-green {
    background: linear-gradient(135deg, #2e8b57, #3cb371);
    color: #fff;
}

/* Danger Soft Red */
.btn-danger-soft {
    background: linear-gradient(135deg, #dc3545, #ff6b6b);
    color: #fff;
}


    /* =========================
       STAT CARD (DASHBOARD)
    ========================= */
    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        transition: 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
    }

    .stat-card small {
        color: #64748B;
        font-weight: 600;
        text-transform: uppercase;
    }

    .stat-card h5 {
        font-weight: 700;
        margin-top: 6px;
    }

    /* Icon Colors */
    .icon-blue { color: #3B82F6; }
    .icon-red { color: #EF4444; }
    .icon-green { color: #22C55E; }
    .icon-orange { color: #F59E0B; }
    
    /* Navy Gradient Elegant */
.btn-navy-gradient {
    background: linear-gradient(135deg, #1e3a5f, #274c77);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(30,58,95,0.15);
}

.btn-navy-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(30,58,95,0.25);
    color: #fff;
}



    /* =========================
       CARD UMUM
    ========================= */
    .card {
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        border: none;
    }

    /* =========================
       TABLE
    ========================= */
    .table-hover tbody tr:hover {
        background-color: #F1F5F9;
    }

    /* =========================
       FOOTER
    ========================= */
    .footer-top {
        background-color: #274c77;
        color: #ffffff;
        padding: 30px 60px;
        text-align: center;
        font-size: 15px;
    }

    .footer-middle {
        background-color: #F1F5F9;
        padding: 40px 60px;
    }

    .footer {
        background-color: #274c77;
        color: #ffffff;
        text-align: center;
        padding: 12px;
        font-size: 13px;
    }

    .contact-title {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .contact-item {
        margin-bottom: 8px;
    }

    .social-icons i {
        font-size: 20px;
        margin-right: 10px;
        cursor: pointer;
    }

    .logo-box img {
        width: 130px;
        border-radius: 50%;
    }

    @media(max-width:768px){
        .footer-top,
        .footer-middle{
            padding: 25px;
        }
    }
    </style>
</head>
<body>
    <body class="d-flex flex-column min-vh-100">

<!-- =========================
     NAVBAR
========================= -->
<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="#">
            Kost Bunda
        </a>

        <ul class="navbar-nav ms-4">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penghuni.*') ? 'active' : '' }}"
                   href="{{ route('penghuni.index') }}">
                    Data Penghuni
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pemasukan.*') ? 'active' : '' }}"
                   href="{{ route('pemasukan.index') }}">
                    Pemasukan
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}"
                   href="{{ route('pengeluaran.index') }}">
                    Pengeluaran
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}"
                   href="{{ route('laporan.index') }}">
                    Laporan
                </a>
            </li>

        </ul>

        <a href="/logout" class="btn btn-logout ms-auto">
            <i class="bi bi-box-arrow-right me-2"></i> LOG OUT
        </a>

    </div>
</nav>

<!-- =========================
     CONTENT
========================= -->
<main class="flex-fill">
    <div class="container my-4">
        @yield('content')
    </div>
</main>

<!-- =========================
     FOOTER
========================= -->

<div class="footer-top">
    Kost Bunda adalah hunian nyaman yang dirancang untuk memberikan rasa aman dan tenang 
    seperti di rumah sendiri. Dengan fasilitas lengkap, lingkungan bersih, serta lokasi strategis,
    Kost Bunda menjadi pilihan ideal bagi pelajar, mahasiswa, maupun karyawan.
</div>

<div class="footer-middle">
    <div class="row align-items-center">

        <div class="col-md-6 logo-box text-center">
            <img src="{{ asset('foto_user/logo_baru.png') }}" alt="Logo Kost Bunda">
        </div>

        <div class="col-md-6">
            <div class="contact-title">HUBUNGI KAMI</div>

            <div class="contact-item">
                <i class="fa-regular fa-envelope"></i>
                kostbunda@gmail.com
            </div>

            <div class="contact-item">
                <i class="fa-brands fa-whatsapp"></i>
                +62 858-8858-5225
            </div>

            <div class="social-icons mt-2">
                <i class="fa-brands fa-facebook"></i>
                <i class="fa-brands fa-instagram"></i>
            </div>
        </div>

    </div>
</div>

<div class="footer">
    © Copyright <u>Kost Bunda</u>, 2026. All Right Reserved.
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
