@extends('layouts.app')

@section('heading')
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ __('nav.profile') }}</h1>
    <a href="#" class="sm-3 btn btn-sm btn-primary shadow-sm" id="change-password-btn"><i
        class="fas fa-pen fa-sm text-white-50"></i> {{ __('profile.change-password-btn') }}</a>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-6 col-md-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('profile.info') }}</h6>
        </div>
        <div class="card-body">
          <form action="#" id="edit-profile">
            <div class="form-group row">
              <label for="colFormLabelSm"
                class="col-sm-2 col-form-label col-form-label-sm">{{ __('profile.name') }}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="colFormLabelSm"
                  placeholder="{{ __('profile.name') }}" name="name" value="{{ Auth::user()->name }}">
                <div class="invalid-feedback" id="name-feedback"></div>
              </div>
            </div>
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">{{ __('auth.email') }}</label>
              <div class="col-sm-10">
                <input type="email" class="form-control form-control-sm" id="colFormLabelSm"
                  placeholder="{{ __('auth.email') }}" disabled>
              </div>
            </div>
            <div class="form-group row">
              <label for="colFormLabelSm"
                class="col-sm-2 col-form-label col-form-label-sm">{{ __('ui.created_at') }}</label>
              <div class="col-sm-10">
                <span data-format="date">{{ Auth::user()->created_at }}</span>
              </div>
            </div>
          </form>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <button class="btn btn-success" form="edit-profile" type="submit">{{ __('ui.save') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('modals')
  <div class="modal fade" id="change-password" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('profile.change-password-btn') }}</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="change-password-form">
            <div class="form-group">
              <small class="form-text text-muted"> {{ __('profile.newpassword') }}</small>
              <input type="password" class="form-control" name="password" placeholder="{{ __('profile.newpassword') }}"
                required>
              <div class="invalid-feedback" id="password-feedback"></div>
            </div>
            <div class="form-group">
              <small class="form-text text-muted"> {{ __('profile.newpasswordconfirm') }}</small>
              <input type="password" class="form-control" name="password_confirmation"
                placeholder="{{ __('profile.newpasswordconfirm') }}" required>
              <div class="invalid-feedback" id="password_confirmation-feedback"></div>
            </div>
            <div class="form-group">
              <small class="form-text text-muted"> {{ __('profile.oldpassword') }}</small>
              <input type="password" class="form-control" name="password_old"
                placeholder="{{ __('profile.oldpassword') }}" required>
              <div class="invalid-feedback" id="password_old-feedback"></div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('ui.dialogCancel') }}</button>
          <button class="btn btn-primary" type="submit"
            form="change-password-form">{{ __('ui.dialogConfirm') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $("#edit-profile").submit(function(e) {
      e.preventDefault();

      Confirmation.fire({
        text: `{{ __('profile.edit-confirmation') }}`,
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),

        preConfirm: async () => {
          try {
            const resp = await $.ajax({
              url: "{{ route('profile') }}",
              type: 'PUT',
              data: {
                name: $('#edit-profile input[name="name"]').val()
              }
            });
            return true;
          } catch (error) {
            return Swal.showValidationMessage(`{{ __('ui.error') }}`);
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          Alert.success.fire({
            text: `{{ __('profile.edit-success') }}`,
          });
        }
      });
    })
  </script>

  <script>
    const modal = $('#change-password');
    $("#change-password-btn").on('click', () => {
      modal.modal('show');
    })

    $('#change-password-form').submit(function(e) {
      e.preventDefault();
      validation.clear("#change-password");
      $.ajax({
        url: "{{ route('profile') }}",
        type: 'PATCH',
        data: JSON.stringify({
          password: $("#change-password-form input[name='password']").val(),
          password_confirmation: $("#change-password-form input[name='password_confirmation']").val(),
          password_old: $("#change-password-form input[name='password_old']").val(),
        }),
        contentType: 'application/json',
        success: (data) => {
          $('#change-password-form').trigger("reset");
          modal.modal('hide');
          validation.clear("#change-password", false);
          Toast.fire({
            icon: "success",
            title: "{{ __('profile.change-password-success') }}"
          });
        },
        error: (error) => {
          if (!validation.error("#change-password", error)) {
            console.error(error);
          }
        }
      });
    })
  </script>
@endsection
