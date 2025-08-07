document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('back-to-top');
    
    // Verifica se o elemento existe na página atual
    if (backToTopButton) {
        // Mostrar ou esconder o botão baseado na posição de rolagem
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) { // Mostrar após rolar 300px
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        // Função de rolar para o topo
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});