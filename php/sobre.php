<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/sobre.css">
        <title>Sobre Nós</title>
    </head>
    <body>
        <?php include 'cabecalho.php' ?>

        <h1 id="tituloInicial">Contatos</h1> <br>
        <p>Endereço: Avenida Monsenhor Rafael, nº 1120, Bairro Primavera, Pingo-D’Água, GO. - CEP: 16587-574</p> <br>
        <p>Telefone: (33) 94815-7316</p> <br>
        <p>E-mail: papelariad'agua@gmail.com</p> <br>
        <p>Horário de Funcionamento:</p> <br>
        <p>Segunda à sexta de 9h às 18h </p>
        <p>Sabado de 9h às 13h </p>
        <p>Domingo: Fechados</p> <br>

        <form action="" method="post" id="form">
            <h2>Formulário para entrar em contato:</h2>

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>
            <label for="nome">Email:</label>
            <input type="text" name="email" id="email" required>
            <label for="nome">Telefone:</label>
            <input type="tel" name="tel" id="telefone" required>
            <label for="nome">Endereço:</label>
            <input type="text" id="endereco" required>
            <textarea name="" id=""></textarea>
            
        </form>
    </body>
</html>
