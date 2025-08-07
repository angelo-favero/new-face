document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.querySelector('.menu-btn');
    const menuList = document.querySelector('.menu-list');
    
    menuBtn.addEventListener('click', () => {
        menuBtn.classList.toggle('active');
        menuList.classList.toggle('active');
    });

    // Fecha o menu ao clicar em um link
    document.querySelectorAll('.menu-list a').forEach(link => {
        link.addEventListener('click', () => {
            menuBtn.classList.remove('active');
            menuList.classList.remove('active');
        });
    });
});