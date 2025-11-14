document.getElementById('search-bar').addEventListener('input', function() {
    let searchTerm = this.value.toLowerCase();
    let livros = document.querySelectorAll('.produto');

    livros.forEach(function(livro) {
        let titulo = livro.querySelector('h2').textContent.toLowerCase();
        if (titulo.includes(searchTerm)) {
            livro.style.display = 'block';
        } else {
            livro.style.display = 'none';
        }
    });
});