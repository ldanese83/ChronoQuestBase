(function () {
  function showAlert(type, message, details) {
    const alertBox = $("#student-management-alert");
    const detailItems =
      Array.isArray(details) && details.length
        ? '<ul class="mb-0 mt-2"><li>' +
          details.map(escapeHtml).join("</li><li>") +
          "</li></ul>"
        : "";

    alertBox
      .removeClass("d-none alert-success alert-danger alert-warning alert-info")
      .addClass("alert alert-" + type)
      .html(
        "<div>" +
          escapeHtml(message || "") +
          "</div>" +
          detailItems +
          '<div><a class="btn btn-primary" style="margin-top:2rem" href="#" onclick="javascript:window.location.reload()">' +
          window.cqT(
            "js.students.reload_changes",
            "Ricarica per vedere le modifiche",
          ) +
          "</a></div>",
      );

    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  function escapeHtml(value) {
    return String(value || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function inferUsername(name, surname) {
    const normalizedName = (name || "").replace(/[^a-z0-9]/gi, "");
    const normalizedSurname = (surname || "").replace(/[^a-z0-9]/gi, "");
    const username = (
      (normalizedName.charAt(0) || "") + normalizedSurname
    ).toLowerCase();
    return username || "studente";
  }

  function setCreateMode() {
    $("#studentFormModalLabel").text(
      window.cqT("js.students.add", "Aggiungi studente"),
    );
    $("#studentId").val("0");
    $("#studentName").val("");
    $("#studentSurname").val("");
    $("#studentEmail").val("").prop("disabled", false).prop("required", true);
    $("#studentUsername").val("");
    $("#studentGender").val("M");
    $("#studentPei").val("0");
    $("#studentPassword").val("");
    $("#studentEmailHelp").removeClass("d-none");
    $("#studentPasswordHelp").text(
      window.cqT(
        "js.students.password.default_generated",
        "Di default viene inserito lo username generato",
      ),
    );
  }

  function setEditMode(button) {
    $("#studentFormModalLabel").text(
      window.cqT("js.students.edit", "Modifica studente"),
    );
    $("#studentId").val(button.data("student-id"));
    $("#studentName").val(button.data("student-name"));
    $("#studentSurname").val(button.data("student-surname"));
    $("#studentEmail")
      .val(button.data("student-email"))
      .prop("disabled", true)
      .prop("required", false);
    $("#studentUsername").val(button.data("student-username"));
    $("#studentGender").val(button.data("student-gender"));
    $("#studentPei").val(button.data("student-l104"));
    $("#studentPassword").val("");
    $("#studentEmailHelp").addClass("d-none");
    $("#studentPasswordHelp").text(
      window.cqT(
        "js.students.password.leave_empty",
        "Lascia vuoto per non modificare la password corrente.",
      ),
    );
  }

  function refreshUsernamePreview() {
    if ($("#studentId").val() !== "0") {
      return;
    }

    $("#studentUsername").val(
      inferUsername($("#studentName").val(), $("#studentSurname").val()),
    );
  }

  function configureTable() {
    if (!$.fn.DataTable || !$("#studentTable").length) {
      return null;
    }

    return $("#studentTable").DataTable({
      pageLength: parseInt($("#studentTable").data("page-length"), 10) || 25,
      lengthMenu: [
        [25, 50, 100, -1],
        [25, 50, 100, window.cqT("js.filter.all", "Tutti")],
      ],
      order: [
        [0, "asc"],
        [1, "asc"],
      ],
      language: {
        search: "Find:",
        lengthMenu: "Show _MENU_ students",
        info: "Showing from _START_ to _END_ of _TOTAL_ students",
        infoEmpty: window.cqT(
          "js.students.none_available",
          "No student available",
        ),
        infoFiltered: "(filtered from _MAX_ total students)",
        zeroRecords: window.cqT(
          "js.students.none_found",
          "Nessuno studente trovato",
        ),
        paginate: {
          first: "First",
          last: "Last",
          next: "Next",
          previous: "Previous",
        },
      },
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

  document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.jQuery === "undefined") {
      return;
    }

    configureTable();
    setCreateMode();
    refreshUsernamePreview();

    $("#openCreateStudentModal").on("click", function () {
      setCreateMode();
      $("#studentFormModal").modal("show");
    });

    $("#openCsvImportModal").on("click", function () {
      $("#csvImportForm").trigger("reset");
      $("#csvImportModal").modal("show");
    });

    $("#openClassImportModal").on("click", function () {
      $("#classImportForm").trigger("reset");
      $("#sourceClassId").val("0");
      $("#classImportModal").modal("show");
    });

    $("#studentName, #studentSurname").on("input", refreshUsernamePreview);

    $(document).on("click", ".js-edit-student", function () {
      setEditMode($(this));
      $("#studentFormModal").modal("show");
    });

    $(document).on("click", ".js-delete-student", function () {
      const button = $(this);
      $("#deleteStudentId").val(button.data("student-id"));
      $("#deleteStudentName").text(
        [button.data("student-name"), button.data("student-surname")].join(" "),
      );
      $("#deleteStudentModal").modal("show");
    });

    $("#studentForm").on("submit", function (event) {
      event.preventDefault();

      const formData = new URLSearchParams();
      $(this)
        .serializeArray()
        .forEach(function (field) {
          formData.append(field.name, field.value);
        });

      $("#saveStudentButton").prop("disabled", true);

      fetch("/docenti/studenti/save", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: formData.toString(),
      })
        .then(handleJsonResponse)
        .then(function (payload) {
          if (!payload.success) {
            showAlert(
              "danger",
              payload.message ||
                window.cqT("js.save.failed", "Salvataggio non riuscito."),
              payload.details || [],
            );
            return;
          }

          $("#studentFormModal").modal("hide");
          showAlert(
            "success",
            payload.message ||
              window.cqT(
                "js.students.save.success",
                "Studente salvato correttamente.",
              ),
            payload.details || [],
          );
          /*window.setTimeout(function () {
                        window.location.reload();
                    }, 400);*/
        })
        .catch(function () {
          showAlert(
            "danger",
            window.cqT(
              "js.students.save.unexpected_error",
              "Errore imprevisto durante il salvataggio dello studente.",
            ),
          );
        })
        .finally(function () {
          $("#saveStudentButton").prop("disabled", false);
        });
    });

    $("#csvImportForm").on("submit", function (event) {
      event.preventDefault();

      const fileInput = $("#csvFileUpload").get(0);
      if (!fileInput || !fileInput.files || !fileInput.files.length) {
        showAlert(
          "danger",
          window.cqT(
            "js.students.import_csv.select_file",
            "Seleziona prima un file CSV da importare.",
          ),
        );
        return;
      }

      const formData = new FormData();
      formData.append("fileUpload", fileInput.files[0]);

      $("#confirmCsvImportButton").prop("disabled", true);

      fetch("/docenti/studenti/import/csv", {
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
                window.cqT("js.import_csv.failed", "Import CSV non riuscito."),
              payload.details || [],
            );
            return;
          }

          $("#csvImportModal").modal("hide");
          showAlert(
            payload.summary && payload.summary.mail_failed > 0
              ? "warning"
              : "success",
            payload.message ||
              window.cqT("js.import_csv.completed", "Import CSV completato."),
            payload.details || [],
          );
          /*window.setTimeout(function () {
                        window.location.reload();
                    }, 600);*/
        })
        .catch(function () {
          showAlert(
            "danger",
            window.cqT(
              "js.students.import_csv.unexpected_error",
              "Errore imprevisto durante l’import CSV degli studenti.",
            ),
          );
        })
        .finally(function () {
          $("#confirmCsvImportButton").prop("disabled", false);
        });
    });

    $("#classImportForm").on("submit", function (event) {
      event.preventDefault();

      const formData = new URLSearchParams();
      $(this)
        .serializeArray()
        .forEach(function (field) {
          formData.append(field.name, field.value);
        });

      $("#confirmClassImportButton").prop("disabled", true);

      fetch("/docenti/studenti/import/class", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: formData.toString(),
      })
        .then(handleJsonResponse)
        .then(function (payload) {
          if (!payload.success) {
            showAlert(
              "danger",
              payload.message ||
                window.cqT(
                  "js.students.import_class.failed",
                  "Import da classe non riuscito.",
                ),
              payload.details || [],
            );
            return;
          }

          $("#classImportModal").modal("hide");
          showAlert(
            "success",
            payload.message ||
              window.cqT(
                "js.students.import_class.completed",
                "Import da classe completato.",
              ),
            payload.details || [],
          );
          /*window.setTimeout(function () {
                        window.location.reload();
                    }, 600);*/
        })
        .catch(function () {
          showAlert(
            "danger",
            window.cqT(
              "js.students.import_class.unexpected_error",
              "Errore imprevisto durante l’import da altra classe.",
            ),
          );
        })
        .finally(function () {
          $("#confirmClassImportButton").prop("disabled", false);
        });
    });

    $("#confirmDeleteStudentButton").on("click", function () {
      const studentId = $("#deleteStudentId").val();
      if (!studentId || studentId === "0") {
        showAlert("danger", "Studente non valido.");
        return;
      }

      $("#confirmDeleteStudentButton").prop("disabled", true);

      fetch("/docenti/studenti/" + encodeURIComponent(studentId) + "/delete", {
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

          $("#deleteStudentModal").modal("hide");
          showAlert(
            "success",
            payload.message || "Studente eliminato correttamente.",
          );
          /*window.setTimeout(function () {
                        window.location.reload();
                    }, 400);*/
        })
        .catch(function () {
          showAlert(
            "danger",
            window.cqT(
              "js.students.delete.unexpected_error",
              "Errore imprevisto durante l’eliminazione dello studente.",
            ),
          );
        })
        .finally(function () {
          $("#confirmDeleteStudentButton").prop("disabled", false);
        });
    });
  });
})();
