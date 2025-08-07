document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, {
        threshold: 0.1 // Elemento aparece quando 10% dele estiver visível
    });
        // Observa todos os elementos com o atributo data-animate
    document.querySelectorAll('[data-animate]').forEach(element => observer.observe(element));
});