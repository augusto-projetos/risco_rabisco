<?php
// 1. INICIAR SESSÃO E CONEXÃO
session_start();
require_once '../conexao.php'; // Estamos na pasta 'acoes', então é ../conexao.php

// 2. SEGURANÇA (O "PORTEIRO")
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php?erro=restrito");
    exit();
}

// 3. VERIFICAR SE O MÉTODO É POST E SE O ID VEIO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_produto'])) {

    // 4. COLETAR DADOS
    $id_usuario = $_SESSION['id_usuario'];
    $id_produto = (int)$_POST['id_produto'];
    
    // O "TRUQUE": Verificamos se a 'acao' foi enviada.
    // Se foi (ex: "remover"), usamos ela.
    // Se não, o padrão é "adicionar".
    $acao = $_POST['acao'] ?? 'adicionar';

    // 5. LÓGICA DE ADICIONAR/REMOVER
    
    try {
        
        if ($acao == 'remover') {
            // --- LÓGICA DE REMOÇÃO ---
            $stmt_exec = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_produto = ?");
            // "ii" -> dois inteiros
            $stmt_exec->bind_param("ii", $id_usuario, $id_produto); 
            $redirect_url = "../favoritos.php?sucesso=removido"; // Redireciona para favoritos

        } else {
            // --- LÓGICA DE ADIÇÃO ---
            $stmt_exec = $conn->prepare("INSERT IGNORE INTO favoritos (id_usuario, id_produto) VALUES (?, ?)");
            
            // ***** O ERRO ESTAVA AQUI *****
            // Corrigido de "iii" para "ii" (pois são 2 variáveis)
            $stmt_exec->bind_param("ii", $id_usuario, $id_produto); 
            // ***** FIM DA CORREÇÃO *****

            $redirect_url = "../produtos.php?sucesso=favorito"; // Redireciona para produtos
        }

        // Executa o SQL (DELETE ou INSERT IGNORE)
        $stmt_exec->execute();
        $stmt_exec->close();
        $conn->close();

        // 6. REDIRECIONAR DE VOLTA (SUCESSO)
        header("Location: " . $redirect_url);
        exit();

    } catch (mysqli_sql_exception $e) {
        // 7. "CAPTURAR" ERRO (Sessão inválida ou outro)
        if ($e->getCode() == 1452) { // Erro de Foreign Key (Sessão inválida)
            session_unset();
            session_destroy();
            header("Location: ../login.php?erro=sessao_invalida");
            exit();
        } else {
            // Outro erro de BD
            header("Location: ../produtos.php?erro=bd");
            exit();
        }
    }

} else {
    // Se alguém acessar o arquivo sem dados POST
    header("Location: ../produtos.php");
    exit();
}
?>