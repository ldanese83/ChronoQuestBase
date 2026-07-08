(function () {
  document.addEventListener("DOMContentLoaded", function () {
    const reasonField = document.getElementById("motivazione_sconto");

    if (window.tinymce && reasonField) {
      window.tinymce.init({
        selector: "#motivazione_sconto",
        license_key: "gpl",
        menubar: false,
        branding: false,
        height: 260,
        plugins: "lists link image table code",
        toolbar:
          "undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | link table | code",
      });
    }

    if (reasonField && reasonField.form) {
      reasonField.form.addEventListener("submit", function (event) {
        const editor = window.tinymce
          ? window.tinymce.get("motivazione_sconto")
          : null;

        if (editor) {
          editor.save();
        }

        const reasonText = editor
          ? editor.getContent({ format: "text" })
          : reasonField.value;

        if (!reasonText.replace(/\u00a0/g, " ").trim()) {
          event.preventDefault();
          alert(
            window.cqT(
              "teacher.customizations.discount.reason.required",
              "Inserisci la motivazione della giornata sconti.",
            ),
          );

          if (editor) {
            editor.focus();
          } else {
            reasonField.focus();
          }
        }
      });
    }

    if (window.jQuery && $.fn.DataTable && $("#discountDaysTable").length) {
      $("#discountDaysTable").DataTable({
        order: [[0, "desc"]],
        pageLength: 25,
        language: {
          search: window.cqT("js.table.search", "Cerca:"),
          lengthMenu: window.cqT(
            "teacher.customizations.discount.table.length_menu",
            "Mostra _MENU_ record",
          ),
          info: window.cqT(
            "teacher.customizations.discount.table.info",
            "Visualizzazione da _START_ a _END_ di _TOTAL_ record",
          ),
          infoEmpty: window.cqT(
            "teacher.customizations.discount.table.info_empty",
            "Nessun record disponibile",
          ),
          infoFiltered: window.cqT(
            "teacher.customizations.discount.table.info_filtered",
            "(filtrati da _MAX_ record totali)",
          ),
          zeroRecords: window.cqT(
            "teacher.customizations.discount.table.zero_records",
            "Nessun record trovato",
          ),
        },
      });
    }
  });
})();
