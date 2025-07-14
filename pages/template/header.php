<?php
// Inclui o serviço de autenticação para verificar o status do login
// Usar __DIR__ garante que o caminho funcione de qualquer página que inclua este header
require_once __DIR__ . '/../../services/authService.php'; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Vendas</title>
    <link rel="stylesheet" href="./public/css/style.css">
</head>
<body>

<header class="toolbar">
    <div class="logo-nav">
        <a href="./index.php" class="logo">
            <img src="./public/img/logo.jpg" alt="Logo da Empresa">
        </a>
        <nav class="nav-menu">
            <a href="./index.php">Home</a>            
            <?php if (isAdmin()): ?>
                <a href="./pages/categorias/index.php">Gerenciar Categorias</a>
                <a href="./pages/produtos/index.php">Gerenciar Produtos</a>
                <a href="./pages/admin/usuarios.php">Gerenciar Usuários</a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="user-actions">
        <a href="./pages/carrinho/index.php" class="cart-button">
            <span class="cart-icon">🛒</span>
            <span id="cart-counter" class="cart-badge">0</span>
        </a>
        
        <?php
        // Busca o estado do usuário uma única vez
        $loggedUser = getLoggedUser(); 
        
        // Renderização condicional baseada no estado de login
        if ($loggedUser): 
        ?>
            <span class="welcome-message">Olá, <?= htmlspecialchars(explode(' ', $loggedUser->getNomeCompleto())[0]) ?></span>
            <a href="./pages/usuarios/logout.php" class="auth-link">Sair</a>
        <?php else: ?>
            <a href="./pages/usuarios/login.php" class="auth-link">Entre ou Cadastre-se</a>
        <?php endif; ?>
    </div>
</header>

<main class="container">