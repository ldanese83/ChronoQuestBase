(function () {
  function showAlert(type, message) {
    const alertBox = $("#team-management-alert");
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
    $("#teamFormModalLabel").text(window.cqT("teams.add", "Aggiungi squadra"));
    $("#teamId").val("0");
    $("#teamName").val("");
    $("#teamType").val("");
    $('input[name="emblema_tipo"][value="upload"]').prop("checked", true);
    $("#teamEmblemUpload").val("");
    $('input[name="default_emblema"]').prop("checked", false);
    $(".js-team-student").prop("checked", false);
    applyStudentAvailability(0);
    toggleEmblemInputs();
  }

  function setEditMode(button) {
    $("#teamFormModalLabel").text(
      window.cqT("teams.edit_modal_title", "Modifica squadra"),
    );
    $("#teamId").val(button.data("team-id"));
    $("#teamName").val(button.data("team-name"));
    $("#teamType").val(button.data("team-type") || "");
    $('input[name="emblema_tipo"][value="keep"]').prop("checked", true);
    $("#teamEmblemUpload").val("");
    $('input[name="default_emblema"]').prop("checked", false);
    $(".js-team-student").prop("checked", false);

    const selectedIds = String(button.data("team-member-ids") || "")
      .split(",")
      .map(function (value) {
        return parseInt(value, 10);
      })
      .filter(function (value) {
        return !Number.isNaN(value) && value > 0;
      });

    applyStudentAvailability(button.data("team-id"));

    selectedIds.forEach(function (id) {
      const checkbox = $('.js-team-student[value="' + id + '"]');
      if (!checkbox.prop("disabled")) {
        checkbox.prop("checked", true);
      }
    });

    toggleEmblemInputs();
  }

  function toggleEmblemInputs() {
    const mode = $('input[name="emblema_tipo"]:checked').val();
    const isDefault = mode === "default";
    const isUpload = mode === "upload";

    $("#teamEmblemUpload").prop("disabled", !isUpload);
    $("#teamDefaultEmblems").toggleClass("d-none", !isDefault);
    $('input[name="default_emblema"]').prop("disabled", !isDefault);
  }

  function applyStudentAvailability(modeTeamId) {
    const activeTeamId = parseInt(modeTeamId, 10) || 0;

    $(".js-team-student-row").each(function () {
      const row = $(this);
      const checkbox = row.find(".js-team-student");
      const currentTeamId = parseInt(checkbox.data("current-team-id"), 10) || 0;

      const visible =
        activeTeamId === 0
          ? currentTeamId === 0
          : currentTeamId === 0 || currentTeamId === activeTeamId;

      row.toggleClass("d-none", !visible);
      checkbox.prop("disabled", !visible);

      if (!visible) {
        checkbox.prop("checked", false);
      }
    });
  }

  function collectSelectedStudents() {
    const selected = [];
    $(".js-team-student:checked").each(function () {
      selected.push(parseInt($(this).val(), 10));
    });

    return selected.filter(function (value) {
      return !Number.isNaN(value) && value > 0;
    });
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

  function postSimple(url) {
    return fetch(url, {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    }).then(handleJsonResponse);
  }

  document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.jQuery === "undefined") {
      return;
    }

    if ($.fn.DataTable && $("#teamTable").length) {
      $("#teamTable").DataTable({
        pageLength: parseInt($("#teamTable").data("page-length"), 10) || 25,
        lengthMenu: [
          [25, 50, 100, -1],
          [25, 50, 100, window.cqT("js.filter.all", "Tutti")],
        ],
        order: [
          [5, "asc"],
          [0, "asc"],
        ],
        language: {
          search: "Search:",
          lengthMenu: "Show _MENU_ teams",
          info: "Displayed from _START_ to _END_ of _TOTAL_ teams",
          infoEmpty: "No team available",
          infoFiltered: "(filtered from _MAX_ total teams)",
          zeroRecords: "No team found",
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

    $("#openCreateTeamModal").on("click", function () {
      setCreateMode();
      $("#teamFormModal").modal("show");
    });

    $(document).on("click", ".js-edit-team", function () {
      setEditMode($(this));
      $("#teamFormModal").modal("show");
    });

    $(document).on("change", 'input[name="emblema_tipo"]', toggleEmblemInputs);

    $(document).on("change", ".js-team-student", function () {
      if (collectSelectedStudents().length > 4) {
        this.checked = false;
        showAlert(
          "warning",
          window.cqT(
            "js.maxteam",
            "Puoi selezionare al massimo 4 studenti per squadra.",
          ),
        );
      }
    });

    $("#teamForm").on("submit", function (event) {
      event.preventDefault();

      const selectedStudents = collectSelectedStudents();
      if (selectedStudents.length > 4) {
        showAlert(
          "warning",
          window.cqT(
            "js.maxteam",
            "Puoi selezionare al massimo 4 studenti per squadra.",
          ),
        );
        return;
      }

      const mode = $('input[name="emblema_tipo"]:checked').val();
      const teamId = parseInt($("#teamId").val(), 10) || 0;
      if (teamId === 0 && mode === "keep") {
        showAlert(
          "danger",
          window.cqT(
            "js.chooseemblem",
            "Per una nuova squadra devi scegliere un emblema.",
          ),
        );
        return;
      }

      const formData = new FormData();
      formData.append("id_squadra", String(teamId));
      formData.append("nome_squadra", $("#teamName").val() || "");
      formData.append("tipologia", $("#teamType").val() || "");
      formData.append("emblema_tipo", mode || "keep");
      formData.append("studenti", JSON.stringify(selectedStudents));

      if (mode === "default") {
        formData.append(
          "default_emblema",
          $('input[name="default_emblema"]:checked').val() || "",
        );
      }

      const uploadInput = $("#teamEmblemUpload").get(0);
      if (
        mode === "upload" &&
        uploadInput &&
        uploadInput.files &&
        uploadInput.files.length > 0
      ) {
        formData.append("team_emblem", uploadInput.files[0]);
      }

      $("#saveTeamButton").prop("disabled", true);

      fetch("/docenti/squadre/save", {
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

          $("#teamFormModal").modal("hide");
          showAlert("success", payload.message || "Team saved.");
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        })
        .catch(function () {
          showAlert("danger", "Error on saving team.");
        })
        .finally(function () {
          $("#saveTeamButton").prop("disabled", false);
        });
    });

    $(document).on("click", ".js-delete-team", function () {
      const id = $(this).data("team-id");
      const name = $(this).data("team-name") || "questa squadra";
      if (!window.confirm('Are you sure you want to delete "' + name + '"?')) {
        return;
      }

      postSimple("/docenti/squadre/" + id + "/delete")
        .then(function (payload) {
          if (!payload.success) {
            showAlert(
              "danger",
              payload.message ||
                window.cqT("js.delete.failed", "Eliminazione non riuscita."),
            );
            return;
          }

          showAlert("success", payload.message || "Team deleted.");
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        })
        .catch(function () {
          showAlert("danger", "Error on deleting team.");
        });
    });

    $(document).on("click", ".js-approve-team", function () {
      const id = $(this).data("team-id");
      postSimple("/docenti/squadre/" + id + "/approve")
        .then(function (payload) {
          if (!payload.success) {
            showAlert("danger", payload.message || "Approvation failed.");
            return;
          }

          showAlert("success", payload.message || "Team approved.");
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        })
        .catch(function () {
          showAlert("danger", "Error on team approvation.");
        });
    });

    $(document).on("click", ".js-reject-team", function () {
      const id = $(this).data("team-id");
      if (
        !window.confirm(
          "Confirm the reject of the team? The team will be deleted.",
        )
      ) {
        return;
      }

      postSimple("/docenti/squadre/" + id + "/reject")
        .then(function (payload) {
          if (!payload.success) {
            showAlert("danger", payload.message || "Reject failed.");
            return;
          }

          showAlert("success", payload.message || "Team rejected.");
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        })
        .catch(function () {
          showAlert("danger", "Error on team rejection.");
        });
    });
  });
})();
