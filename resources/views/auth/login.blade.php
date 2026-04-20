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

.login-card {
    position: relative;
    z-index: 1;
    background-color: #ffffff;
    border-radius: 25px;
    padding: 50px 55px;
    width: 420px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
}

.card-header-decor {
    height: 6px;
    width: 100%;
    border-radius: 20px 20px 0 0;
    background: linear-gradient(90deg, #1e3a5f, #274b77);
    margin-bottom: 20px;
}

.login-icon {
    font-size: 40px;
    background: #eef2f7;
    width: 70px;
    height: 70px;
    line-height: 70px;
    border-radius: 50%;
    margin: 0 auto 10px;
}

.login-card h2 {
    font-weight: 700;
    text-align: center;
    color: #1e3a5f;
}

.login-subtitle {
    text-align: center;
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 25px;
}

label {
    font-size: 12px;
    font-weight: 600;
    color: #1e3a5f;
}

.form-control {
    border-radius: 12px;
    padding: 12px;
}

.btn-login {
    background: linear-gradient(135deg, #1e3a5f, #274b77);
    color: #ffffff;
    border-radius: 14px;
    padding: 12px 0;
    font-weight: 600;
    border: none;
}

.login-footer {
    text-align: center;
    font-size: 12px;
    color: #9aa3af;
    margin-top: 20px;
}
</style>
</head>

<body>

<div class="bg-shape"></div>
<div class="bg-shape2"></div>

<div class="login-card">

    <div class="card-header-decor"></div>

    <div class="login-icon">🔐</div>

    <h2>LOGIN</h2>

    <div class="login-subtitle">
        Masukkan username dan password anda
    </div>

    <!-- 🔥 FIX ACTION + CSRF -->
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

        <!-- ERROR -->
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <!-- SUCCESS -->
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="d-grid">
            <button type="submit" class="btn btn-login">
                Login
            </button>
        </div>

    </form>

    <div class="login-footer">
        © 2026 Kost Bunda
    </div>

</div>

</body>
</html>