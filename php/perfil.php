<?php
// 1. Definir o CSS específico desta página
$pagina_css = "../css/perfil.css";

// 2. Incluir o cabeçalho
// O 'cabecalho.php' já lida com a sessão, conexão e segurança
include 'cabecalho.php';

// Inicializar variáveis de mensagem
$mensagem_sucesso = "";
$mensagem_erro_dados = "";
$mensagem_erro_senha = "";

// 3. PROCESSAR FORMULÁRIOS (LÓGICA POST)
// O $conn e $id_usuario_logado vêm do 'cabecalho.php'
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- LÓGICA 1: ATUALIZAR DADOS PESSOAIS ---
    if (isset($_POST['atualizar_dados'])) {
        $novo_nome = trim($_POST['nome']);
        
        if (empty($novo_nome)) {
            $mensagem_erro_dados = "O nome não pode ficar em branco.";
        } else {
            // Atualiza o nome no banco
            $stmt_nome = $conn->prepare("UPDATE usuarios SET nome = ? WHERE id = ?");
            $stmt_nome->bind_param("si", $novo_nome, $id_usuario_logado);
            
            if ($stmt_nome->execute()) {
                // SUCESSO! Atualiza também o nome na SESSÃO
                $_SESSION['nome_usuario'] = $novo_nome;
                $mensagem_sucesso = "Nome atualizado com sucesso!";
                // Precisamos recarregar o primeiro nome para o cabeçalho
                $primeiro_nome = explode(' ', $novo_nome)[0]; 
            } else {
                $mensagem_erro_dados = "Erro ao atualizar o nome. Tente novamente.";
            }
            $stmt_nome->close();
        }
    }

    // --- LÓGICA 2: ATUALIZAR SENHA ---
    if (isset($_POST['atualizar_senha'])) {
        $senha_atual = $_POST['senha_atual'];
        $nova_senha = $_POST['nova_senha'];
        $confirma_senha = $_POST['confirma_senha'];

        // 1. Validar campos
        if (empty($senha_atual) || empty($nova_senha) || empty($confirma_senha)) {
            $mensagem_erro_senha = "Todos os campos de senha são obrigatórios.";
        } elseif ($nova_senha !== $confirma_senha) {
            $mensagem_erro_senha = "As novas senhas não coincidem.";
        } elseif (strlen($nova_senha) < 6) {
            $mensagem_erro_senha = "A nova senha deve ter pelo menos 6 caracteres.";
        } else {
            // 2. Buscar a senha atual no banco para verificar
            // ***** CORREÇÃO: Usamos uma variável diferente ($usuario_senha) *****
            $stmt_verificar = $conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
            $stmt_verificar->bind_param("i", $id_usuario_logado);
            $stmt_verificar->execute();
            $resultado_senha = $stmt_verificar->get_result();
            $usuario_senha = $resultado_senha->fetch_assoc();
            $hash_senha_atual = $usuario_senha['senha'];
            $stmt_verificar->close();

            // 3. Verificar se a "Senha Atual" digitada bate com a do banco
            if (password_verify($senha_atual, $hash_senha_atual)) {
                // 4. Se bateu, criptografa e atualiza a nova senha
                $novo_hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
                
                $stmt_senha = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                $stmt_senha->bind_param("si", $novo_hash_senha, $id_usuario_logado);
                
                if ($stmt_senha->execute()) {
                    $mensagem_sucesso = "Senha alterada com sucesso!";
                } else {
                    $mensagem_erro_senha = "Erro ao alterar a senha. Tente novamente.";
                }
                $stmt_senha->close();
                
            } else {
                // Se a senha atual não bateu
                $mensagem_erro_senha = "A 'Senha Atual' está incorreta.";
            }
        }
    }
} // Fim do if (REQUEST_METHOD == POST)


// 4. BUSCAR DADOS ATUAIS PARA EXIBIR (LÓGICA GET)
// ***** CORREÇÃO: Esta lógica agora roda *sempre* *****
// Seja GET ou POST, nós *sempre* precisamos dos dados para preencher o formulário.
$stmt_get = $conn->prepare("SELECT nome, email, data_cadastro FROM usuarios WHERE id = ?");
$stmt_get->bind_param("i", $id_usuario_logado);
$stmt_get->execute();
$resultado = $stmt_get->get_result();
$usuario = $resultado->fetch_assoc(); // Agora $usuario SEMPRE terá nome, email e data.
$stmt_get->close();

// Fechamos a conexão
$conn->close();
?>

<h1 class="titulo-pagina">Meu Perfil</h1>

<div class="perfil-container">

    <div class="perfil-card">
        <h2>Dados Pessoais</h2>
        <form action="perfil.php" method="POST">
            <input type="hidden" name="atualizar_dados" value="1">
            
            <?php if (!empty($mensagem_erro_dados)): ?>
                <div class="mensagem-erro"><?php echo $mensagem_erro_dados; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail (não pode ser alterado)</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
            </div>

            <div class="form-group">
                <label for="data_cadastro">Membro desde</label>
                <input type="text" id="data_cadastro" value="<?php echo date("d/m/Y", strtotime($usuario['data_cadastro'])); ?>" disabled>
            </div>

            <button type="submit" class="btn-primario">Salvar Alterações</button>
        </form>
    </div>

    <div class="perfil-card">
        <h2>Alterar Senha</h2>
        <form action="perfil.php" method="POST">
            <input type="hidden" name="atualizar_senha" value="1">

            <?php if (!empty($mensagem_erro_senha)): ?>
                <div class="mensagem-erro"><?php echo $mensagem_erro_senha; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="senha_atual">Senha Atual</label>
                <input type="password" id="senha_atual" name="senha_atual" required>
            </div>

            <div class="form-group">
                <label for="nova_senha">Nova Senha</label>
                <input type="password" id="nova_senha" name="nova_senha" required>
            </div>

            <div class="form-group">
                <label for="confirma_senha">Confirmar Nova Senha</label>
                <input type="password" id="confirma_senha" name="confirma_senha" required>
            </div>

            <button type="submit" class="btn-primario">Alterar Senha</button>
        </form>
    </div>
</div>

<?php if (!empty($mensagem_sucesso)): ?>
    <div class="mensagem-sucesso-flutuante" id="msg-sucesso">
        <i class="fa-solid fa-check-circle"></i> <?php echo $mensagem_sucesso; ?>
    </div>
    <script>
        setTimeout(() => {
            const msgSucesso = document.getElementById('msg-sucesso');
            if (msgSucesso) {
                msgSucesso.style.opacity = '0';
                // Remove o elemento após a animação de fade-out
                setTimeout(() => msgSucesso.remove(), 500);
            }
        }, 3000); // 3 segundos
    </script>
<?php endif; ?>

<?php
// 5. Fechamento do HTML
?>
</main>
</body>
</html>