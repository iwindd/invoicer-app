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
              <th>{{ __('invoice.table')}}</th>
              <th style="display: none"></th>
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
              <input type="date" class="form-control" id="joined_at" name="joined_at" required>
              <div class="invalid-feedback" id="joined_at-feedback"></div>
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
    const RefreshTable = () => {
      const Table = $("#dataTable").dataTable();
      Table.fnDraw(false);
    }
    
    const delFunc = (id, name) => {
      Confirmation.fire({
        text: `{{__('ui.delete', ['text' => ':name'])}}`.replace(":name", name),
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async () => {
          try {
            const resp = await $.ajax({ url: "{{ route('customers') }}", type: 'DELETE', data: {id}});
            return true;
          } catch (error) {
            console.error(error);
            return Swal.showValidationMessage(`{{__("ui.error")}}`);
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          RefreshTable();
          Alert.success.fire({
            text: `{{__('ui.deleted', ['text' => ':name'])}}`.replace(":name", name),
          }); 
        }
      });
    }

    $(document).ready(function(){
      $("#dataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('customers') }}",
        order: [[0, 'desc']],
        columns: [
          { data: 'joined_at', name: 'joined_at', render: ff.date},
          { data: 'firstname', name: 'firstname', render: (_, __, row) => `${row.firstname} ${row.lastname}`},
          { data: 'invoices', name: 'invoicesData', orderable: false, searchable: false, render: (data) => {
            const process = data.filter(i => ff.invoice(i) == 3).length;
            const checking = data.filter(i => ff.invoice(i) == 2).length;
            const overtime = data.filter(i => ff.invoice(i) == 4).length;
            const waiting = data.filter(i => ff.invoice(i) == 0).length;

            const returnData =  (`
              ${ process > 0 ? (` <span class="text-warning mr-2"><i class="fas fa-hourglass-half"></i> : ${process}</span>`):"" }
              ${ checking > 0 ? (` <span class="text-primary mr-2"><i class="fas fa-search"></i> : ${checking}</span>`):"" }
              ${ overtime > 0 ? (` <span class="text-danger mr-2"><i class="fas fa-times"></i> : ${overtime}</span>`):"" }
              ${ waiting > 0 ? (` <span class="text-info mr-2"><i class="fas fa-calendar"></i> : ${waiting}</span>`):"" }
            `).trim();

            return returnData ? returnData : "<span class='text-muted'>ไม่พบข้อมูล</span>"
          }},
          { data: 'lastname', name: 'lastname', visible: false},
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
          RefreshTable();
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
