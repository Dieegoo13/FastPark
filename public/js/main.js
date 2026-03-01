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
    minute: "2-digit",
  });
}

// Formata valores para R$ 0,00
function formatarValorBR(valor) {
  if (valor === null || valor === undefined) return "0,00";
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
    minimumFractionDigits: 2,
  }).format(valor);
}

document.addEventListener("DOMContentLoaded", function () {
  const modalElement = document.getElementById("modalFechamento");
  const modal = new bootstrap.Modal(modalElement, {
    backdrop: true,
    keyboard: true,
  });

  const modalConfirmar = document.getElementById("m-confirmar");
  const mTicket = document.getElementById("m-ticket");
  const mEntrada = document.getElementById("m-entrada");
  const mSaida = document.getElementById("m-saida");
  const mTempo = document.getElementById("m-tempo");
  const mValor = document.getElementById("m-valor");

  document.querySelectorAll(".btn-fechar").forEach((btn) => {
    btn.addEventListener("click", function () {
      const id = this.dataset.id;
      const entrada = this.dataset.entrada;
      const saida = new Date(); // momento do fechamento
      const tempo = this.dataset.tempo || 0;
      const valor = this.dataset.valor || 0;

      // Preenche a modal
      mTicket.textContent = id;
      mEntrada.textContent = formatarDataBR(entrada);
      mSaida.textContent = saida.toLocaleString();
      mTempo.textContent = tempo;
      mValor.textContent = Number(valor).toFixed(2).replace(".", ",");

      modalConfirmar.href = `/fechamento/fechar/${id}`;

      // Abre a modal
      modal.show();
    });
  });

  // Opcional: reseta os dados quando modal é fechada
  modalElement.addEventListener("hidden.bs.modal", () => {
    mTicket.textContent = "";
    mEntrada.textContent = "";
    mSaida.textContent = "";
    mTempo.textContent = "";
    mValor.textContent = "";
    modalConfirmar.href = "#";
  });
});