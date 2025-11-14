<?php
require_once 'php/conexao.php';
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Risco e Rabisco | Sua Papelaria Online</title>
        <link rel="website icon" type="png" href="img/rabisco.png">
        <link rel="stylesheet" href="css/index.css">
    </head>
    <body>
    
        <!-- Hero Section: A primeira impressão -->
        <section class="hero">
            <div class="hero-content">
                <h1>Dê vida às suas ideias com a <br><span>Risco e Rabisco</span></h1>
                <p>Organize seus estudos, planeje seu orçamento e crie sua lista de desejos com os melhores materiais de papelaria.</p>
                
                <div class="cta-buttons">
                    <!-- Se o usuário NÃO estiver logado -->
                    <?php if(!isset($_SESSION['id_usuario'])): ?>
                        <a href="php/registrar.php" class="btn btn-primary">Criar Minha Conta Grátis</a>
                        <a href="php/login.php" class="btn btn-outline">Já tenho conta</a>
                    <?php else: ?>
                        <!-- Se já estiver logado -->
                        <a href="php/produtos.php" class="btn btn-primary">Ver Catálogo Completo</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    
        <!-- Seção de Vantagens: Por que se cadastrar? -->
        <section class="features">
            <div class="feature-card">
                <h3>❤️ Favoritos</h3>
                <p>Salve os itens que você mais amou para não perder de vista.</p>
            </div>
            <div class="feature-card">
                <h3>💰 Orçamento Inteligente</h3>
                <p>Adicione itens e calcule o valor total do seu material escolar automaticamente.</p>
            </div>
            <div class="feature-card">
                <h3>🚀 Praticidade</h3>
                <p>Monte sua lista de material escolar sem sair de casa.</p>
            </div>
        </section>
    
        <!-- Teaser de Produtos: Mostra 4 itens aleatórios para dar vontade -->
        <section class="teaser-produtos">
            <h2>Um gostinho do nosso estoque</h2>
            <div class="grid-produtos">
                <?php
                // SQL para pegar 4 produtos aleatórios
                $sql = "SELECT * FROM produtos ORDER BY RAND() LIMIT 4";
                $result = $conn->query($sql);
    
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // Ajuste do caminho da imagem (remove o "../" pois estamos na raiz)
                        $img_path = str_replace('../', '', $row['imagem']);
                        
                        echo '<div class="card-produto-blur">';
                        echo '<img src="' . $img_path . '" alt="' . $row['nome'] . '">';
                        echo '<h4>' . $row['nome'] . '</h4>';
                        echo '<p class="blur-price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</p>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <p class="aviso-cadastro">Cadastre-se para ver preços e criar seu orçamento!</p>
        </section>
    
        <!-- Rodapé Simples -->
        <footer>
            <p>&copy; 2025 Risco e Rabisco - Projeto Senac</p>
        </footer>
    
    </body>
</html>