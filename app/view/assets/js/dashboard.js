function adjustCount(elementId, step) {
  var elem = document.getElementById(elementId);
  if (!elem) return;

  var currentVal = parseFloat(elem.value) || 0;
  var newVal = currentVal + step;

  if (newVal < 0) {
    newVal = 0;
  }

  elem.value = newVal % 1 === 0 ? newVal : newVal.toFixed(1);
}

function toggleMealInputs(toggleCheckbox) {
  var inputs = ["bfCount", "lunchCount", "dinCount"];
  for (var i = 0; i < inputs.length; i++) {
    var el = document.getElementById(inputs[i]);
    if (el) {
      el.disabled = !toggleCheckbox.checked;
    }
  }
}
