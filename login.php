<?php
session_start();
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ? AND activo = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        
        header("Location: index.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Restaurante</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            padding: 20px;
        }
        
        .login-container {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .login-left {
            flex: 1;
            background: linear-gradient(120deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -70px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .login-left::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .logo {
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo i {
            font-size: 32px;
        }
        
        .logo h1 {
            font-size: 28px;
            font-weight: 700;
        }
        
        .welcome-text {
            margin-bottom: 30px;
        }
        
        .welcome-text h2 {
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .features {
            margin-top: 40px;
        }
        
        .feature {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .feature i {
            margin-right: 15px;
            font-size: 20px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .login-right {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-form {
            width: 100%;
            max-width: 350px;
            margin: 0 auto;
        }
        
        .form-header {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .form-header h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .form-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #444;
            font-weight: 500;
            font-size: 14px;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        
        .input-with-icon input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .input-with-icon input:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .remember {
            display: flex;
            align-items: center;
        }
        
        .remember input {
            margin-right: 8px;
        }
        
        .forgot-password {
            color: #4CAF50;
            text-decoration: none;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-login:hover {
            background-color: #45a049;
        }
        
        .alert.error {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #c62828;
            font-size: 14px;
        }
        
        .demo-info {
            margin-top: 25px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 6px;
            font-size: 13px;
            color: #666;
            border-left: 4px solid #4CAF50;
        }
        
        .demo-info p {
            margin-bottom: 5px;
        }
        
        .language-selector {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 13px;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 400px;
            }
            
            .login-left {
                padding: 30px;
            }
            
            .login-left::before, .login-left::after {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="language-selector">
        <i class="fas fa-globe"></i>
        <span>Español</span>
    </div>
    
    <div class="login-container">
        <div class="login-left">
            <div class="logo">
                <i class="fas fa-utensils"></i>
                <h1>RestPOS</h1>
            </div>
            
            <div class="welcome-text">
                <h2>Bienvenido al Sistema</h2>
                <p>Sistema de gestión para restaurante con todas las herramientas que necesitas para tu negocio.</p>
            </div>
            
            <div class="features">
                <div class="feature">
                    <i class="fas fa-calculator"></i>
                    <span>Gestión de pedidos y mesas</span>
                </div>
                <div class="feature">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes y análisis de ventas</span>
                </div>
                <div class="feature">
                    <i class="fas fa-box"></i>
                    <span>Control de inventario</span>
                </div>
            </div>
        </div>
        
        <div class="login-right">
            <form class="login-form" method="POST" action="">
                <div class="form-header">
                    <h2>Iniciar Sesión</h2>
                    <p>Ingresa tus credenciales para acceder al sistema</p>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="username">Usuario:</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" required placeholder="Ingresa tu usuario">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
                    </div>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Recuérdame</label>
                    </div>
                    <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>
                
                <button type="submit" class="btn-login">Iniciar sesión</button>
                
                <div class="demo-info">
                    <p><strong>Usuario demo:</strong> admin / password</p>
                    <p style="margin-top: 10px; font-size: 12px; color: #666;">
                        Sistema de gestión para restaurantes
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>