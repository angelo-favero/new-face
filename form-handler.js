document.addEventListener("DOMContentLoaded", function () {
  // 1. Verifica URL para parâmetros de status
  const urlParams = new URLSearchParams(window.location.search);
  const status = urlParams.get("status");
  const formMessages = document.getElementById("form-messages");

  if (status) {
    formMessages.style.display = "block";

    if (status === "success") {
      document.querySelector("#form-messages .success-message").style.display =
        "block";
    } else if (status === "error") {
      const errorMsg = document.querySelector("#form-messages .error-message");
      errorMsg.style.display = "block";

      // Adiciona detalhes do erro, se houver
      const msg = urlParams.get("msg");
      if (msg) {
        document.getElementById("error-details").textContent =
          decodeURIComponent(msg);
      }
    }
  }

  // 2. Configura o gerenciamento do envio de formulários
  const forms = document.querySelectorAll(".formulario");

  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const formMsgs = this.querySelector("#form-messages");
      if (!formMsgs) return;

      formMsgs.style.display = "block";
      formMsgs.innerHTML =
        '<div class="info-message">Enviando mensagem...</div>';

      const formData = new FormData(this);

      fetch("process-form.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          if (response.ok) return response.text();
          throw new Error("Erro na rede ao enviar o formulário.");
        })
        .then((data) => {
          formMsgs.innerHTML =
            '<div class="success-message">Mensagem enviada com sucesso! Entraremos em contato em breve.</div>';
          form.reset(); // Limpa o formulário
        })
        .catch((error) => {
          formMsgs.innerHTML =
            '<div class="error-message">Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente.</div>';
          console.error("Erro:", error);
        });
    });
  });
});
