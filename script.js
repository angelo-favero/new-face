document.addEventListener("DOMContentLoaded", () => {
  // Verifica se os elementos existem antes de inicializar as funções
  if (document.querySelector(".carousel-slide")) {
    initCarousel();
  }

  if (document.querySelectorAll(".slider-item").length > 0) {
    initSlider();
  }

  initSmoothScrolling();

  if (
    document.querySelectorAll(
      ".box1, .box2, .box3, .box4, .box5, .box6, .box7, .box8"
    ).length > 0
  ) {
    initOpacityEffect();
  }

  if (document.getElementById("back-to-top")) {
    initBackToTop();
  }
});

function initSlider() {
  const sliderItems = document.querySelectorAll(".slider-item");
  let currentIndex = 0;

  // Função para trocar os slides
  function nextSlide() {
    // Adicione a classe leaving ao slide atual
    sliderItems[currentIndex].classList.remove("active");
    sliderItems[currentIndex].classList.add("leaving");

    // Depois de um pequeno delay, remova a classe leaving
    setTimeout(() => {
      sliderItems[currentIndex].classList.remove("leaving");

      // Avance para o próximo slide
      currentIndex = (currentIndex + 1) % sliderItems.length;

      // Adicione a classe active ao novo slide atual
      sliderItems[currentIndex].classList.add("active");
    }, 800); // Tempo igual à transição
  }

  // Inicialize o primeiro slide como ativo
  sliderItems[0].classList.add("active");

  // Configure o intervalo para alternar automaticamente
  setInterval(nextSlide, 4000); // Troca a cada 4 segundos
}

function initCarousel() {
  const slides = document.querySelectorAll(".carousel-slide");
  const indicators = document.querySelectorAll(".nav-indicator");
  const prevButton = document.querySelector(".prev");
  const nextButton = document.querySelector(".next");
  const slideLinks = document.querySelectorAll(".slide-link");
  let currentSlide = 0;

  function updateSlides() {
    slides.forEach((slide) => slide.classList.remove("ativo"));
    indicators.forEach((indicator) => indicator.classList.remove("active"));

    slides[currentSlide].classList.add("ativo");
    indicators[currentSlide].classList.add("active");
  }
  prevButton.addEventListener("click", () => {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    updateSlides();
  });

  nextButton.addEventListener("click", () => {
    currentSlide = (currentSlide + 1) % slides.length;
    updateSlides();
  });

  indicators.forEach((indicator, index) => {
    indicator.addEventListener("click", () => {
      currentSlide = index;
      updateSlides();
    });
  });

  slideLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      nextButton.click(); // ou prevButton.click() dependendo do lado que você quer que vá
    });
  });
}

function initSmoothScrolling() {
  // Seleciona todos os links que começam com #
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();

      // Se for link para o topo
      if (this.getAttribute("href") === "#top") {
        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
        return;
      }

      // Para outros links
      const target = document.querySelector(this.getAttribute("href"));
      const headerOffset = 100; // Ajuste conforme necessidade
      const elementPosition = target.getBoundingClientRect().top;
      const offsetPosition =
        elementPosition + window.pageYOffset - headerOffset;

      window.scrollTo({
        top: offsetPosition,
        behavior: "smooth",
      });
    });
  });
}

function initOpacityEffect() {
  // Seleciona todos os boxes com links
  const boxes = document.querySelectorAll(
    ".box1, .box2, .box3, .box4, .box5, .box6, .box7, .box8"
  );
  const allImages = document.querySelectorAll(
    ".quadrante1 a img, .quadrante2 a img, .quadrante3 a img"
  );

  // Adiciona eventos para cada box
  boxes.forEach((box) => {
    // Quando o mouse entra em um box
    box.addEventListener("mouseenter", function () {
      // Reduz a opacidade de todas as imagens
      allImages.forEach((img) => {
        img.style.opacity = "0.4";
        // Apenas controla a opacidade, não a escala
      });

      // Restaura a opacidade da imagem atual
      const currentImage = this.querySelector("a img");
      if (currentImage) {
        currentImage.style.opacity = "1";
        // Não modifica a transform
      }
    });
  });

  // Quando o mouse sai de todos os boxes (mouseout na seção)
  const sections = document.querySelectorAll(
    ".quadrante1, .quadrante2, .quadrante3"
  );
  sections.forEach((section) => {
    section.addEventListener("mouseleave", function () {
      // Restaura apenas a opacidade das imagens
      allImages.forEach((img) => {
        img.style.opacity = "1";
        // Não modifica a transform ou box-shadow
      });
    });
  });
}

function initBackToTop() {
  const backToTopButton = document.getElementById("back-to-top");

  // Mostrar ou esconder o botão baseado na posição de rolagem
  window.addEventListener("scroll", function () {
    if (window.pageYOffset > 300) {
      // Mostrar após rolar 300px
      backToTopButton.classList.add("show");
    } else {
      backToTopButton.classList.remove("show");
    }
  });

  // Função de rolar para o topo
  backToTopButton.addEventListener("click", function () {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
}
