(function () {
  function byId(id) { return document.getElementById(id); }

  function setLayer(id, src) {
    const el = byId(id);
    if (!el) return;
    el.src = src || '';
    el.style.display = src ? 'block' : 'none';
  }

  window.cambia_sfondo = function (id, img) {
    byId('sfondo_scelto').value = id;
    setLayer('sfondo_pers', img);
  };

  window.cambia_bigsfondo = function (id, img) {
    byId('bigsfondo_scelto').value = id;
    setLayer('big_sfondo', img);
  };

  window.cambia_capelli = function (id, img) {
    byId('capelli_scelti').value = id;
    setLayer('capelli_pers', img);
  };

  window.cambia_personale = function (id, img) {
    byId('personale_scelto').value = id;
    setLayer('personale_pers', img);
  };

  window.cambia_pet = function (id, img) {
    byId('id_pet').value = id;
    setLayer('pet', img);
  };

  window.salva_personalizzazione = function () {
    const form = byId('personalizationForm');
    if (form) form.submit();
  };

  window.vendi_personalizzazione = function (idPersonalizzazione) {
    if (!confirm(window.cqT('student.customization.js.confirm_sell', 'Sei sicuro di voler vendere questa personalizzazione?'))) return;
    const form = byId('sellCustomizationForm');
    const input = byId('sell_customization_id');
    if (!form || !input) return;
    input.value = String(idPersonalizzazione || 0);
    form.submit();
  };
})();
