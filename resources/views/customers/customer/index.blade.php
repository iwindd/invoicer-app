@extends('layouts.app')

@section('heading')
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $customer['firstname'] }} {{ $customer['lastname'] }}</h1>
    <div class="d-flex ">
      <div class="dropdown show mr-1">
        <a class="sm-3 btn btn-sm btn-secondary shadow-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{ __('ui.etc') }}
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          <a class="dropdown-item" href="#"><i
              class="fas fa-link mr-2 text-secondary"></i>{{ __('invoice.api') }}</a>
          <a class="dropdown-item" href="#"><i
              class="fas fa-cogs mr-2 text-info"></i>{{ __('invoice.application') }}</a>
          <button class="dropdown-item" id="delete"><i class="fas fa-trash mr-2 text-danger"></i>ลบ</button>
        </div>
      </div>

      {{--       <a href="#" class="sm-3 btn btn-sm btn-info shadow-sm" ><i
        class="fas fa-cogs fa-sm text-white-50"></i> {{ __('invoice.application') }}</a> --}}
      <a href="#" class="sm-3 btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i>
        {{ __('invoice.create') }}</a>
    </div>
  </div>
@endsection

@section('content')
  <!-- Content Row -->
  <div class="row">
    <!-- ALL INVOICES -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow  py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                {{ __('invoice.type-all') }}</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-receipt fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- INVOICE SUCCESS -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow  py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                {{ __('invoice.type-success') }}</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- INVOICE PROCESS -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow  py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                {{ __('invoice.type-process') }}</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- INVOICE OVERTIME -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-danger shadow  py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                {{ __('invoice.type-overtime') }}</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-times fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">{{ __('invoice.table') }}</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>{{ __('invoice.id') }}</th>
              <th>{{ __('invoice.status') }}</th>
              <th>{{ __('invoice.note') }}</th>
              <th>{{ __('invoice.value') }}</th>
              <th>{{ __('invoice.start') }}</th>
              <th>{{ __('invoice.end') }}</th>
              <th>{{ __('invoice.createdBy') }}</th>
              <th>{{ __('ui.actions') }}</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('customer.customer') }}</h6>
          <a href="#" class="sm-3 btn btn-sm btn-secondary shadow-sm"><i
              class="fas fa-sync fa-sm text-white-50"></i>
            {{ __('ui.line-connect') }}</a>
        </div>
        <div class="card-body">
          <form action="#" method="post" id="edit-form">
            <input type="hidden" name="id" value="{{ $customer['id'] }}">
            <div class="form-row">
              <div class="form-group col-md-6">
                <small class="form-text text-muted"> {{ __('customer.firstname') }} </small>
                <input type="text" class="form-control" disabled name="firstname" value="{{ $customer['firstname'] }}"
                  placeholder="{{ __('customer.firstname') }}" required>
                <div class="invalid-feedback" id="firstname-feedback"></div>
              </div>
              <div class="form-group col-md-6">
                <small class="form-text text-muted"> {{ __('customer.lastname') }} </small>
                <input type="text" class="form-control" disabled name="lastname" value="{{ $customer['lastname'] }}"
                  placeholder="{{ __('customer.lastname') }}" required>
                <div class="invalid-feedback" id="lastname-feedback"></div>
              </div>
            </div>
            <div class="form-group">
              <small class="form-text text-muted"> {{ __('auth.email') }} </small>
              <input type="email" class="form-control" disabled name="email" value="{{ $customer['email'] }}"
                placeholder="{{ __('auth.email') }}" required>
              <div class="invalid-feedback" id="email-feedback"></div>
            </div>
            <div class="form-group">
              <small class="form-text text-muted"> {{ __('customer.joinedAt') }} </small>
              <input type="date" class="form-control" disabled value="{{ $customer['joinedAt'] }}" id="joinedAt"
                name="joinedAt" required>
              <div class="invalid-feedback" id="joinedAt-feedback"></div>
            </div>
          </form>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <button href="#" class="btn btn-secondary editMode mr-2" id="cancel-edit-profile"
            style="display: none"><i
              class="fas fa-times fa-sm text-white-50 mr-2"></i>{{ __('ui.dialogCancel') }}</button>
          <button href="#" class="btn btn-success editMode" type="submit" form="edit-form"
            style="display: none"><i class="fas fa-save fa-sm text-white-50 mr-2"></i>{{ __('ui.save') }}</button>
          <button class="btn btn-primary" id="edit-profile"><i
              class="fas fa-pen fa-sm text-white-50 mr-2"></i>{{ __('ui.edit-btn') }}</button>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('invoice.stats') }}</h6>
        </div>
        <div class="card-body">
          <h4 class="small font-weight-bold">{{ __('invoice.invoice') }}{{ __('invoice.type-success') }}<span
              class="float-right">50%</span></h4>
          <div class="progress mb-4">
            <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="50"
              aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <h4 class="small font-weight-bold">{{ __('invoice.invoice') }}{{ __('invoice.type-process') }}<span
              class="float-right">50%</span></h4>
          <div class="progress mb-4">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50"
              aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <h4 class="small font-weight-bold">{{ __('invoice.invoice') }}{{ __('invoice.type-overtime') }}<span
              class="float-right">50%</span></h4>
          <div class="progress mb-4">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 50%" aria-valuenow="50"
              aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <h4 class="small font-weight-bold">{{ __('invoice.invoice') }}{{ __('invoice.type-checking') }}<span
              class="float-right">50%</span></h4>
          <div class="progress mb-4">
            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50"
              aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <h4 class="small font-weight-bold">{{ __('invoice.invoice') }}{{ __('invoice.type-cancel') }}<span
              class="float-right">50%</span></h4>
          <div class="progress mb-4">
            <div class="progress-bar bg-secondary" role="progressbar" style="width: 50%" aria-valuenow="50"
              aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('modals')
@endsection

@section('scripts')
  <script type="text/javascript">
    $("#delete").on("click", () => {
      Confirmation.fire({
        text: `{{ __('ui.delete', ['text' => ':name']) }}`.replace(":name", name),
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async () => {
          try {
            const resp = await $.ajax({
              url: "{{ route('customers') }}",
              type: 'DELETE',
              data: {
                id: "{{$customer['id']}}"
              }
            });
            return true;
          } catch (error) {
            console.error(error);
            return Swal.showValidationMessage(`{{ __('ui.error') }}`);
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          Alert.success.fire({
            text: `{{ __('ui.deleted', ['text' => ':name']) }}`.replace(":name", name),
          }).then(() => {
            window.location = "{{ route('customers') }}"
          })
        }
      });
    })
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $("#edit-profile").on('click', () => {
        $('#edit-profile').hide();
        $('#edit-form input').attr('disabled', false)
        $('#edit-form input')[0].focus();
        $('button.editMode').show();
      })

      $("#cancel-edit-profile").on('click', () => {
        $('#edit-profile').show();
        $('button.editMode').hide();
        $('#edit-form input').attr('disabled', true)
      })
    })

    $('#edit-form').submit(function(e) {
      e.preventDefault();
      $('#edit-form input.is-invalid').removeClass("is-invalid");

      Confirmation.fire({
        text: "{{ __('ui.edit', ['text' => $customer['firstname'] . ' ' . $customer['lastname']]) }}",
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async () => {
          try {
            const resp = await $.ajax({
              url: "{{ route('customers') }}",
              type: 'PUT',
              data: JSON.stringify({
                id: $("#edit-form input[name='id']").val(),
                firstname: $("#edit-form input[name='firstname']").val(),
                lastname: $("#edit-form input[name='lastname']").val(),
                email: $("#edit-form input[name='email']").val(),
                joinedAt: $("#edit-form input[name='joinedAt']").val(),
              }),
              contentType: 'application/json',
            });

            return true;
          } catch (error) {
            try {
              if (error.status == 422) {
                const response = error.responseJSON;
                for (const [key, value] of Object.entries(response.errors)) {
                  const input = $(`input[name="${key}"]`);
                  const feedback = $(`#${key}-feedback`);
                  input.addClass("is-invalid");
                  feedback.html(value)
                }

                return 422;
              }
            } catch (error) {
              console.error(error);
            }
            return Swal.showValidationMessage(`{{ __('ui.error') }}`);
          }
        }
      }).then((result) => {
        if (result.value == 422) return
        if (result.isConfirmed) {
          Alert.success.fire({
            text: "{{ __('ui.edited', ['text' => $customer['firstname'] . ' ' . $customer['lastname']]) }}",
          });
          $('#edit-profile').show();
          $('button.editMode').hide();
          $('#edit-form input').attr('disabled', true)
        }
      });
    })
  </script>
@endsection
