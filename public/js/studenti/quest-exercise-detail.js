var MappaRisp = new Map();
window.Dropzone.autoDiscover = false;

function seleziona_risp(id_domanda, id_risposta) {
  var dom = document.getElementById("rispdom_" + id_domanda);
  if (dom) {
    dom.value = id_risposta;
  }

  var valore = MappaRisp.get(id_domanda);
  if (valore !== undefined) {
    var oldIcona = document.getElementById("icona_risp" + valore);
    if (oldIcona) {
      oldIcona.className = "fa fa-angle-right";
      var oldRow = oldIcona.closest(".quiz-risposta");
      if (oldRow) {
        oldRow.classList.remove("selected");
      }
    }
  }

  var icona = document.getElementById("icona_risp" + id_risposta);
  if (icona) {
    icona.className = "fa fa-check";
    var row = icona.closest(".quiz-risposta");
    if (row) {
      row.classList.add("selected");
    }
  }

  MappaRisp.set(id_domanda, id_risposta);
}

(function () {
  document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("form_capitolo");
    if (!form) {
      return;
    }

    if (window.tinymce && document.getElementById("testo_risposta")) {
      window.tinymce.init({
        selector: "#testo_risposta",
        menubar: false,
        plugins: "lists link image table code help wordcount",
        toolbar:
          "undo redo | blocks | bold italic underline | bullist numlist | link | removeformat | code",
        height: 320,
        branding: false,
        license_key: "gpl",
      });
    }

    var initialSelections = document.querySelectorAll('input[id^="rispdom_"]');
    initialSelections.forEach(function (el) {
      var domandaId = (el.id || "").replace("rispdom_", "");
      var rispostaId = parseInt(el.value, 10);
      if (domandaId !== "" && rispostaId > 0) {
        MappaRisp.set(parseInt(domandaId, 10), rispostaId);
      }
    });

    form.addEventListener("submit", function (event) {
      window.tinymce?.triggerSave();

      var campo = document.getElementById("num_crocette");
      if (!campo) {
        return;
      }

      var num = parseInt(campo.value || "0", 10);
      if (num > 0 && MappaRisp.size !== num) {
        event.preventDefault();
        alert(
          window.cqT(
            "student.quest.exercise.complete_multiple_choice",
            "Prego completare tutte le domande a risposta multipla",
          ),
        );
      }
    });

    if (typeof window.Dropzone === "undefined") {
      return;
    }

    var dropzoneEl = document.querySelector(".js-exercise-dropzone");
    if (!dropzoneEl) {
      return;
    }

    if (dropzoneEl.dropzone) {
      return;
    }

    var submitUrl = dropzoneEl.getAttribute("data-url");
    if (!submitUrl) {
      return;
    }

    var dz = new window.Dropzone(dropzoneEl, {
      url: submitUrl,
      paramName: "file",
      uploadMultiple: true,
      parallelUploads: 5,
      maxFiles: 5,
      autoProcessQueue: false,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      addRemoveLinks: true,
      clickable: true,
      dictDefaultMessage: window.cqT(
        "js.upload.drag_drop_multiple",
        "Trascina i file qui o clicca per aggiungerli",
      ),
      dictMaxFilesExceeded: window.cqT(
        "student.quest.exercise.upload.max_files_exceeded",
        "Puoi caricare al massimo 5 file",
      ),
    });

    form.addEventListener("submit", function (event) {
      window.tinymce?.triggerSave();

      if (dz.files.length === 0) {
        return;
      }
      event.preventDefault();
      dz.processQueue();
    });

    dz.on("sending", function (_file, _xhr, formData) {
      var elements = form.elements;
      for (var i = 0; i < elements.length; i += 1) {
        var element = elements[i];
        if (!element.name) {
          continue;
        }
        formData.append(element.name, element.value);
      }
    });

    function reloadAfterDropzoneUpload(response) {
      if (response && typeof response === "object" && response.redirectUrl) {
        window.location.href = response.redirectUrl;
        return;
      }

      if (typeof response === "string" && response.trim() !== "") {
        try {
          var parsed = JSON.parse(response);
          if (parsed && parsed.redirectUrl) {
            window.location.href = parsed.redirectUrl;
            return;
          }
        } catch (_error) {
          // Fall through to the normal reload when the server returns HTML.
        }
      }

      var xhr = dz.files.length > 0 ? dz.files[dz.files.length - 1].xhr : null;
      if (xhr && xhr.responseURL) {
        window.location.href = xhr.responseURL;
        return;
      }
      window.location.reload();
    }

    dz.on("successmultiple", function (_files, response) {
      reloadAfterDropzoneUpload(response);
    });

    dz.on("error", function () {
      window.location.reload();
    });
  });
})();
