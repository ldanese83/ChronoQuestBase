(function () {
  window.acquistaEquip = function (idEquip, costo) {
    const message = window.cqT(
      'student.customization.js.confirm_buy_equipment',
      'Sei sicuro di voler acquistare l\'equipaggiamento selezionato per {cost} monete?'
    ).replace('{cost}', costo);

    if (!confirm(message)) {
      return;
    }
    const form = document.getElementById('buyEquipmentForm');
    const input = document.getElementById('buy_equipment_id');
    if (!form || !input) return;
    input.value = String(idEquip || 0);
    form.submit();
  };

  window.equipaggia = function (idEquip) {
    const form = document.getElementById('equipEquipmentForm');
    const input = document.getElementById('equip_equipment_id');
    if (!form || !input) return;
    input.value = String(idEquip || 0);
    form.submit();
  };

  window.vendiEquip = function (idEquip) {
    if (!confirm(window.cqT('student.customization.js.confirm_sell_equipment', 'Sei sicuro di voler vendere questa armatura?'))) {
      return;
    }

    const form = document.getElementById('sellEquipmentForm');
    const input = document.getElementById('sell_equipment_id');
    if (!form || !input) return;
    input.value = String(idEquip || 0);
    form.submit();
  };
})();
