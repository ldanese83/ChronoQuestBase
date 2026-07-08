(function () {
  function showAlert(type, message) {
    const alertBox = $("#quest-management-alert");
    alertBox
      .removeClass("d-none alert-success alert-danger alert-warning alert-info")
      .addClass("alert alert-" + type)
      .text(
        message ||
          window.cqT("js.operation.completed", "Operazione completata."),
      );

    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  function setCreateMode() {
    $("#questFormModalLabel").text("Inserisci nuova quest");
    $("#questId").val("0");
    $("#questName").val("");
    $("#questImage").val("");
    $("#questMap").val("");
    $("#questLockMode").val("1");
  }

  function setEditMode(button) {
    $("#questFormModalLabel").text("Modifica quest");
    $("#questId").val(button.data("quest-id"));
    $("#questName").val(button.data("quest-name") || "");
    $("#questImage").val("");
    $("#questMap").val("");
    $("#questLockMode").val(String(button.data("quest-blocca") || "1"));
  }

  function handleJsonResponse(response) {
    return response.json().catch(function () {
      return {
        success: false,
        message: window.cqT(
          "js.response.invalid",
          "Risposta non valida dal server.",
        ),
      };
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.jQuery === "undefined") {
      return;
    }

    if ($.fn.DataTable && $("#questTable").length) {
      $("#questTable").DataTable({
        pageLength: parseInt($("#questTable").data("page-length"), 10) || 10,
        lengthMenu: [
          [10, 25, 50, 100, -1],
          [10, 25, 50, 100, window.cqT("js.filter.all", "Tutti")],
        ],
        order: [[0, "asc"]],
        language: {
          search: "Search:",
          lengthMenu: "Show _MENU_ quest",
          info: "Display from _START_ to _END_ of _TOTAL_ quest",
          infoEmpty: "No quest available",
          infoFiltered: "(filtered from _MAX_ total quest)",
          zeroRecords: "No quest found",
          paginate: {
            first: "First",
            last: "Last",
            next: "Next",
            previous: "Previous",
          },
        },
      });
    }

    setCreateMode();

    $("#openCreateQuestModal").on("click", function () {
      setCreateMode();
      $("#questFormModal").modal("show");
    });

    $(document).on("click", ".js-edit-quest", function () {
      setEditMode($(this));
      $("#questFormModal").modal("show");
    });

    $("#questForm").on("submit", function (event) {
      event.preventDefault();

      const questId = parseInt($("#questId").val(), 10) || 0;
      const questName = String($("#questName").val() || "").trim();
      const imageInput = $("#questImage").get(0);
      const mapInput = $("#questMap").get(0);

      if (questName === "") {
        showAlert("warning", "Insert quest name.");
        return;
      }

      if (
        questId === 0 &&
        (!imageInput || !imageInput.files || imageInput.files.length === 0)
      ) {
        showAlert("warning", "Upload the quest image.");
        return;
      }

      if (
        questId === 0 &&
        (!mapInput || !mapInput.files || mapInput.files.length === 0)
      ) {
        showAlert("warning", "Upload the quest map.");
        return;
      }

      const formData = new FormData();
      formData.append("id_quest", String(questId));
      formData.append("nome_quest", questName);
      formData.append("blocca_ese", String($("#questLockMode").val() || "1"));

      if (imageInput && imageInput.files && imageInput.files.length > 0) {
        formData.append("image_quest", imageInput.files[0]);
      }

      if (mapInput && mapInput.files && mapInput.files.length > 0) {
        formData.append("piantina_quest", mapInput.files[0]);
      }

      $("#saveQuestButton").prop("disabled", true);

      fetch("/docenti/quest/save", {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
      })
        .then(handleJsonResponse)
        .then(function (payload) {
          if (!payload.success) {
            showAlert(
              "danger",
              payload.message ||
                window.cqT("js.save.failed", "Salvataggio non riuscito."),
            );
            return;
          }

          $("#questFormModal").modal("hide");
          showAlert("success", payload.message || "Quest saved.");
          setTimeout(function () {
            window.location.reload();
          }, 900);
        })
        .catch(function () {
          showAlert("danger", "Error on quest saving.");
        })
        .finally(function () {
          $("#saveQuestButton").prop("disabled", false);
        });
    });

    $(document).on("click", ".js-delete-quest", function () {
      const id = $(this).data("quest-id");
      const name = $(this).data("quest-name") || "this quest";

      if (
        !window.confirm(
          'Do you really want to delete the quest "' + name + '" from class?',
        )
      ) {
        return;
      }

      fetch("/docenti/quest/" + id + "/delete", {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then(handleJsonResponse)
        .then(function (payload) {
          if (!payload.success) {
            showAlert(
              "danger",
              payload.message ||
                window.cqT("js.delete.failed", "Eliminazione non riuscita."),
            );
            return;
          }

          showAlert("success", payload.message || "Quest deleted.");
          setTimeout(function () {
            window.location.reload();
          }, 900);
        })
        .catch(function () {
          showAlert("danger", "Error on quest deleting.");
        });
    });
  });
})();
