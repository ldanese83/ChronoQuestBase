(function () {
  const FLAG_WIDTH = 60;
  const FLAG_HEIGHT = 60;
  const FLAG_OFFSET_X = 15;
  const FLAG_OFFSET_Y = 45;
  const FLAG_IMAGE_SRC = "/assets/images/bandiera_rossa.png";

  function showAlert(type, message) {
    const alertBox = $("#chapter-management-alert");
    alertBox
      .removeClass("d-none alert-success alert-danger alert-warning alert-info")
      .addClass("alert alert-" + type)
      .text(
        message ||
          window.cqT("js.operation.completed", "Operazione completata."),
      );

    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  function parseJson(response) {
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

  function drawFlag(ctx, image, chapter) {
    const drawX = Number(chapter.x || 0) - FLAG_OFFSET_X;
    const drawY = Number(chapter.y || 0) - FLAG_OFFSET_Y;

    ctx.drawImage(image, drawX, drawY, FLAG_WIDTH, FLAG_HEIGHT);
    ctx.fillStyle = "white";
    ctx.font = "bold 12px Arial";
    ctx.fillText(String(chapter.progressive || ""), drawX + 25, drawY + 25);

    return {
      id: Number(chapter.id || 0),
      x: drawX,
      y: drawY,
      width: FLAG_WIDTH,
      height: FLAG_HEIGHT,
    };
  }

  document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.jQuery === "undefined") {
      return;
    }

    const canvas = document.getElementById("immagine_piantina");
    if (!canvas) {
      return;
    }

    const mapSrc = canvas.dataset.mapSrc || "";
    const createUrl = canvas.dataset.createUrl || "";
    const questData = window.questMapData || { chapters: [], questId: 0 };
    const chapterBoxes = [];
    const ctx = canvas.getContext("2d", { alpha: false });
    let pendingChapterCoords = null;
    let addModeEnabled = false;

    $("#openChapterListButton").on("click", function () {
      window.location.href =
        "/docenti/quest/" + Number(questData.questId || 0) + "/capitoli";
    });

    $("#addChapterButton").on("click", function () {
      addModeEnabled = true;
      showAlert(
        "info",
        window.cqT(
          "js.new.chapter",
          "Clicca sulla piantina per aggiungere un nuovo capitolo.",
        ),
      );
    });

    const mapImage = new Image();
    const flagImage = new Image();

    function redrawMap() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      const canvasWidth = canvas.width;
      const canvasHeight = canvas.height;
      const scale = Math.min(
        canvasWidth / mapImage.width,
        canvasHeight / mapImage.height,
      );
      const newWidth = mapImage.width * scale;
      const newHeight = mapImage.height * scale;
      const offsetX = (canvasWidth - newWidth) / 2;
      const offsetY = (canvasHeight - newHeight) / 2;

      ctx.drawImage(mapImage, offsetX, offsetY, newWidth, newHeight);

      chapterBoxes.length = 0;
      (questData.chapters || []).forEach(function (chapter) {
        chapterBoxes.push(drawFlag(ctx, flagImage, chapter));
      });
    }

    function openCreateModal(x, y) {
      pendingChapterCoords = { x: x, y: y };
      $("#chapterCoordXInput").val(String(x));
      $("#chapterCoordYInput").val(String(y));
      $("#chapterNameInput").val("");
      $("#addChapterModal").modal("show");
    }

    canvas.addEventListener("click", function (event) {
      const rect = canvas.getBoundingClientRect();
      const mouseX = Math.ceil(event.clientX - rect.left);
      const mouseY = Math.ceil(event.clientY - rect.top);

      if (addModeEnabled) {
        addModeEnabled = false;
        openCreateModal(mouseX, mouseY);
        return;
      }

      const hit = chapterBoxes.find(function (box) {
        return (
          mouseX >= box.x &&
          mouseX <= box.x + box.width &&
          mouseY >= box.y &&
          mouseY <= box.y + box.height
        );
      });

      if (hit) {
        window.location.href =
          "/docenti/quest/" +
          Number(questData.questId || 0) +
          "/capitoli/" +
          Number(hit.id || 0);
      }
    });

    $("#addChapterForm").on("submit", function (event) {
      event.preventDefault();

      const chapterName = String($("#chapterNameInput").val() || "").trim();
      const progressive =
        parseInt($("#chapterProgressiveInput").val(), 10) || 0;
      const coordX = pendingChapterCoords
        ? pendingChapterCoords.x
        : parseInt($("#chapterCoordXInput").val(), 10);
      const coordY = pendingChapterCoords
        ? pendingChapterCoords.y
        : parseInt($("#chapterCoordYInput").val(), 10);

      if (chapterName === "") {
        showAlert(
          "warning",
          window.cqT(
            "teacher.quest.map.chapter_name_required",
            "Inserisci il nome del capitolo.",
          ),
        );
        return;
      }

      $("#saveChapterButton").prop("disabled", true);

      const body = new URLSearchParams();
      body.append("nome_capitolo", chapterName);
      body.append("coord_x", String(coordX));
      body.append("coord_y", String(coordY));
      body.append("progressivo", String(progressive));

      fetch(createUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: body.toString(),
      })
        .then(parseJson)
        .then(function (payload) {
          if (!payload.success) {
            showAlert(
              "danger",
              payload.message ||
                window.cqT(
                  "teacher.quest.map.chapter_save_failed",
                  "Salvataggio del capitolo non riuscito.",
                ),
            );
            return;
          }

          if (payload.redirectUrl) {
            window.location.href = payload.redirectUrl;
            return;
          }

          $("#addChapterModal").modal("hide");
          showAlert(
            "success",
            payload.message ||
              window.cqT(
                "teacher.quest.map.chapter_created",
                "Capitolo creato correttamente.",
              ),
          );
        })
        .catch(function () {
          showAlert(
            "danger",
            window.cqT(
              "teacher.quest.map.chapter_save_unexpected",
              "Errore imprevisto durante il salvataggio del capitolo.",
            ),
          );
        })
        .finally(function () {
          $("#saveChapterButton").prop("disabled", false);
        });
    });

    mapImage.onload = function () {
      flagImage.onload = redrawMap;
      flagImage.src = FLAG_IMAGE_SRC;
    };

    mapImage.onerror = function () {
      showAlert(
        "danger",
        window.cqT(
          "teacher.quest.map.load_failed",
          "Impossibile caricare la piantina della quest.",
        ),
      );
    };

    mapImage.src = mapSrc;
  });
})();
