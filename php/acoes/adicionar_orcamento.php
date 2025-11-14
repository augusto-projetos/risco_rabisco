<?php
// 1. INICIAR SESSÃO E CONEXÃO
session_start();
require_once '../conexao.php'; // Usamos ../ pois estamos dentro da pasta 'acoes'

// 2. SEGURANÇA (O "PORTEIRO")
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php?erro=restrito");
    exit();
}

// 3. VERIFICAR SE O MÉTODO É POST E SE OS DADOS VIERAM
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_produto'])) {

    // 4. COLETAR E VALIDAR DADOS
    $id_usuario = $_SESSION['id_usuario'];
    $id_produto = (int)$_POST['id_produto'];
    $quantidade_adicionar = (isset($_POST['quantidade']) && (int)$_POST['quantidade'] > 0) ? (int)$_POST['quantidade'] : 1;

    // 5. LÓGICA DO ORÇAMENTO (COM TRATAMENTO DE ERRO)
    
    try {
        // --- Início do bloco "Tentar" ---

        // Primeiro, verificar se o usuário JÁ TEM esse item no orçamento
        $stmt_verificar = $conn->prepare("SELECT quantidade FROM orcamento_itens WHERE id_usuario = ? AND id_produto = ?");
        $stmt_verificar->bind_param("ii", $id_usuario, $id_produto);
        $stmt_verificar->execute();
        $resultado = $stmt_verificar->get_result();

        if ($resultado->num_rows > 0) {
            // --- SE JÁ EXISTE: ATUALIZAR (UPDATE) ---
            $item_existente = $resultado->fetch_assoc();
            $nova_quantidade = $item_existente['quantidade'] + $quantidade_adicionar;

            $stmt_update = $conn->prepare("UPDATE orcamento_itens SET quantidade = ? WHERE id_usuario = ? AND id_produto = ?");
            $stmt_update->bind_param("iii", $nova_quantidade, $id_usuario, $id_produto);
            $stmt_update->execute();
            $stmt_update->close();

        } else {
            // --- SE NÃO EXISTE: INSERIR (INSERT) ---
            // É aqui que o erro de Foreign Key (sessão inválida) pode acontecer
            $stmt_insert = $conn->prepare("INSERT INTO orcamento_itens (id_usuario, id_produto, quantidade) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("iii", $id_usuario, $id_produto, $quantidade_adicionar);
            $stmt_insert->execute();
            $stmt_insert->close();
        }

        $stmt_verificar->close();
        $conn->close();

        // 6. REDIRECIONAR O USUÁRIO (SUCESSO)
        header("Location: ../orcamento.php?sucesso=add");
        exit();

        // --- Fim do bloco "Tentar" ---

    } catch (mysqli_sql_exception $e) {
        // 7. "CAPTURAR" O ERRO FATAL DO MYSQL
        // Se o erro for "foreign key constraint fails" (código 1452),
        // é 99% de certeza que a sessão do usuário está dessincronizada.
        
        if ($e->getCode() == 1452) {
            // Ação amigável: Forçar logout e pedir novo login
            session_unset();
            session_destroy();
            header("Location: ../login.php?erro=sessao_invalida");
            exit();
        } else {
            // Se for qualquer outro erro de SQL, mostra uma msg genérica
            // (Idealmente, você registraria $e->getMessage() em um log de erros)
            header("Location: ../produtos.php?erro=bd");
            exit();
        }
    }

} else {
    // Se alguém tentar acessar este arquivo diretamente pela URL
    header("Location: ../produtos.php");
    exit();
}
?>