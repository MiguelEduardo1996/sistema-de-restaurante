<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Incluir configuración de rutas - CORREGIDO el path
require_once __DIR__ . '../config/paths.php'; // <- FIX: Agregué el slash que faltaba

// Simulación de datos
$total_pedidos = 521;
$total_ganancias = 12540.75;
$pedidos_entregados = 257;

$pedidos_recientes = [
    ['cliente' => 'Herberio Mone', 'orden' => '23H-103', 'monto' => 430.00, 'estado' => 'Entregado'],
    ['cliente' => 'Rosay Bollopas', 'orden' => '23H-104', 'monto' => 3431.00, 'estado' => 'En proceso'],
    ['cliente' => 'Carlos Rodríguez', 'orden' => '23H-105', 'monto' => 1250.00, 'estado' => 'Finalizado'],
    ['cliente' => 'María González', 'orden' => '23H-106', 'monto' => 890.00, 'estado' => 'Entregado']
];

// Incluir header
include 'includes/header.php';
?>

<style>
    /* Solo estilos específicos del dashboard */
    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px; /* Padding para móviles */
    }
    
    .welcome-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: var(--cream);
        border-radius: var(--border-radius);
        padding: 30px 20px; /* Padding reducido para móviles */
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }
    
    .welcome-section::before {
        content: '';
        position: absolute;
        top: -30px; /* Ajustado para móviles */
        right: -30px;
        width: 100px; /* Más pequeño para móviles */
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
    }
    
    .welcome-section h1 {
        font-size: 1.8rem; /* Tamaño reducido para móviles */
        margin-bottom: 10px;
        font-weight: 600;
        line-height: 1.3;
    }
    
    .welcome-section p {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 400;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: var(--cream);
        border-radius: var(--border-radius);
        padding: 20px;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 1px solid var(--light-gray);
        text-align: center;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-hover);
        border-color: var(--primary);
    }
    
    .stat-card h3 {
        color: var(--gray);
        font-size: 0.85rem;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .stat-value {
        font-size: 2rem; /* Reducido para móviles */
        font-weight: 700;
        color: var(--primary-dark);
        margin: 12px 0;
    }
    
    .stat-change {
        color: var(--primary);
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.8rem;
    }
    
    .orders-card {
        background: var(--cream);
        border-radius: var(--border-radius);
        padding: 25px 20px;
        box-shadow: var(--shadow);
        margin-bottom: 30px;
        border: 1px solid var(--light-gray);
        overflow-x: auto; /* Scroll horizontal para tablas en móviles */
    }
    
    .orders-card h2 {
        color: var(--dark);
        font-size: 1.3rem;
        margin-bottom: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        min-width: 600px; /* Ancho mínimo para scroll en móviles */
    }
    
    .orders-table th {
        text-align: left;
        padding: 12px 15px;
        border-bottom: 2px solid var(--light-gray);
        color: var(--primary-dark);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        white-space: nowrap;
    }
    
    .orders-table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--light-gray);
        color: var(--dark);
        font-weight: 400;
        white-space: nowrap;
    }
    
    .orders-table tr:hover {
        background: rgba(76, 175, 80, 0.05);
    }
    
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }
    
    .status-entregado { 
        background: rgba(76, 175, 80, 0.1); 
        color: var(--primary-dark);
    }
    
    .status-en-proceso { 
        background: rgba(255, 193, 7, 0.1); 
        color: #ff9800;
    }
    
    .status-finalizado { 
        background: rgba(33, 150, 243, 0.1); 
        color: #1976d2;
    }
    
    /* MOBILE FIRST - Responsive para móviles */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 0 10px;
        }
        
        .welcome-section {
            padding: 20px 15px;
            margin-bottom: 20px;
        }
        
        .welcome-section h1 {
            font-size: 1.5rem;
        }
        
        .welcome-section p {
            font-size: 0.9rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            padding: 15px;
        }
        
        .stat-value {
            font-size: 1.8rem;
        }
        
        .orders-card {
            padding: 15px 10px;
            margin-bottom: 20px;
        }
        
        .orders-card h2 {
            font-size: 1.2rem;
        }
        
        .orders-table {
            font-size: 0.8rem;
        }
        
        .orders-table th,
        .orders-table td {
            padding: 8px 10px;
        }
    }
    
    @media (max-width: 480px) {
        .welcome-section h1 {
            font-size: 1.3rem;
        }
        
        .stat-value {
            font-size: 1.6rem;
        }
        
        .status-badge {
            font-size: 0.7rem;
            padding: 4px 8px;
            min-width: 70px;
        }
    }
    
    /* Estilos para cuando el menú está abierto en móviles */
    @media (max-width: 768px) {
        .main-content.expanded {
            position: relative;
        }
        
        .main-content.expanded::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1>Bienvenido al Sistema de Restaurante</h1>
        <p>Hola, <?php echo $_SESSION['nombre']; ?> - ¿Qué hay de nuevo contigo?</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total de Pedidos</h3>
            <div class="stat-value"><?php echo $total_pedidos; ?></div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i> 1.5% desde el mes pasado
            </div>
        </div>
        
        <div class="stat-card">
            <h3>Ganancia Totales</h3>
            <div class="stat-value">S./ <?php echo number_format($total_ganancias, 2); ?></div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i> 2.3% desde el mes pasado
            </div>
        </div>
        
        <div class="stat-card">
            <h3>Pedidos Entregados</h3>
            <div class="stat-value"><?php echo $pedidos_entregados; ?></div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i> 5.7% desde el mes pasado
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="orders-card">
        <h2><i class="fas fa-list-alt"></i> Pedidos Recientes</h2>
        <div class="table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>No. Orden</th>
                        <th>Monto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pedidos_recientes as $pedido): ?>
                    <tr>
                        <td><strong><?php echo $pedido['cliente']; ?></strong></td>
                        <td><?php echo $pedido['orden']; ?></td>
                        <td><strong>S./ <?php echo number_format($pedido['monto'], 2); ?></strong></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $pedido['estado'])); ?>">
                                <?php echo $pedido['estado']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Scripts específicos del dashboard para móviles
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si estamos en móvil
    const isMobile = window.innerWidth <= 768;
    
    // Animaciones solo para desktop
    if (!isMobile) {
        const statCards = document.querySelectorAll('.stat-card');
        
        statCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Efecto de conteo solo en desktop
        const statValues = document.querySelectorAll('.stat-value');
        
        statValues.forEach(value => {
            const text = value.textContent;
            const numberMatch = text.match(/(\d+\.?\d*)/);
            
            if (numberMatch && !isMobile) {
                const target = parseFloat(numberMatch[1]);
                if (!isNaN(target)) {
                    animateCount(value, target, text.includes('S./'));
                }
            }
        });
    }
    
    function animateCount(element, target, isCurrency = false) {
        let current = 0;
        const duration = 1500;
        const increment = target / (duration / 16);
        const start = performance.now();
        
        function updateCount(timestamp) {
            const elapsed = timestamp - start;
            current = Math.min(target, increment * (elapsed / 16));
            
            if (isCurrency) {
                element.textContent = 'S./ ' + current.toLocaleString('es-PE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
            
            if (current < target) {
                requestAnimationFrame(updateCount);
            }
        }
        
        requestAnimationFrame(updateCount);
    }
    
    // Manejar clicks en el contenido cuando el menú está abierto en móviles
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                if (!sidebar.classList.contains('collapsed')) {
                    // Cerrar menú si se hace click fuera de él
                    sidebar.classList.add('collapsed');
                    document.getElementById('mainContent').classList.remove('expanded');
                    const overlay = document.getElementById('overlay');
                    if (overlay) overlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            }
        }
    });
});
</script>

<?php
// Incluir footer
include 'includes/footer.php';
?>