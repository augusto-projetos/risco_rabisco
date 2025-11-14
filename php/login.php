<?php
// Sempre iniciar a sessão no topo
session_start();

// Incluir o arquivo de conexão
require_once 'conexao.php';

// Inicializar variáveis
$mensagem_erro = "";
$mensagem_sucesso = "";
$email = ""; // Para manter o e-mail no campo em caso de erro de senha

// --- Verificação de Sucesso (vinda do registrar.php) ---
if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1') {
    $mensagem_sucesso = "Conta criada com sucesso! Faça login para continuar.";
}

// --- Verificação de Logout (vinda do logout.php) ---
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    $mensagem_sucesso = "Você saiu com sucesso!";
}

// --- Verificação de Erro (vinda do cabecalho.php) ---
if (isset($_GET['erro']) && $_GET['erro'] == 'restrito') {
    $mensagem_erro = "Área restrita. Por favor, faça login.";
}

// --- NOVO: Verificação de Erro (vinda do acoes/adicionar_orcamento.php) ---
if (isset($_GET['erro']) && $_GET['erro'] == 'sessao_invalida') {
    $mensagem_erro = "Sua sessão expirou ou é inválida. Por favor, faça login novamente.";
}

// --- NOVO: Verificação de Erro (Genérico do BD) ---
if (isset($_GET['erro']) && $_GET['erro'] == 'bd') {
    $mensagem_erro = "Ocorreu um erro inesperado. Tente novamente mais tarde.";
}

// --- Processamento do Formulário de Login ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Coletar dados
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // 2. Validar dados básicos
    if (empty($email) || empty($senha)) {
        $mensagem_erro = "E-mail e senha são obrigatórios.";
    } else {
        // 3. Buscar o usuário no banco de dados
        $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result(); // Precisamos disso para verificar num_rows e usar bind_result

        // 4. Verificar se o e-mail foi encontrado
        if ($stmt->num_rows == 1) {
            
            // 5. O e-mail existe, vamos verificar a senha
            $stmt->bind_result($id_usuario, $nome_usuario, $hash_senha);
            $stmt->fetch();

            if (password_verify($senha, $hash_senha)) {
                // 6. SENHA CORRETA!
                
                // Regenerar a sessão (boa prática de segurança)
                session_regenerate_id(true); 

                // Armazenar os dados na sessão
                $_SESSION['logado'] = true;
                $_SESSION['id_usuario'] = $id_usuario;
                $_SESSION['nome_usuario'] = $nome_usuario;
                
                // Redirecionar para a página principal da loja (logada)
                header("Location: produtos.php"); 
                exit();

            } else {
                // Senha incorreta
                $mensagem_erro = "E-mail ou senha inválidos.";
            }

        } else {
            // E-mail não encontrado
            $mensagem_erro = "E-mail ou senha inválidos.";
        }
        $stmt->close();
    }
    // Fechamos a conexão aqui se o método for POST
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Risco e Rabisco</title>
        <link rel="website icon" type="png" href="../img/rabisco.png">
        <link rel="stylesheet" href="../css/auth.css">
    </head>
    <body>

        <div class="auth-container">
            <!-- Logo -->
            <a href="../index.php" class="logo-link">
                <img src="../img/logotipo.jpg" alt="Logo Risco e Rabisco">
                <span>Risco e Rabisco</span>
            </a>

            <!-- Formulário de Login -->
            <form class="auth-form" method="POST" action="login.php">
                <h2>Faça Login</h2>
                <p>Bem-vindo de volta! Sentimos sua falta.</p>

                <!-- Mensagem de ERRO (se houver) -->
                <?php if (!empty($mensagem_erro)): ?>
                    <div class="mensagem-erro">
                        <?php echo $mensagem_erro; ?>
                    </div>
                <?php endif; ?>

                <!-- Mensagem de SUCESSO (se houver) -->
                <?php if (!empty($mensagem_sucesso)): ?>
                    <div class="mensagem-sucesso">
                        <?php echo $mensagem_sucesso; ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <!-- Manter o e-mail preenchido em caso de erro -->
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <button type="submit" class="btn-auth">Entrar</button>
                
                <p class="auth-link">
                    Não tem uma conta? <a href="registrar.php">Crie uma</a>
                </p>
            </form>
        </div>

    </body>
</html>