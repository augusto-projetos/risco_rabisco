// 1. Espera o HTML ser carregado antes de rodar o script
document.addEventListener("DOMContentLoaded", () => {

    // 2. Seleciona os dois elementos que vamos usar
    const btnMobile = document.getElementById("btn-mobile");
    const containerNav = document.getElementById("container-nav");

    // 3. Verifica se os elementos realmente existem
    if (btnMobile && containerNav) {
        
        // 4. Adiciona a função de "clique" no botão
        btnMobile.addEventListener("click", () => {
            
            // 5. A MÁGICA:
            // Adiciona ou remove a classe 'active' do container
            containerNav.classList.toggle("active");

            // 6. Bônus (Acessibilidade):
            // Avisa leitores de tela se o menu está aberto ou fechado
            const estaAtivo = containerNav.classList.contains("active");
            btnMobile.setAttribute("aria-expanded", estaAtivo);
        });
    }

});