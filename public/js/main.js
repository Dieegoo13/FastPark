function updateClock() {
  const now = new Date();
  const h = String(now.getHours()).padStart(2, "0");
  const m = String(now.getMinutes()).padStart(2, "0");
  document.getElementById("clock").textContent = h + ":" + m;
}
updateClock();
setInterval(updateClock, 1000);

const menuOptions = document.querySelectorAll(".menuOption");

menuOptions.forEach((option) => {
  option.addEventListener("click", function () {
    menuOptions.forEach((item) => {
      item.classList.remove("active");
    });

    this.classList.add("active");
  });
});

// ========================================MODAL===================================

// Formata data MySQL para DD/MM/YYYY HH:mm
function formatarDataBR(dataMysql) {
    if (!dataMysql) return "--";
    const data = new Date(dataMysql.replace(" ", "T"));
    return data.toLocaleString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit"
    });
}

// Formata valores para R$ 0,00
function formatarValorBR(valor) {
    if (valor === null || valor === undefined) return "0,00";
    return new Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "BRL",
        minimumFractionDigits: 2
    }).format(valor);
}

document.addEventListener("DOMContentLoaded", function () {
    const modalElement = document.getElementById("modalFechamento");
    const modal = new bootstrap.Modal(modalElement, { backdrop: true, keyboard: true });

    document.querySelectorAll(".btn-fechar").forEach(btn => {
        btn.addEventListener("click", function () {
            // Preenche a modal
            document.getElementById("m-ticket").textContent = this.dataset.id;
            document.getElementById("m-entrada").textContent = formatarDataBR(this.dataset.entrada);
            document.getElementById("m-saida").textContent = formatarDataBR(this.dataset.saida);
            document.getElementById("m-tempo").textContent = this.dataset.tempo;
            document.getElementById("m-valor").textContent = formatarValorBR(this.dataset.valor);

            // Link de confirmar
            document.getElementById("m-confirmar").href = "/fechamento/fechar/" + this.dataset.id;

            // Abre a modal
            modal.show();
        });
    });
});