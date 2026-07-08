(function () {
  window.acquista = function (idPersonalizzazione, nome, costo) {
    const message = window.cqT(
      'student.customization.js.confirm_buy',
      'Sei sicuro di voler acquistare la personalizzazione: {name} al costo di {cost} monete?'
    )
      .replace('{name}', nome)
      .replace('{cost}', costo);

    if (!confirm(message)) {
      return;
    }

    const form = document.getElementById('buyCustomizationForm');
    const input = document.getElementById('buy_customization_id');
    if (!form || !input) return;

    input.value = String(idPersonalizzazione || 0);
    form.submit();
  };

  document.addEventListener('DOMContentLoaded', function () {
    const uploadForm = document.getElementById('studentCustomizationUploadForm');
    const imageInput = document.getElementById('studentCustomizationImage');

    if (!uploadForm || !imageInput) {
      return;
    }

    uploadForm.addEventListener('submit', function (event) {
      const file = imageInput.files && imageInput.files.length > 0 ? imageInput.files[0] : null;
      if (!file) {
        return;
      }

      const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
      if (!allowedTypes.includes(file.type)) {
        event.preventDefault();
        window.alert(window.cqT('student.customization.shop.upload.format_error', 'Carica un file JPEG, PNG o GIF.'));
        return;
      }

      if (file.size > 5 * 1024 * 1024) {
        event.preventDefault();
        window.alert(window.cqT('student.customization.shop.upload.size_error', "L'immagine non deve superare i 5 Mbyte."));
      }
    });
  });
})();
