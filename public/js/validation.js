const validation = {}

validation.clear = (scope) => {
  $(`${scope} .is-invalid`).removeClass("is-invalid");
  $(`${scope} .is-valid`).removeClass("is-is-valid");
  $(`${scope} .was-validated`).removeClass("is-was-validated");
  $(`${scope} [type="submit"]`).attr('disabled', true);
}

validation.error = (scope, error) => {
  $(`${scope} [type="submit"]`).attr('disabled', false);

  if (error?.status == 422) {
    for (const [key, value] of Object.entries(error.responseJSON.errors)) {
      const input = $(`${scope} input[name="${key}"]`);
      const feedback = $(`${scope} #${key}-feedback`);
      input.addClass("is-invalid");
      feedback.html(value)
    }

    return true
  }
}