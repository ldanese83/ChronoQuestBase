(function () {
  let deathsQueue = [];
  let currentDeath = null;
  let reloadAfterQueue = false;

  function setupBulkSelection(table) {
    function updateButtons() {
      const selected = table.rows({ selected: true }).count();
      $("#btnMail, #btnCuore, #btnRicompensa").prop("disabled", selected === 0);
    }

    table.on("select deselect", updateButtons);
    updateButtons();
  }

  function getStudentiSelezionati(table) {
    const ids = [];
    table.rows({ selected: true }).every(function () {
      ids.push(parseInt(this.data()[0], 10));
    });
    return ids.filter((id) => !Number.isNaN(id) && id > 0);
  }

  function handleServerResponse(response, fallbackReload) {
    if (!response) {
      window.alert(
        window.cqT(
          "js.server.communication_error",
          "Errore nella risposta del server.",
        ),
      );
      return;
    }

    if (response.status === "error") {
      window.alert(
        response.message ||
          window.cqT("js.operation.failed", "Operazione non riuscita."),
      );
      return;
    }

    if (Array.isArray(response.messages) && response.messages.length > 0) {
      window.alert(response.messages.join("\n"));
    } else if (response.message) {
      window.alert(response.message);
    }

    if (Array.isArray(response.deaths) && response.deaths.length > 0) {
      reloadAfterQueue = true;
      enqueueDeaths(response.deaths);
      return;
    }

    if (fallbackReload) {
      window.location.reload();
    }
  }

  function enqueueDeaths(deaths) {
    deathsQueue = deathsQueue.concat(deaths);
    if (!$("#punizioneModal").hasClass("show")) {
      showNextDeath();
    }
  }

  function showNextDeath() {
    if (deathsQueue.length === 0) {
      currentDeath = null;
      if (reloadAfterQueue) {
        window.location.reload();
      }
      return;
    }

    currentDeath = deathsQueue.shift();
    populatePunizioneModal(currentDeath);
    $("#punizioneModal").modal("show");
  }

  function populatePunizioneModal(death) {
    $("#punizioneStudenteId").val(death.id_studente || 0);
    $("#punizioneStudenteNome").text(
      [death.nome || "", death.cognome || ""].join(" ").trim(),
    );
    $("#nuovaDescrizionePunizione").val("");
    $("#nuoviGiorniConsegna").val("");
    $("#nuovaImgPunizione").val("");

    const select = $("#existingPunizioneSelect");
    select.empty();

    const hasPunishments =
      Array.isArray(death.punizioni) && death.punizioni.length > 0;

    if (hasPunishments) {
      death.punizioni.forEach((pun) => {
        const daysLabel = window.cqT(
          "teacher.dashboard.death_punishment.days_short",
          "gg",
        );
        const label = `${pun.descrizione_punizione} (${pun.giorni_per_consegna} ${daysLabel})`;
        select.append(new Option(label, pun.id_punizione));
      });
      $("#modalitaRandom, #modalitaExisting").prop("disabled", false);
      $("#modalitaRandom").prop("checked", true);
    } else {
      select.append(
        new Option(
          window.cqT(
            "teacher.dashboard.death_punishment.no_available",
            "Nessuna punizione disponibile",
          ),
          "",
        ),
      );
      $("#modalitaRandom, #modalitaExisting").prop("disabled", true);
      $("#modalitaNew").prop("checked", true);
    }

    updatePunizioneSections();
  }

  function updatePunizioneSections() {
    const mode = $("input[name='modalita_punizione']:checked").val();

    if (mode === "existing") {
      $("#existingPunizioneWrapper").removeClass("d-none");
      $("#nuovaPunizioneWrapper").addClass("d-none");
    } else if (mode === "new") {
      $("#existingPunizioneWrapper").addClass("d-none");
      $("#nuovaPunizioneWrapper").removeClass("d-none");
    } else {
      $("#existingPunizioneWrapper, #nuovaPunizioneWrapper").addClass("d-none");
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    if (
      typeof window.jQuery === "undefined" ||
      !$.fn.DataTable ||
      !$("#dataTable").length
    ) {
      return;
    }

    const table = $("#dataTable").DataTable({
      pageLength: 25,
      lengthMenu: [
        [25, 50, 100, -1],
        [25, 50, 100, window.cqT("js.filter.all", "Tutti")],
      ],
      columnDefs: [{ targets: 0, visible: false }],
      select: {
        style: "multi",
      },
    });

    setupBulkSelection(table);

    $("#btnMail").on("click", function () {
      const ids = getStudentiSelezionati(table);
      if (ids.length === 0) {
        return;
      }
      window.location.href =
        "/docenti/messages/new-bulk?studenti=" +
        encodeURIComponent(ids.join(","));
    });

    $("#btnCuore").on("click", function () {
      const ids = getStudentiSelezionati(table);
      if (ids.length === 0) {
        return;
      }

      $("#id_studente").val("0");
      $("#bulkHeartStudentIds").val(ids.join(","));
      $("#nome_tc").text(
        window.cqT("js.toselected.students", "agli studenti selezionati"),
      );
      $("#cognome_tc").text("");
      $("#motivazione").val("");
      $("#togliCuoreModal").modal("show");
    });

    $(document).on("click", ".js-open-heart-modal", function (event) {
      event.preventDefault();
      const button = $(this);
      $("#id_studente").val(button.data("student-id"));
      $("#bulkHeartStudentIds").val("");
      $("#nome_tc").text(button.data("student-name"));
      $("#cognome_tc").text(button.data("student-surname"));
      $("#motivazione").val("");
      $("#togliCuoreModal").modal("show");
    });

    $(document).on("click", ".js-instant-death", function (event) {
      event.preventDefault();
      const button = $(this);
      const studentId = parseInt(button.data("student-id"), 10);
      const fullName = [
        button.data("student-name"),
        button.data("student-surname"),
      ]
        .join(" ")
        .trim();

      const confirmMessage = window
        .cqT(
          "teacher.dashboard.instant_death.confirm",
          "Sei sicuro di voler far morire istantaneamente lo studente %s?",
        )
        .replace("%s", fullName);

      if (!window.confirm(confirmMessage)) {
        return;
      }

      fetch("/docenti/dashboard/morte-istantanea", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: new URLSearchParams({
          id_studente: String(studentId),
        }).toString(),
      })
        .then((response) => response.json())
        .then((response) => handleServerResponse(response, false))
        .catch(() =>
          window.alert(
            window.cqT(
              "teacher.dashboard.instant_death.error",
              "Errore durante il salvataggio della morte istantanea.",
            ),
          ),
        );
    });

    $("#btnRicompensa").on("click", function () {
      const ids = getStudentiSelezionati(table);
      if (ids.length === 0) {
        return;
      }

      $("#ricompensaStudentIds").val(ids.join(","));
      $("#ricompensaXp").val("");
      $("#ricompensaMonete").val("");
      $("#ricompensaMotivazione").val("");
      $("#ricompensaModal").modal("show");
    });

    $("#salvaRicompensa").on("click", function () {
      const ids = ($("#ricompensaStudentIds").val() || "")
        .split(",")
        .map((value) => parseInt(value, 10))
        .filter((value) => !Number.isNaN(value) && value > 0);
      const xp = parseInt($("#ricompensaXp").val(), 10) || 0;
      const monete = parseInt($("#ricompensaMonete").val(), 10) || 0;
      const motivazione = ($("#ricompensaMotivazione").val() || "").trim();

      if (ids.length === 0) {
        window.alert(
          window.cqT(
            "js.students.none_selected",
            "Nessuno studente selezionato.",
          ),
        );
        return;
      }
      if (xp <= 0 && monete <= 0) {
        window.alert(
          window.cqT(
            "dash.what",
            "Inserisci almeno un valore tra XP o monete.",
          ),
        );
        return;
      }
      if (!motivazione) {
        window.alert(
          window.cqT(
            "dash.give_reward_why",
            "Inserisci una motivazione per la ricompensa.",
          ),
        );
        return;
      }

      const body = new URLSearchParams();
      ids.forEach((id) => body.append("ids[]", String(id)));
      body.append("xp", String(xp));
      body.append("monete", String(monete));
      body.append("motivazione", motivazione);

      fetch("/docenti/dashboard/ricompensa-multipla", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: body.toString(),
      })
        .then((response) => response.json())
        .then((response) => {
          if (response.status === "success") {
            window.alert(response.message);
            $("#ricompensaModal").modal("hide");
            window.location.reload();
            return;
          }
          window.alert(
            response.message ||
              window.cqT(
                "dash.give_reward_error",
                "Errore durante l'invio della ricompensa.",
              ),
          );
        })
        .catch(() =>
          window.alert(
            window.cqT(
              "dash.give_reward_error",
              "Errore durante l'invio della ricompensa.",
            ),
          ),
        );
    });

    $("#salvaCuore").on("click", function () {
      const studentId = parseInt($("#id_studente").val(), 10) || 0;
      const bulkIds = ($("#bulkHeartStudentIds").val() || "")
        .split(",")
        .map((value) => parseInt(value, 10))
        .filter((value) => !Number.isNaN(value) && value > 0);
      const motivazione = ($("#motivazione").val() || "").trim();

      if (studentId <= 0 && bulkIds.length === 0) {
        window.alert(
          window.cqT(
            "js.students.none_selected",
            "Nessuno studente selezionato",
          ),
        );
        return;
      }
      if (!motivazione) {
        window.alert(
          window.cqT("common.give_reason", "Inserisci una motivazione"),
        );
        return;
      }

      const body = new URLSearchParams();
      body.append("msg", motivazione);

      let endpoint = "/docenti/dashboard/togli-cuore";
      if (bulkIds.length > 0) {
        endpoint = "/docenti/dashboard/togli-cuore-multiplo";
        bulkIds.forEach((id) => body.append("ids[]", String(id)));
      } else {
        body.append("id_studente", String(studentId));
        body.append("motivazione", motivazione);
      }

      fetch(endpoint, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: body.toString(),
      })
        .then((response) => response.json())
        .then((response) => {
          $("#togliCuoreModal").modal("hide");
          if (bulkIds.length > 0) {
            table.rows({ selected: true }).deselect();
          }
          handleServerResponse(response, true);
        })
        .catch(() =>
          window.alert(
            window.cqT(
              "dash.removeheart_error",
              "Errore durante il salvataggio della perdita cuore.",
            ),
          ),
        );
    });

    $(document).on(
      "change",
      "input[name='modalita_punizione']",
      updatePunizioneSections,
    );

    $("#salvaPunizioneMorte").on("click", function () {
      if (!currentDeath) {
        return;
      }

      const mode = $("input[name='modalita_punizione']:checked").val();
      const formData = new FormData();
      formData.append("id_studente", String(currentDeath.id_studente || 0));
      formData.append("modalita", mode);

      if (mode === "existing") {
        const punishmentId = $("#existingPunizioneSelect").val();
        if (!punishmentId) {
          window.alert(
            window.cqT(
              "dash.select_punishment",
              "Seleziona una punizione da assegnare.",
            ),
          );
          return;
        }
        formData.append("id_punizione", punishmentId);
      } else if (mode === "new") {
        const description = (
          $("#nuovaDescrizionePunizione").val() || ""
        ).trim();
        const days = parseInt($("#nuoviGiorniConsegna").val(), 10) || 0;
        if (!description || days <= 0) {
          window.alert(
            window.cqT(
              "dash.insert_punishment",
              "Inserisci descrizione e giorni per la consegna.",
            ),
          );
          return;
        }
        formData.append("descrizione_punizione", description);
        formData.append("giorni_per_consegna", String(days));
        const file = $("#nuovaImgPunizione")[0].files[0];
        if (file) {
          formData.append("file", file);
        }
      }

      fetch("/docenti/dashboard/assegna-punizione-morte", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((response) => {
          if (response.status === "error") {
            window.alert(
              response.message ||
                window.cqT(
                  "dash.error_punishment_saving",
                  "Errore nel salvataggio della punizione.",
                ),
            );
            return;
          }
          window.alert(
            response.message ||
              window.cqT(
                "dash.punishment_assigned",
                "Punizione assegnata con successo.",
              ),
          );
          currentDeath = null;
          $("#punizioneModal").modal("hide");
        })
        .catch(() =>
          window.alert(
            window.cqT(
              "dash.error_punishment_saving",
              "Errore nel salvataggio della punizione.",
            ),
          ),
        );
    });

    $("#punizioneModal").on("hidden.bs.modal", function () {
      if (currentDeath) {
        deathsQueue.unshift(currentDeath);
        currentDeath = null;
      }

      if (deathsQueue.length > 0) {
        showNextDeath();
      } else if (reloadAfterQueue) {
        window.location.reload();
      }
    });
  });
})();
