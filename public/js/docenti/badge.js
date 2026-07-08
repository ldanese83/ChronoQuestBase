(function () {
  function showAlert(type, message) {
    const alertBox = $("#badge-management-alert");
    alertBox
      .removeClass("d-none alert-success alert-danger alert-warning alert-info")
      .addClass("alert alert-" + type)
      .text(
        message ||
          window.cqT("js.operation.completed", "Operazione completata."),
      );

    window.scrollTo({ top: 0, behavior: "smooth" });
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

  function loadTopics(selectSelector, subjectId, selectedTopicId) {
    const topicSelect = $(selectSelector);
    topicSelect.html('<option value="0">Caricamento argomenti...</option>');

    if (!subjectId || parseInt(subjectId, 10) <= 0) {
      topicSelect.html('<option value="0">Tutti gli argomenti</option>');
      return Promise.resolve();
    }

    return fetch(
      "/docenti/badge/api/materie/" + parseInt(subjectId, 10) + "/argomenti",
      {
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      },
    )
      .then(handleJsonResponse)
      .then(function (payload) {
        if (!payload.success) {
          topicSelect.html('<option value="0">Errore nel caricamento</option>');
          return;
        }

        let options = '<option value="0">All arguments</option>';
        (payload.topics || []).forEach(function (topic) {
          const isSelected =
            parseInt(selectedTopicId, 10) === parseInt(topic.id_argomento, 10)
              ? " selected"
              : "";
          options +=
            '<option value="' +
            topic.id_argomento +
            '"' +
            isSelected +
            ">" +
            escapeHtml(topic.nome_argomento || "") +
            "</option>";
        });

        topicSelect.html(options);
      });
  }

  function loadModalTopics(subjectId, selectedTopicId) {
    const topicSelect = $("#badgeTopic");

    if (!subjectId || parseInt(subjectId, 10) <= 0) {
      topicSelect.html('<option value="">Seleziona prima la materia</option>');
      return Promise.resolve();
    }

    topicSelect.html('<option value="">Caricamento...</option>');

    return fetch(
      "/docenti/badge/api/materie/" + parseInt(subjectId, 10) + "/argomenti",
      {
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      },
    )
      .then(handleJsonResponse)
      .then(function (payload) {
        if (!payload.success) {
          topicSelect.html('<option value="">Error on loading</option>');
          return;
        }

        let options = '<option value="">Select topic</option>';
        (payload.topics || []).forEach(function (topic) {
          const isSelected =
            parseInt(selectedTopicId, 10) === parseInt(topic.id_argomento, 10)
              ? " selected"
              : "";
          options +=
            '<option value="' +
            topic.id_argomento +
            '"' +
            isSelected +
            ">" +
            escapeHtml(topic.nome_argomento || "") +
            "</option>";
        });
        topicSelect.html(options);
      });
  }

  function escapeHtml(value) {
    return String(value)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#39;");
  }

  function renderTopicResolutionModal(payload, onConfirm) {
    if ($("#badgeTopicResolutionModal").length) {
      $("#badgeTopicResolutionModal").remove();
    }

    let html =
      '<div class="modal fade" id="badgeTopicResolutionModal" tabindex="-1" role="dialog" aria-hidden="true">';
    html +=
      '<div class="modal-dialog modal-xl" role="document"><div class="modal-content">';
    html +=
      '<div class="modal-header"><h5 class="modal-title">Badge\'s topics resolution</h5>';
    html +=
      '<button class="close" type="button" data-dismiss="modal"><span>&times;</span></button></div>';
    html += '<div class="modal-body">';

    (payload.missing_topics || []).forEach(function (topic) {
      html += '<div class="border rounded p-2 mb-3">';
      html += "<strong>" + escapeHtml(topic.nome || "Topic") + "</strong>";
      if (topic.uuid) {
        html +=
          '<div><small class="text-muted">UUID: ' +
          escapeHtml(topic.uuid) +
          "</small></div>";
      }

      html += '<label class="mt-2">Select destination topic</label>';
      html +=
        '<select class="form-control js-topic-resolution" data-topic-key="' +
        escapeHtml(topic.key) +
        '">';
      html += '<option value="0">Select...</option>';
      (payload.available_topics || []).forEach(function (existing) {
        html +=
          '<option value="' +
          parseInt(existing.id_argomento, 10) +
          '">' +
          escapeHtml(existing.nome_materia || "") +
          " / " +
          escapeHtml(existing.nome_argomento || "") +
          "</option>";
      });
      html += "</select></div>";
    });

    html += '</div><div class="modal-footer">';
    html +=
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
    html +=
      '<button type="button" class="btn btn-primary" id="confirmBadgeTopicResolution">Confirm import</button>';
    html += "</div></div></div></div>";
    $("body").append(html);

    $("#badgeTopicResolutionModal").modal("show");

    $("#confirmBadgeTopicResolution").on("click", function () {
      const resolution = {};
      let valid = true;
      $(".js-topic-resolution").each(function () {
        const value = parseInt($(this).val(), 10) || 0;
        const topicKey = String($(this).data("topic-key") || "");
        if (value <= 0) {
          valid = false;
          return;
        }
        resolution[topicKey] = { topic_id: value };
      });

      if (!valid) {
        window.alert("Select a destination topic for all the elements.");
        return;
      }

      $("#badgeTopicResolutionModal").modal("hide");
      $("#badgeTopicResolutionModal").on("hidden.bs.modal", function () {
        $(this).remove();
      });
      onConfirm(resolution);
    });
  }

  function setCreateMode() {
    $("#badgeFormModalLabel").text("Add badge");
    $("#badgeId").val("0");
    $("#badgeName").val("");
    $("#badgeDescription").val("");
    $("#badgeExercises").val("1");
    $("#badgeAverage").val("6");
    $("#badgeGender").val("U");
    $("#badgeImage").val("");
    $("#badgeSubject").val("");
    $("#badgeTopic").html('<option value="">Select the subject first</option>');
  }

  function setEditMode(button) {
    $("#badgeFormModalLabel").text("Modifiy badge");
    $("#badgeId").val(button.data("badge-id"));
    $("#badgeName").val(button.data("badge-name"));
    $("#badgeDescription").val(button.data("badge-description"));
    $("#badgeExercises").val(button.data("badge-exercises"));
    $("#badgeAverage").val(button.data("badge-average"));
    $("#badgeGender").val(button.data("badge-gender") || "U");
    $("#badgeImage").val("");

    const subjectId = button.data("badge-subject-id");
    const topicId = button.data("badge-topic-id");
    $("#badgeSubject").val(subjectId);

    loadModalTopics(subjectId, topicId);
  }

  document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.jQuery === "undefined") {
      return;
    }

    if ($.fn.DataTable && $("#badgeTable").length) {
      $("#badgeTable").DataTable({
        pageLength: parseInt($("#badgeTable").data("page-length"), 10) || 25,
        lengthMenu: [
          [25, 50, 100, -1],
          [25, 50, 100, window.cqT("js.filter.all", "Tutti")],
        ],
        order: [[0, "asc"]],
        language: {
          search: "Search:",
          lengthMenu: "Show _MENU_ badge",
          info: "Display from _START_ to _END_ of _TOTAL_ badge",
          infoEmpty: "No badge available",
          infoFiltered: "(filtered from _MAX_ total badge)",
          zeroRecords: "No badge found",
          paginate: {
            first: "First",
            last: "Last",
            next: "Next",
            previous: "Previous",
          },
        },
      });
    }

    $("#badgeSubjectFilter").on("change", function () {
      const selectedTopicId =
        parseInt($("#badgeSubjectFilter").data("selected-topic"), 10) || 0;
      loadTopics("#badgeTopicFilter", $(this).val(), selectedTopicId);
    });

    $("#openCreateBadgeModal").on("click", function () {
      setCreateMode();
      $("#badgeFormModal").modal("show");
    });
    $("#openImportExportBadgeModal").on("click", function () {
      $("#badgeImportExportModal").modal("show");
    });

    $("#exportBadgeForm").on("submit", function (event) {
      event.preventDefault();
      const subjectId = parseInt($("#exportBadgeSubjectSelect").val(), 10) || 0;
      if (subjectId <= 0) {
        window.alert("Select a subject to export.");
        return;
      }
      window.location.href = "/docenti/badge/export/" + subjectId;
    });

    $("#importBadgeForm").on("submit", function (event) {
      event.preventDefault();
      const formData = new FormData(this);
      const fileInput = $(this).find('input[name="badge_archive"]').get(0);
      if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        window.alert("Select a ZIP archive to import.");
        return;
      }

      const submitImport = function (resolution) {
        if (resolution) {
          formData.set("topic_resolution", JSON.stringify(resolution));
        }

        fetch("/docenti/badge/import-file", {
          method: "POST",
          headers: { "X-Requested-With": "XMLHttpRequest" },
          body: formData,
        })
          .then(handleJsonResponse)
          .then(function (payload) {
            if (payload.requires_topic_resolution) {
              renderTopicResolutionModal(payload, submitImport);
              return;
            }
            if (!payload.success) {
              showAlert("danger", payload.message || "Import failed.");
              return;
            }
            $("#badgeImportExportModal").modal("hide");
            showAlert("success", payload.message || "Import completed.");
            setTimeout(function () {
              window.location.reload();
            }, 1000);
          })
          .catch(function () {
            showAlert("danger", "Error on badges import.");
          });
      };

      submitImport(null);
    });

    $(document).on("click", ".js-edit-badge", function () {
      setEditMode($(this));
      $("#badgeFormModal").modal("show");
    });

    $("#badgeSubject").on("change", function () {
      loadModalTopics($(this).val(), 0);
    });

    $("#badgeForm").on("submit", function (event) {
      event.preventDefault();

      const badgeId = parseInt($("#badgeId").val(), 10) || 0;
      if (!$("#badgeTopic").val()) {
        showAlert("warning", "Select a topic.");
        return;
      }

      if (badgeId === 0) {
        const fileInput = $("#badgeImage").get(0);
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
          showAlert("warning", "Upload an image for the new badge.");
          return;
        }
      }

      const formData = new FormData(this);
      $("#saveBadgeButton").prop("disabled", true);

      fetch("/docenti/badge/save", {
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

          $("#badgeFormModal").modal("hide");
          showAlert("success", payload.message || "Badge saved.");
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        })
        .catch(function () {
          showAlert("danger", "Error on badge saving.");
        })
        .finally(function () {
          $("#saveBadgeButton").prop("disabled", false);
        });
    });

    $(document).on("click", ".js-delete-badge", function () {
      const badgeId = parseInt($(this).data("badge-id"), 10) || 0;
      const badgeName = $(this).data("badge-name") || "questo badge";

      if (
        !window.confirm('Do you really want to delete "' + badgeName + '"?')
      ) {
        return;
      }

      fetch("/docenti/badge/" + badgeId + "/delete", {
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

          showAlert("success", payload.message || "Badge deleted.");
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        })
        .catch(function () {
          showAlert("danger", "Error on badge deleting.");
        });
    });
  });
})();
