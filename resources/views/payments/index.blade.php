@extends('layouts.app')

@section('heading')
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ __('nav.payments') }}</h1>
    <a href="#" class="sm-3 btn btn-sm btn-primary shadow-sm" id="create-form-btn"><i
        class="fas fa-plus fa-sm text-white-50"></i> {{ __('payment.create') }}</a>
  </div>
@endsection

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">{{ __('payment.table') }}</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>{{ __('payment.status') }}</th>
              <th>{{ __('payment.account') }}</th>
              <th>{{ __('payment.title') }}</th>
              <th>{{ __('payment.name') }}</th>
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
                <input type="text" class="form-control" name="title" placeholder="{{ __('payment.title') }}"
                  required>
                <div class="invalid-feedback" id="title-feedback"></div>
              </div>
              <div class="form-group col-md-6">
                <input type="text" class="form-control" name="account" placeholder="{{ __('payment.account') }}"
                  required>
                <div class="invalid-feedback" id="account-feedback"></div>
              </div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="name" placeholder="{{ __('payment.name') }}" required>
              <div class="invalid-feedback" id="name-feedback"></div>
            </div>
            <div class="form-check">
              <input class="form-check-input" name="use" type="checkbox" value="" id="flexCheckChecked" checked>
              <label class="form-check-label" for="flexCheckChecked">
                {{ __('payment.use') }}
              </label>
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
    let id = 0;
    let isUpdate = 0;
    const RefreshTable = () => {
      const Table = $("#dataTable").dataTable();
      Table.fnDraw(false);
    }

    const editFunc = (id_, title, account, name) => {
      id = id_;
      isUpdate = 1;
      $('#create-form [name="title"]').val(title);
      $('#create-form [name="account"]').val(account);
      $('#create-form [name="name"]').val(name);
      $('#create-form .form-check').hide();
      $('#create .modal-title').html('{{ __('ui.edit-btn') }}');
      $('#create').modal('show');
    }

    const delFunc = (id, name) => {
      Confirmation.fire({
        text: `{{ __('ui.delete', ['text' => ':name']) }}`.replace(":name", name),
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async () => {
          try {
            const resp = await $.ajax({
              url: "{{ route('payments') }}",
              type: 'DELETE',
              data: {
                id
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
          RefreshTable();
          Alert.success.fire({
            text: `{{ __('ui.deleted', ['text' => ':name']) }}`.replace(":name", name),
          });
        }
      });
    }

    const patchFunc = (id, name) => {
      Confirmation.fire({
        text: `{{ __('payment.active_confirmation', ['text' => ':name']) }}`.replace(":name", name),
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async () => {
          try {
            const resp = await $.ajax({
              url:  "{{ route('payment', ['id' => ':id']) }}".replace(":id", id),
              type: 'PATCH',
              data: {
                id
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
          RefreshTable();
          Alert.success.fire({
            text: `{{ __('payment.active_success', ['text' => ':name']) }}`.replace(":name", name),
          });
        }
      });
    }

    $(document).ready(function() {
      $("#dataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('payments') }}",
        order: [
          [0, 'desc']
        ],
        columns: [{
            data: 'active',
            name: 'active',
            render: isActive => isActive ? "<span class='text-primary'>{{ __('payment.active') }}</span>" :
              "{{ __('payment.deactive') }}"
          },
          {
            data: 'account',
            name: 'account',
          },
          {
            data: 'title',
            name: 'title',
          },
          {
            data: 'account',
            name: 'account',
          },
          {
            data: 'action',
            name: 'action',
            orderable: false
          },
        ]
      })
    })
  </script>

  <script type="text/javascript">
    $('#create-form-btn').on('click', () => {
      isUpdate = 0;
      $('#create .modal-title').html('{{ __('ui.dialogHeaderAdd') }}')
      $('#create-form .form-check').show();
      $('#create').modal('show');

    })
    $("#create-form").submit(function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      formData.append("active", $('#create-form input[name="use"]').is(":checked") ? '1' : '0')
      validation.clear('#create-form');

      if (isUpdate == 0) {
        $.ajax({
          type: "POST",
          url: "{{ route('payments') }}",
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          success: (data) => {
            $('form#create-form').trigger("reset");
            $('#create').modal('hide');
            validation.clear('#create-form', false);
            RefreshTable();
            Toast.fire({
              icon: "success",
              title: "{{ __('ui.added') }}"
            });
          },
          error: (error) => {
            if (!validation.error(error)) {
              $('#create-alert').show();
            }
          }
        })
      } else {
        $.ajax({
          url: "{{ route('payment', ['id' => ':id']) }}".replace(":id", id),
          type: 'PUT',
          data: JSON.stringify({
            title: $('#create-form [name="title"]').val(),
            name: $('#create-form [name="name"]').val(),
            account: $('#create-form [name="account"]').val(),
          }),
          contentType: 'application/json',
          success: (data) => {
            $('form#create-form').trigger("reset");
            $('#create').modal('hide');
            validation.clear('#create-form', false);
            RefreshTable();
            Toast.fire({
              icon: "success",
              title: "{{ __('ui.edited', ['text' => __('payment.payment')]) }}"
            });
          },
          error: (error) => {
            if (!validation.error(error)) {
              $('#create-alert').show();
            }
          }
        })

      }
    })
  </script>
@endsection
