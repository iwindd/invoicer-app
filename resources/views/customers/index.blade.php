@extends('layouts.app')

@section('heading')
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ __('nav.customers') }}</h1>
    <a href="#" class="sm-3 btn btn-sm btn-primary shadow-sm" data-toggle="modal"
      data-target="#create"><i class="fas fa-plus fa-sm text-white-50"></i> {{ __('customer.create') }}</a>
  </div>
@endsection

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">{{ __('customer.table') }}</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>{{ __('customer.joinedAt') }}</th>
              <th>{{ __('customer.firstname') }} {{ __('customer.lastname') }}</th>
              <th></th>
              <th>{{ __('customer.createdBy') }}</th>
              <th>{{ __('ui.actions') }}</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@section('modals')
  <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('ui.dialogHeaderAdd') }}</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="#" method="post" id="create-form">
            <div class="form-row">
              <div class="form-group col-md-6">
                <input type="text" class="form-control" name="firstname" placeholder="{{ __('customer.firstname') }}"
                  required>
                <div class="invalid-feedback" id="firstname-feedback"></div>
              </div>
              <div class="form-group col-md-6">
                <input type="text" class="form-control" name="lastname" placeholder="{{ __('customer.lastname') }}"
                  required>
                <div class="invalid-feedback" id="lastname-feedback"></div>
              </div>
            </div>
            <div class="form-group">
              <input type="email" class="form-control" name="email" placeholder="{{ __('auth.email') }}" required>
              <div class="invalid-feedback" id="email-feedback"></div>
            </div>
            <div class="form-group">
              <small class="form-text text-muted"> {{ __('customer.joinedAt') }} </small>
              <input type="date" class="form-control" id="joinedAt" name="joinedAt" required>
              <div class="invalid-feedback" id="joinedAt-feedback"></div>
            </div>
          </form>
          <div class="alert alert-danger" style="display: none;" id="create-alert" role="alert">{{ __('ui.error') }}
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('ui.dialogCancel') }}</button>
          <button class="btn btn-primary" type="submit" form="create-form">{{ __('ui.dialogConfirm') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script type="text/javascript">
    $(document).ready(function(){
      $("#dataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('customers') }}",
        order: [[0, 'desc']],
        columns: [
          { data: 'joinedAt', name: 'joinedAt', render: ff.date},
          { data: 'firstname', name: 'firstname', render: (_, __, row) => `${row.firstname} ${row.lastname}`},
          { data: 'lastname', name: 'lastname', visible: false},
          { data: 'owner', name: 'owner', orderable: false, searchable: false, render: (data) => data.name},
          { data: 'action', name: 'action', orderable: false},
        ]
      }) 
    })
  </script>
  <script type="text/javascript">
    $("#create-form").submit(function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      $('input.is-invalid').removeClass("is-invalid");
      $('#create-alert').hide();
      $('#create button[type="submit"]').attr('disabled', true);
      console.log(Toast);
      $.ajax({
        type: "POST",
        url: "{{ route('customers') }}",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: (data) => {
          $('form#create-form').trigger("reset");
          $('#create').modal('hide');
          $('#create button[type="submit"]').attr('disabled', false);
          Toast.fire({ icon: "success", title: "{{__('ui.added')}}" });
        },
        error: (data) => {
          $('#create button[type="submit"]').attr('disabled', false);
          if (data.status == 422) {
            const response = data.responseJSON;
            for (const [key, value] of Object.entries(response.errors)) {
              const input = $(`input[name="${key}"]`);
              const feedback = $(`#${key}-feedback`);
              input.addClass("is-invalid");
              feedback.html(value)
            }
          } else {
            $('#create-alert').show();
          }
        }
      })
    })
  </script>
@endsection
