(function () {
  "use strict";

  function initDataTable(selector) {
    if (!(window.jQuery && typeof window.jQuery.fn.DataTable === "function")) {
      return;
    }

    var table = window.jQuery(selector);
    if (!table.length) {
      return;
    }

    table.DataTable({
      pageLength: 25,
      order: [[0, "asc"]],
      language: {
        emptyTable: "No data available.",
        info: "Showed _START_ - _END_ of _TOTAL_ elements",
        infoEmpty: "Showed 0 - 0 of 0 elements",
        infoFiltered: "(filtered on _MAX_ total elements)",
        lengthMenu: "Show _MENU_ elements",
        loadingRecords: "Loading...",
        processing: "Processing...",
        search: "Search:",
        zeroRecords: "No records found",
        paginate: {
          first: "First",
          last: "Last",
          next: "Next",
          previous: "Previous",
        },
      },
    });
  }

  function bindAddTeacherConfirmation() {
    var forms = document.querySelectorAll(".js-add-teacher-form");
    forms.forEach(function (form) {
      form.addEventListener("submit", function (event) {
        var confirmed = window.confirm(
          window.cqT(
            "teacher.add_teacher",
            "Vuoi aggiungere questo docente alla classe selezionata?",
          ),
        );
        if (!confirmed) {
          event.preventDefault();
        }
      });
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    initDataTable("#classTeachersTable");
    initDataTable("#availableTeachersTable");
    bindAddTeacherConfirmation();
  });
})();
