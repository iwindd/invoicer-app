const Toast = Swal.mixin({
  toast: true,
  position: "bottom-end",
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  }
});

const Confirmation = Swal.mixin({
  title: "{{ __('ui.dialogHeader') }}",
  customClass: {
    confirmButton: "btn shadow-sm btn-primary mr-1",
    cancelButton: "btn shadow-sm btn-secondary ml-1",
    icon: "text-warning border-warning"
  },
  showCancelButton: true,
  buttonsStyling: false,
  cancelButtonText: "{{ __('ui.dialogCancel') }}",
  confirmButtonText: "{{ __('ui.dialogConfirm') }}",
  icon: "warning",
});

const Alert = {
  success: Swal.mixin({
    title: "{{ __('ui.dialogHeaderSuccess') }}",
    customClass: {
      confirmButton: "btn shadow-sm btn-primary mr-1",
    },
    buttonsStyling: false,
    confirmButtonText: "{{ __('ui.dialogConfirm') }}",
    icon: "success",
  })
}