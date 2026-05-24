<?php
session_start();
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == 'admin' && $password == 'admin') {
        $_SESSION['user'] = 'Alif';
        header("Location: index.php?page=beranda");
        exit;
    } else {
        $error = "Username atau Password Salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Royal Luxury Pharmacy</title>
    <style>
        /* LIGHT ROYAL LUXURY & PEARL WHITE THEME */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f7f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        
        .login-container { 
            background: #ffffff; 
            border: 1px solid #e1e8e5; 
            border-top: 5px solid #dfb76c; 
            padding: 40px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            width: 100%;
            max-width: 400px; 
            border-radius: 12px;
            text-align: center;
        }
        
        /* Brand Header Deep Emerald & Gold */
        .brand-header {
            background: linear-gradient(135deg, #0d2c20 0%, #061711 100%);
            padding: 20px;
            margin: -40px -40px 30px -40px;
            border-top-left-radius: 7px;
            border-top-right-radius: 7px;
            border-bottom: 3px solid #dfb76c;
        }
        .brand-header h2 { 
            color: #dfb76c; 
            font-size: 20px;
            letter-spacing: 2px; 
            text-transform: uppercase; 
            font-weight: 600;
        }
        .brand-header p {
            color: #a3b8ae;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
        }
        
        /* Form Groups */
        .form-group { margin-bottom: 22px; text-align: left; }
        .form-group label { 
            display: block; 
            margin-bottom: 8px; 
            font-size: 12px; 
            font-weight: 700;
            color: #0d2c20; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-group input { 
            width: 100%; 
            padding: 12px; 
            background: #f8faf9; 
            border: 1px solid #ced6d0; 
            color: #2c3e50; 
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px; 
            transition: all 0.2s ease;
        }
        .form-group input:focus { 
            outline: none; 
            border-color: #dfb76c; 
            background: #ffffff;
            box-shadow: 0 0 8px rgba(223,183,108,0.25); 
        }
        
        /* Luxury Gold Button */
        button { 
            width: 100%; 
            padding: 12px; 
            background: linear-gradient(135deg, #0d2c20 0%, #061711 100%); 
            color: #dfb76c;
            border: none; 
            font-size: 13px;
            font-weight: 700; 
            cursor: pointer; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(13,44,32,0.15);
            transition: all 0.2s ease;
        }
        button:hover { 
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(13,44,32,0.25);
            background: linear-gradient(135deg, #143f2e 0%, #0a241b 100%);
        }
        
        .error { 
            background: #ffe0e0;
            color: #cc3333; 
            font-size: 13px; 
            font-weight: 600;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #ffb3b3;
        }
        .footer-text {
            margin-top: 25px;
            font-size: 11px;
            color: #747d8c;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="brand-header">
            <h2>THE ROYAL PHARMACY</h2>
            <p>Authentication Node v2004</p>
        </div>
        
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>User Node</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label>Security Key</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login">Initialize System</button>
        </form>
        
        <div class="footer-text">
            Secure Gateway Active on Port :2004
        </div>
    </div>

</body>
</html>