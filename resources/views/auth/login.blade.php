<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body {
    background-color: #f4f6f9;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    position: relative;
    overflow: hidden;
}

/* BACKGROUND SHAPE */
.bg-shape {
    position: absolute;
    width: 320px;
    height: 320px;
    background: rgba(30,58,95,0.08);
    border-radius: 50%;
    top: -80px;
    right: -80px;
}

.bg-shape2 {
    position: absolute;
    width: 250px;
    height: 250px;
    background: rgba(30,58,95,0.05);
    border-radius: 50%;
    bottom: -60px;
    left: -60px;
}

/* CARD */
.login-card {
    position: relative;
    z-index: 1;
    background-color: #ffffff;
    border-radius: 25px;
    padding: 50px 55px;
    width: 420px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    transition: 0.3s ease;
    animation: fadeSlide 0.6s ease;
}

.login-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.12);
}

/* HEADER DECOR */
.card-header-decor {
    height: 6px;
    width: 100%;
    border-radius: 20px 20px 0 0;
    background: linear-gradient(90deg, #1e3a5f, #274b77);
    margin-bottom: 20px;
}

/* ICON */
.login-icon {
    font-size: 40px;
    background: #eef2f7;
    width: 70px;
    height: 70px;
    line-height: 70px;
    border-radius: 50%;
    margin: 0 auto 10px;
    box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
}

/* TITLE */
.login-card h2 {
    font-weight: 700;
    text-align: center;
    letter-spacing: 1px;
    color: #1e3a5f;
}

/* SUBTITLE */
.login-subtitle {
    text-align: center;
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 25px;
}

/* LABEL */
label {
    font-size: 12px;
    font-weight: 600;
    color: #1e3a5f;
    margin-bottom: 6px;
}

/* INPUT */
.form-control {
    border-radius: 12px;
    padding: 12px;
    border: 1px solid #dcdcdc;
    transition: 0.2s ease;
}

.form-control:focus {
    border-color: #1e3a5f;
    box-shadow: 0 0 0 0.15rem rgba(30,58,95,0.2);
}

/* BUTTON */
.btn-login {
    background: linear-gradient(135deg, #1e3a5f, #274b77);
    color: #ffffff;
    border-radius: 14px;
    padding: 12px 0;
    font-weight: 600;
    font-size: 16px;
    transition: 0.3s;
    border: none;
}

.btn-login:hover {
    opacity: 0.95;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* FOOTER */
.login-footer {
    text-align: center;
    font-size: 12px;
    color: #9aa3af;
    margin-top: 20px;
}

/* ANIMATION */
@keyframes fadeSlide {
    from {
        opacity: 0;
        transform: translateY(25px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

</style>
</head>
<body>

<!-- BACKGROUND -->
<div class="bg-shape"></div>
<div class="bg-shape2"></div>

<div class="login-card">

    <!-- HEADER LINE -->
    <div class="card-header-decor"></div>

    <!-- ICON -->
    <div class="login-icon">
        🔐
    </div>

    <h2>LOGIN</h2>

    <div class="login-subtitle">
        Masukkan username dan password anda
    </div>

        <form action="{{ route('login.proses') }}" method="POST">    
        @csrf

    <div class="mb-4">
        <label>USERNAME</label>
        <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-4">
        <label>PASSWORD</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-login">
            Login
        </button>
    </div>
</form>

    <!-- FOOTER -->
    <div class="login-footer">
        © 2026 Kost Bunda
    </div>

</div>

</body>
</html>