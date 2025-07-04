const forms = elements().whereClass("form-eliminar");
const buttonConfirm = _find("#confirm-button");
const confirmacion = _find(".confirmacion-popup");
const buttonCancel = _find("#cancel-button");

let ACTUAL_FORM = null;

forms.event("submit", function (e) {
  e.preventDefault();
  confirmacion.show("flex");
  ACTUAL_FORM = e.target;
});

buttonConfirm.when("click").make(function () {
  ACTUAL_FORM.submit();
  confirmacion.hide();
});

buttonCancel.when("click").make(function () {
  confirmacion.hide();
});
