<?php
// Sempre iniciar a sessão no topo
session_start();

// Incluir o arquivo de conexão
require_once 'conexao.php';

// Inicializar variáveis para feedback e para manter os dados no form
$mensagem_erro = "";
$nome = "";
$email = "";

// Verificar se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Coletar e limpar os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // 2. Validar os dados
    if (empty($nome) || empty($email) || empty($senha) || empty($confirma_senha)) {
        $mensagem_erro = "Todos os campos são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem_erro = "Por favor, insira um e-mail válido.";
    } elseif (strlen($senha) < 6) {
        $mensagem_erro = "A senha deve ter pelo menos 6 caracteres.";
    } elseif ($senha !== $confirma_senha) {
        $mensagem_erro = "As senhas não coincidem.";
    } else {
        // 3. Se a validação básica passou, verificar o banco de dados
        
        // Usar PREPARED STATEMENTS para evitar SQL Injection
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email); // "s" = string
        $stmt->execute();
        $stmt->store_result(); // Armazena o resultado para verificar o num_rows

        if ($stmt->num_rows > 0) {
            // Email já existe
            $mensagem_erro = "Este e-mail já está cadastrado. Tente fazer login.";
        } else {
            // 4. Email é novo. Vamos criar a conta.
            
            // Criptografar a senha (NUNCA salvar senha em texto puro)
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Preparar a inserção
            $stmt_insert = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $nome, $email, $senha_hash);

            // Executar e verificar
            if ($stmt_insert->execute()) {
                // Sucesso!
                // Redireciona para a página de login com uma msg de sucesso
                header("Location: login.php?sucesso=1");
                exit(); // Sempre usar exit() após um redirecionamento
            } else {
                $mensagem_erro = "Ocorreu um erro ao criar sua conta. Tente novamente.";
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Criar Conta - Risco e Rabisco</title>
        <link rel="website icon" type="png" href="../img/rabisco.png">
        <link rel="stylesheet" href="../css/auth.css">
    </head>
    <body>

        <div class="auth-container">
            <!-- Link para voltar para a Home -->
            <a href="../index.php" class="logo-link">
                <img src="../img/logotipo.jpg" alt="Logo Risco e Rabisco">
                <span>Risco e Rabisco</span>
            </a>

            <!-- Formulário de Registro -->
            <form class="auth-form" method="POST" action="registrar.php">
                <h2>Crie sua Conta</h2>
                <p>É rápido e fácil para começar a montar seus orçamentos.</p>

                <!-- PHP para exibir mensagem de erro, se houver -->
                <?php if (!empty($mensagem_erro)): ?>
                    <div class="mensagem-erro">
                        <?php echo $mensagem_erro; ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <!-- Usamos htmlspecialchars para segurança e para manter o valor em caso de erro -->
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirma_senha">Confirmar Senha</label>
                    <input type="password" id="confirma_senha" name="confirma_senha" required>
                </div>
                
                <button type="submit" class="btn-auth">Cadastrar</button>
                
                <p class="auth-link">
                    Já tem uma conta? <a href="login.php">Faça Login</a>
                </p>
            </form>
        </div>

    </body>
</html>