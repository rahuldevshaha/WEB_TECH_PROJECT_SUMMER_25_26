document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("costSuccessModal");

  if (!modal) {
    return;
  }

  const closeBtn = modal.querySelector(".modal-close");
  const backdrop = modal.querySelector(".history-modal-backdrop");

  function closeModal() {
    modal.classList.remove("active");
    document.body.style.overflow = "";
  }

  if (closeBtn) {
    closeBtn.addEventListener("click", closeModal);
  }

  if (backdrop) {
    backdrop.addEventListener("click", closeModal);
  }

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      closeModal();
    }
  });
});
