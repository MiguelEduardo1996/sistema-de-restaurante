<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';

// Simulación de datos para las estadísticas
$pedidos_hoy = 15;
$pedidos_ayer = 12;
$clientes_hoy = 28;
$clientes_ayer = 25;
$ventas_mes = 12540.75;
$ganancias_hoy = 850.50;
$ganancias_ayer = 720.25;
$ganancia_promedio = 410.75;
$ganancia_mes_anterior = 405.50;

// Cálculo de porcentajes
$porcentaje_pedidos = $pedidos_ayer > 0 ? (($pedidos_hoy - $pedidos_ayer) / $pedidos_ayer) * 100 : 0;
$porcentaje_clientes = $clientes_ayer > 0 ? (($clientes_hoy - $clientes_ayer) / $clientes_ayer) * 100 : 0;
$porcentaje_ganancias = $ganancias_ayer > 0 ? (($ganancias_hoy - $ganancias_ayer) / $ganancias_ayer) * 100 : 0;
$porcentaje_promedio = $ganancia_mes_anterior > 0 ? (($ganancia_promedio - $ganancia_mes_anterior) / $ganancia_mes_anterior) * 100 : 0;

// Formatear números
$ventas_mes_formateado = number_format($ventas_mes, 2);
$ganancias_hoy_formateado = number_format($ganancias_hoy, 2);
$ganancia_promedio_formateado = number_format($ganancia_promedio, 2);

// Obtener información del primer pedido del día
$primer_pedido_hora = "11:02 a.m.";
$primer_pedido_fecha = date('l, d M.', strtotime('today'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Restaurante</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --success: #27ae60;
            --warning: #f39c12;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --border-radius: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', 'Roboto', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .dashboard-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 25px;
        }
        
        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .welcome-section h1 {
            font-size: 2.2rem;
            margin-bottom: 8px;
            font-weight: 600;
            position: relative;
        }
        
        .welcome-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border-left: 4px solid var(--secondary);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }
        
        .stat-card h3 {
            margin-bottom: 20px;
            color: var(--primary);
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .stat-card h3 i {
            color: var(--secondary);
        }
        
        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .stat-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .stat-info h4 {
            font-size: 0.95rem;
            color: var(--gray);
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .stat-change {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .positive {
            background-color: rgba(39, 174, 96, 0.15);
            color: var(--success);
        }
        
        .negative {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--danger);
        }
        
        /* Sales Chart */
        .sales-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            transition: var(--transition);
        }
        
        .sales-card:hover {
            box-shadow: var(--shadow-hover);
        }
        
        .sales-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .sales-title {
            font-size: 1.3rem;
            color: var(--primary);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sales-title i {
            color: var(--info);
        }
        
        .sales-amount {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            background: linear-gradient(135deg, var(--secondary), #2980b9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .chart-container {
            height: 180px;
            background: linear-gradient(to bottom, #f8f9fa, #ffffff);
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            padding: 15px 0;
            border: 1px solid var(--light-gray);
        }
        
        .chart-bars {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 100%;
        }
        
        .chart-bar {
            width: 35px;
            background: linear-gradient(to top, var(--secondary), #5dade2);
            border-radius: 5px 5px 0 0;
            position: relative;
            transition: var(--transition);
        }
        
        .chart-bar:hover {
            opacity: 0.8;
            transform: scaleY(1.05);
        }
        
        .chart-label {
            position: absolute;
            bottom: -25px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 0.8rem;
            color: var(--gray);
            font-weight: 500;
        }
        
        /* First Order Card */
        .first-order-card {
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            color: var(--dark);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
        }
        
        .first-order-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }
        
        .first-order-card h3 {
            margin-bottom: 15px;
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .first-order-time {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .first-order-date {
            font-size: 1rem;
            opacity: 0.8;
            font-weight: 500;
        }
        
        /* Modules Grid */
        .modules-section {
            margin-bottom: 40px;
        }
        
        .modules-section h2 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.6rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .modules-section h2 i {
            color: var(--secondary);
        }
        
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .module-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
            display: block;
            border-top: 4px solid var(--secondary);
        }
        
        .module-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
        }
        
        .module-icon {
            background: linear-gradient(135deg, var(--secondary), #2980b9);
            color: white;
            padding: 25px;
            font-size: 2.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: var(--transition);
        }
        
        .module-card:hover .module-icon {
            background: linear-gradient(135deg, #2980b9, var(--secondary));
        }
        
        .module-content {
            padding: 20px;
        }
        
        .module-card h3 {
            margin-bottom: 10px;
            color: var(--primary);
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .module-card p {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .modules-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 0 15px;
                margin: 20px auto;
            }
            
            .welcome-section {
                padding: 20px;
            }
            
            .welcome-section h1 {
                font-size: 1.8rem;
            }
            
            .modules-grid {
                grid-template-columns: 1fr;
            }
            
            .sales-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .stat-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .stat-change {
                align-self: flex-start;
            }
        }
        
        /* Animation for cards */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-card, .sales-card, .first-order-card, .module-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.2s; }
        .sales-card { animation-delay: 0.3s; }
        .module-card:nth-child(1) { animation-delay: 0.4s; }
        .module-card:nth-child(2) { animation-delay: 0.5s; }
        .module-card:nth-child(3) { animation-delay: 0.6s; }
        .module-card:nth-child(4) { animation-delay: 0.7s; }
    </style>
</head>
<body>
    <!-- El header se incluye desde includes/header.php -->
    
    <div class="dashboard-container">
        <div class="welcome-section">
            <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
            <p>Rol: <?php echo ucfirst($_SESSION['rol']); ?></p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3><i class="fas fa-chart-line"></i> Estadísticas de hoy</h3>
                <div class="stat-item">
                    <div class="stat-info">
                        <h4>Pedidos de hoy</h4>
                        <div class="stat-value"><?php echo $pedidos_hoy; ?></div>
                    </div>
                    <div class="stat-change <?php echo $porcentaje_pedidos >= 0 ? 'positive' : 'negative'; ?>">
                        <i class="fas <?php echo $porcentaje_pedidos >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i>
                        <?php echo ($porcentaje_pedidos >= 0 ? '+' : '') . number_format($porcentaje_pedidos, 1); ?>% Desde ayer
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-info">
                        <h4>Clientes de hoy</h4>
                        <div class="stat-value"><?php echo $clientes_hoy; ?></div>
                    </div>
                    <div class="stat-change <?php echo $porcentaje_clientes >= 0 ? 'positive' : 'negative'; ?>">
                        <i class="fas <?php echo $porcentaje_clientes >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i>
                        <?php echo ($porcentaje_clientes >= 0 ? '+' : '') . number_format($porcentaje_clientes, 1); ?>% Desde ayer
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-shopping-cart"></i> Pedidos de hoy</h3>
                <div class="stat-item">
                    <div class="stat-info">
                        <h4>Ganancias de hoy</h4>
                        <div class="stat-value">$<?php echo $ganancias_hoy_formateado; ?></div>
                    </div>
                    <div class="stat-change <?php echo $porcentaje_ganancias >= 0 ? 'positive' : 'negative'; ?>">
                        <i class="fas <?php echo $porcentaje_ganancias >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i>
                        <?php echo ($porcentaje_ganancias >= 0 ? '+' : '') . number_format($porcentaje_ganancias, 1); ?>% Desde ayer
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-info">
                        <h4>Ganancia diaria promedio (<?php echo date('F'); ?>)</h4>
                        <div class="stat-value">$<?php echo $ganancia_promedio_formateado; ?></div>
                    </div>
                    <div class="stat-change <?php echo $porcentaje_promedio >= 0 ? 'positive' : 'negative'; ?>">
                        <i class="fas <?php echo $porcentaje_promedio >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i>
                        <?php echo ($porcentaje_promedio >= 0 ? '+' : '') . number_format($porcentaje_promedio, 1); ?>% Desde el mes anterior
                    </div>
                </div>
            </div>
            
            <div class="first-order-card">
                <h3><i class="fas fa-clock"></i> Esperando el primer pedido del día</h3>
                <div class="first-order-time"><?php echo $primer_pedido_hora; ?></div>
                <div class="first-order-date"><?php echo $primer_pedido_fecha; ?></div>
            </div>
        </div>
        
        <div class="sales-card">
            <div class="sales-header">
                <div class="sales-title"><i class="fas fa-chart-bar"></i> Ventas de este mes</div>
                <div class="sales-amount">$<?php echo $ventas_mes_formateado; ?></div>
            </div>
            <div class="chart-container">
                <div class="chart-bars">
                    <!-- Barras del gráfico simuladas -->
                    <div class="chart-bar" style="height: 70%;"><div class="chart-label">Lun</div></div>
                    <div class="chart-bar" style="height: 85%;"><div class="chart-label">Mar</div></div>
                    <div class="chart-bar" style="height: 60%;"><div class="chart-label">Mié</div></div>
                    <div class="chart-bar" style="height: 90%;"><div class="chart-label">Jue</div></div>
                    <div class="chart-bar" style="height: 75%;"><div class="chart-label">Vie</div></div>
                    <div class="chart-bar" style="height: 95%;"><div class="chart-label">Sáb</div></div>
                    <div class="chart-bar" style="height: 80%;"><div class="chart-label">Dom</div></div>
                </div>
            </div>
        </div>
        
        <div class="modules-section">
            <h2><i class="fas fa-th-large"></i> Módulos del Sistema</h2>
            <div class="modules-grid">
                <?php if ($_SESSION['rol'] == 'admin'): ?>
                    <a href="modules/admin/index.php" class="module-card">
                        <div class="module-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div class="module-content">
                            <h3>Administración</h3>
                            <p>Gestión de usuarios, mesas y productos</p>
                        </div>
                    </a>
                <?php endif; ?>
                
                <?php if ($_SESSION['rol'] == 'mesero' || $_SESSION['rol'] == 'admin'): ?>
                    <a href="modules/mesero/index.php" class="module-card">
                        <div class="module-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <div class="module-content">
                            <h3>Mesero</h3>
                            <p>Gestión de comandas y pedidos</p>
                        </div>
                    </a>
                <?php endif; ?>
                
                <?php if ($_SESSION['rol'] == 'cocina' || $_SESSION['rol'] == 'admin'): ?>
                    <a href="modules/cocina/index.php" class="module-card">
                        <div class="module-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="module-content">
                            <h3>Cocina</h3>
                            <p>Ver y gestionar órdenes</p>
                        </div>
                    </a>
                <?php endif; ?>
                
                <?php if ($_SESSION['rol'] == 'caja' || $_SESSION['rol'] == 'admin'): ?>
                    <a href="modules/caja/index.php" class="module-card">
                        <div class="module-icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <div class="module-content">
                            <h3>Caja</h3>
                            <p>Gestión de ventas y pagos</p>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- El footer se incluye desde includes/footer.php -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>