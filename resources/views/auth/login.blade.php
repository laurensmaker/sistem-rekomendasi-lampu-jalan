<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Login Portal - Dinas PUPR</title>
    <!-- Bootstrap 5 CSS + Icons + Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 (free icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts: Poppins & Inter for clean typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0B2B5E 0%, #1A4A8F 50%, #2B6CB0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* decorative abstract shapes */
        body::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            top: -100px;
            right: -80px;
            z-index: 0;
        }

        body::after {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            bottom: -200px;
            left: -150px;
            z-index: 0;
        }

        /* main card container */
        .login-wrapper {
            width: 60%;
            max-width: 1300px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* modern card with blur and shadow */
        .glass-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(0px);
            border-radius: 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.35), 0 1px 2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* left side brand area */
        .brand-panel {
            background: linear-gradient(145deg, #0A2F44 0%, #0B3B5E 100%);
            padding: 2.5rem 2rem;
            height: 100%;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::after {
            content: "🏛️";
            font-size: 140px;
            opacity: 0.08;
            position: absolute;
            bottom: -20px;
            right: -20px;
            pointer-events: none;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.8rem;
            flex-wrap: wrap;
        }

        .logo-img {
            width: 70px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
            background: white;
            padding: 6px;
            border-radius: 18px;
        }

        .institution-name {
            font-weight: 700;
            font-size: 1.4rem;
            line-height: 1.3;
            letter-spacing: -0.2px;
        }

        .institution-name small {
            font-size: 0.8rem;
            font-weight: 400;
            display: block;
            opacity: 0.85;
            margin-top: 5px;
        }

        .hero-text {
            margin-top: 2rem;
        }

        .hero-text h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .hero-text p {
            font-size: 0.95rem;
            opacity: 0.85;
            line-height: 1.5;
            margin-bottom: 1.8rem;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            margin-bottom: 0.9rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
        }

        .feature-list li i {
            width: 28px;
            font-size: 1.2rem;
            color: #FFD966;
        }

        /* right side form */
        .form-panel {
            padding: 2.5rem 2rem;
            background: white;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header .badge-admin {
            background: #EFF6FF;
            color: #FFD966;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 1rem;
            border-radius: 30px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .login-header h4 {
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            color: #0B2B5E;
            letter-spacing: -0.3px;
        }

        .login-header p {
            color: #5A6E85;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .input-group-custom {
            margin-bottom: 1.5rem;
        }

        .input-group-custom label {
            font-weight: 500;
            font-size: 0.85rem;
            color: #1E2F44;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .input-group-custom label i {
            color: #2B6CB0;
            font-size: 0.9rem;
        }

        .input-group-custom input {
            width: 100%;
            padding: 0.85rem 1rem;
            border-radius: 1rem;
            border: 1.5px solid #E2E8F0;
            background: #FCFDFE;
            font-size: 0.95rem;
            transition: all 0.2s;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .input-group-custom input:focus {
            border-color: #2B6CB0;
            box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.15);
            background: white;
        }

        .btn-login {
            background: linear-gradient(95deg, #0B2B5E 0%, #1E5A9B 100%);
            border: none;
            padding: 0.85rem;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 2rem;
            color: white;
            width: 100%;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 6px 14px rgba(11, 43, 94, 0.25);
        }

        .btn-login:hover {
            background: linear-gradient(95deg, #082043 0%, #154a7c 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 18px -6px rgba(11, 43, 94, 0.4);
        }

        .btn-login i {
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .btn-login:hover i {
            transform: translateX(4px);
        }

        /* footer links */
        .form-footer {
            text-align: center;
            margin-top: 1.8rem;
            font-size: 0.75rem;
            color: #6c7a91;
        }

        /* responsive tweaks */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
                align-items: center;
            }
            .brand-panel {
                text-align: center;
                padding: 2rem 1.5rem;
            }
            .logo-area {
                justify-content: center;
            }
            .feature-list li {
                justify-content: center;
            }
            .hero-text h3 {
                font-size: 1.5rem;
            }
            .form-panel {
                padding: 1.8rem;
            }
            .glass-card {
                border-radius: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .institution-name {
                font-size: 1.1rem;
            }
            .hero-text h3 {
                font-size: 1.3rem;
            }
        }

        /* subtle animation */
        .glass-card {
            animation: fadeSlideUp 0.5s ease-out;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* custom placeholder style */
        ::placeholder {
            color: #B9C3D4;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="row g-0 glass-card">
        <!-- Left side: Institution branding & description -->
      

        <!-- Right side: Login Form -->
        <div class="col-lg-12 col-md-12 form-panel">
            <div class="login-header">
                {{-- <span class="badge-admin"><i class="fas fa-lock me-1"></i> Area Terbatas</span> --}}
                <h4>LOGIN </h4>
                <p>Masukkan Username dan Password Anda untuk Masuk Ke Dasbor</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @if ($errors->has('username') || $errors->has('password'))
                    <div class="alert alert-danger">
                        {{ $errors->first('username') }}
                        {{ $errors->first('password') }}
                    </div>
                @endif
                @csrf
                <div class="input-group-custom">
                    <label for="email"><i class="fas fa-user-circle"></i> Email</label>
                    <input type="email" id="email" name="email" placeholder="admin@gmail.com" required autofocus>
                </div>
                <div class="input-group-custom">
                    <label for="password"><i class="fas fa-key"></i> Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-login">
                    <span>LOGIN</span> <i class="fas fa-arrow-right"></i>
                </button>
                {{-- <div class="form-footer">
                    <i class="fas fa-shield-alt me-1"></i> Sistem aman & terotentikasi dua faktor (2FA)
                </div> --}}
            </form>
            <div class="text-center mt-4">
                <small class="text-muted">© {{ date('Y') }} Fikom</small>
            </div>
        </div>
    </div>
</div>

<!-- Optional Bootstrap JS if needed for interactions, but not mandatory -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- small script for better placeholder + simple onload effect (cosmetic) -->
<script>
    (function() {
        // just to make sure labels & inputs have better interaction (optional)
        const inputs = document.querySelectorAll('.input-group-custom input');
        inputs.forEach(input => {
            input.addEventListener('focus', (e) => {
                e.target.closest('.input-group-custom')?.classList.add('focused');
            });
            input.addEventListener('blur', (e) => {
                e.target.closest('.input-group-custom')?.classList.remove('focused');
            });
        });
        // Add a subtle console greeting (for dev)
        console.log("✨ Portal Login Dinas PUPR - Desain Modern & Aman");
    })();
</script>
</body>
</html>