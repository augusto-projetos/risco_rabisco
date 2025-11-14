<?php
// 1. Iniciar a sessão.
// É obrigatório iniciar a sessão para poder destruí-la.
session_start();

// 2. Limpar todas as variáveis de sessão.
// Isso garante que $_SESSION['logado'], $_SESSION['id_usuario'], etc., sejam apagados.
$_SESSION = array();

// 3. Destruir a sessão.
// Isso remove o cookie de sessão do navegador e invalida a sessão no servidor.
session_destroy();

// 4. Redirecionar para a página de login.
// Enviamos um parâmetro '?logout=1' para que a página login.php
// possa (opcionalmente) mostrar uma mensagem "Você saiu com sucesso!".
header("Location: login.php?logout=1");

// 5. Garantir que o script pare aqui.
exit();
?>