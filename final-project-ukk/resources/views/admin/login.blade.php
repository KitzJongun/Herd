<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | HELPDESK AI</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    :root {
        --primary: #2563eb;
        --secondary: #0f172a;
        --bg-color: #f1f5f9;
    }
    * { box-sizing: border-box; font-family: 'Inter', sans-serif; }
    html, body {
        width: 100%;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }
    body {
        background-color: var(--bg-color);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 20px 0;
    }
    .shape {
        position: absolute;
        filter: blur(80px);
        z-index: -1;
        border-radius: 50%;
        opacity: 0.5;
    }
    .shape-1 { top: -100px; left: -100px; width: 400px; height: 400px; background: rgba(37, 99, 235, 0.4); }
    .shape-2 { bottom: -100px; right: -100px; width: 400px; height: 400px; background: rgba(99, 102, 241, 0.3); }

    .login-container {
        width: 100%;
        max-width: 400px;
        padding: 20px;
        animation: fadeIn 0.8s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .login-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .login-header { text-align: center; margin-bottom: 30px; }
    .login-header h1 { margin: 0; font-size: 24px; color: var(--secondary); font-weight: 800; }
    .login-header p { color: #64748b; margin-top: 5px; font-size: 14px; }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: var(--secondary); }
    .input-wrapper { position: relative; }
    .input-wrapper i { position: absolute; left: 15px; top: 12px; color: #94a3b8; }
    .form-control {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: white;
        font-size: 15px;
        transition: all 0.3s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .btn-login {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 12px;
        background: var(--primary);
        color: white;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .btn-login:hover { background: #1d4ed8; transform: translateY(-1px); }
    .alert {
        padding: 12px;
        background: #fee2e2;
        color: #991b1b;
        border-radius: 10px;
        font-size: 13px;
        margin-bottom: 20px;
        text-align: center;
    }
    @media (max-width: 480px) {
        .login-card { padding: 30px 20px; }
    }
  </style>
</head>
<body class="hold-transition login-page">
  <div class="shape shape-1"></div>
  <div class="shape shape-2"></div>

  <div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1>Admin Panel</h1>
            <p>Silakan masuk ke akun Anda</p>
        </div>

        @if ($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="post">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="admin@example.com" required value="{{ old('email') }}">
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn-login">Masuk Sekarang</button>
        </form>
    </div>
  </div>
</body>
</html>
