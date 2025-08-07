document.addEventListener("DOMContentLoaded", function () {
  // Obter o caminho da URL atual
  const currentPath = decodeURIComponent(window.location.pathname);

  // Extrair o nome do arquivo da URL atual
  const currentFile = currentPath.split("/").pop().toLowerCase();

  // Obter todos os links do menu
  const menuLinks = document.querySelectorAll(".menu-list a");

  // Remover a classe 'active' de todos os links
  menuLinks.forEach((link) => {
    link.classList.remove("active");

    // Obter o href do link e normalizá-lo
    const href = link.getAttribute("href").toLowerCase();

    // Verificar se o href do link corresponde à página atual
    if (
      currentFile === href ||
      currentPath.endsWith(href) ||
      (currentFile === "" && href === "index.html")
    ) {
      link.classList.add("active");
    }

    // Caso especial para portifólio.html
    if (currentFile === "portifólio.html" && href === "portifólio.html") {
      link.classList.add("active");
    }

    // Caso especial para a home
    if (
      (currentFile === "" || currentFile === "index.html") &&
      (href === "index.html" || href === "#top")
    ) {
      link.classList.add("active");
    }
  });
});
