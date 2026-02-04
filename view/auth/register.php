<?php
$title = 'Register';

// Handle register
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $auth->register();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Coffee Shop</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @keyframes authFadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes authSlideIn {
            from { opacity: 0; transform: translateX(-25px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes authSlideInRight {
            from { opacity: 0; transform: translateX(25px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 24px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(45,27,0,0.88) 0%, rgba(111,78,55,0.88) 100%),
                        url('<?php echo BASE_URL . 'assets/img/hero-coffee.jpg'; ?>') center/cover no-repeat;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 20%, rgba(212,165,116,0.3), transparent 45%),
                        radial-gradient(circle at 80% 80%, rgba(212,165,116,0.2), transparent 45%);
            pointer-events: none;
            animation: pulse 4s ease-in-out infinite;
        }
        
        .auth-box {
            background: #FFFFFF;
            border-radius: 24px;
            box-shadow: 0 32px 80px rgba(45, 27, 0, 0.22);
            border: 1px solid #EADBCB;
            width: 100%;
            max-width: 1020px;
            display: grid;
            grid-template-columns: 1fr 1.55fr;
            overflow: hidden;
            position: relative;
            z-index: 1;
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            animation: authFadeUp 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .auth-box:hover {
            transform: translateY(-6px);
            box-shadow: 0 40px 100px rgba(45, 27, 0, 0.28);
            border-color: #D4A574;
        }

        .auth-side {
            background: url('<?php echo BASE_URL . 'assets/img/hero-coffee.jpg'; ?>') center/cover no-repeat;
            color: #FFFFFF;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 18px;
            animation: authSlideIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            border-right: 1px solid rgba(255,255,255,0.15);
            position: relative;
            overflow: hidden;
        }

        .auth-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(45,27,0,0.8) 0%, rgba(111,78,55,0.8) 100%);
            transition: all 0.3s ease;
        }

        .auth-box:hover .auth-side::before {
            background: linear-gradient(135deg, rgba(45,27,0,0.75) 0%, rgba(111,78,55,0.75) 100%);
        }

        .auth-side > * {
            position: relative;
            z-index: 1;
        }

        .auth-side .logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.18);
            border: 1.5px solid rgba(255,255,255,0.4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .auth-side .logo:hover {
            transform: scale(1.08) rotate(5deg);
            background: rgba(255,255,255,0.22);
        }

        .auth-side h2 {
            font-size: 26px;
            font-weight: 900;
            margin: 0;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .auth-side p {
            margin: 0;
            color: rgba(255,255,255,0.92);
            line-height: 1.7;
            font-size: 14px;
            font-weight: 500;
        }

        .auth-side .badge {
            display: inline-block;
            padding: 8px 14px;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.4);
            color: #FFFFFF;
            border-radius: 999px;
            font-weight: 700;
            font-size: 11px;
            width: fit-content;
            transition: all 0.25s ease;
            letter-spacing: 0.5px;
        }

        .auth-side .badge:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .auth-form {
            padding: 42px 42px;
            background: linear-gradient(180deg, #FFFFFF 0%, #FFFBF8 100%);
            border-left: 1px solid rgba(241,230,220,0.8);
            animation: authSlideInRight 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s backwards;
        }
        
        .auth-header {
            text-align: left;
            margin-bottom: 24px;
            position: relative;
            padding-bottom: 12px;
        }

        .auth-header::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 56px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, #6F4E37 0%, #D4A574 100%);
        }
        
        .auth-header h1 {
            color: #2D1B00;
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }
        
        .auth-header p {
            color: #7A5A42;
            margin-top: 6px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2D1B00;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.2px;
        }

        .form-group label i {
            color: #6F4E37;
        }
        
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #E6DED5;
            border-radius: 12px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s, background 0.3s;
            background: #FFFDFB;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #B9A89A;
        }
        
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #C99A6B;
            box-shadow: 0 0 0 4px rgba(201, 154, 107, 0.18);
            background: #FFFFFF;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
                .form-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 12px;
                }

                .form-grid .form-group {
                    margin-bottom: 10px;
                }

                .form-grid .full {
                    grid-column: 1 / -1;
                }
        
        .form-row .form-group {
            margin-bottom: 0;
        }
        
        .btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            margin-top: 15px;
            letter-spacing: 0.2px;
            box-shadow: 0 10px 22px rgba(111, 78, 55, 0.18);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(111, 78, 55, 0.25);
            background: linear-gradient(135deg, #7A5A42 0%, #9B7A52 100%);
        }
        
        .auth-footer {
            text-align: left;
            margin-top: 20px;
        }
        
        .auth-footer p {
            color: #7A5A42;
            margin: 0;
            font-size: 13px;
        }

        @media (max-width: 900px) {
            .auth-box {
                grid-template-columns: 1fr;
                max-width: 520px;
            }

            .auth-side {
                text-align: center;
                align-items: center;
                padding: 28px;
            }

            .auth-form {
                padding: 28px;
                border-left: none;
                border-top: 1px solid #F1E6DC;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .auth-header,
            .auth-footer {
                text-align: center;
            }
        }
        
        .auth-footer a {
            color: #6F4E37;
            text-decoration: none;
            font-weight: 700;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 10px;
            border-left: 4px solid #ddd;
        }
        
        .alert-danger {
            background: #fff1f1;
            border-left-color: #ef4444;
            color: #b91c1c;
        }
        
        .alert-success {
            background: #f0fdf4;
            border-left-color: #22c55e;
            color: #15803d;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-side">
                <div class="logo">☕</div>
                <h2>Mulai Perjalanan Kopimu</h2>
                <p>Daftar untuk akses koleksi kopi premium, promo eksklusif, dan layanan prioritas.</p>
                <span class="badge">Eksklusif Member</span>
            </div>
            <div class="auth-form">
                <div class="auth-header">
                    <h1>Register</h1>
                    <p>Buat akun baru</p>
                </div>
            
            <?php 
            $alert = getAlert();
            if ($alert) {
                echo '<div class="alert alert-' . htmlspecialchars($alert['type']) . '">';
                echo htmlspecialchars($alert['message']);
                echo '</div>';
            }
            ?>
            
                <form method="POST" action="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nama"><i class="fas fa-user"></i> Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" required placeholder="Nama lengkap">
                        </div>
                        
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" id="email" name="email" required placeholder="nama@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="password"><i class="fas fa-lock"></i> Password</label>
                            <input type="password" id="password" name="password" required placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm"><i class="fas fa-lock"></i> Konfirmasi Password</label>
                            <input type="password" id="password_confirm" name="password_confirm" required placeholder="Ulangi password">
                        </div>
                        
                        <div class="form-group">
                            <label for="no_telepon"><i class="fas fa-phone"></i> No Telepon</label>
                            <input type="tel" id="no_telepon" name="no_telepon" placeholder="08xxxxxxxxxx">
                        </div>
                        
                        <div class="form-group full">
                            <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap"></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn">Daftar</button>
                </form>
                
                <div class="auth-footer">
                    <p>Sudah punya akun? <a href="<?php echo BASE_URL . 'index.php?page=login'; ?>">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
