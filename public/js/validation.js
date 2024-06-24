const validation = {}

validation.clear = (scope, disable = true) => {
  $(`${scope} .is-invalid`).removeClass("is-invalid");
  $(`${scope} .is-valid`).removeClass("is-is-valid");
  $(`${scope} .was-validated`).removeClass("is-was-validated");
  $(`${scope} [type="submit"]`).attr('disabled', disable);
}

validation.error = (scope, error) => {
  $(`${scope} [type="submit"]`).attr('disabled', false);
  if (!error) return;

  if (error?.status == 422) {
    for (const [key, value] of Object.entries(error.responseJSON.errors)) {
      let input = $(`${scope} [name="${key}"]`);
      if (input.attr('data-validate')) {
        input = $(`${input.attr('data-validate')}`)
      }
      const feedback = $(`${scope} #${key}-feedback`);
      input.addClass("is-invalid");
      feedback.html(value)
    }

    return true
  }
}