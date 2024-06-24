@extends('layouts.app')

@section('heading')
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ __('nav.applications') }}</h1>
    <button class="sm-3 btn btn-sm btn-primary shadow-sm" id="create-form-btn"><i
        class="fas fa-plus fa-sm text-white-50"></i> {{ __('application.create') }}</button>
  </div>
@endsection

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">{{ __('application.table') }}</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>{{ __('ui.created_at') }}</th>
              <th>{{ __('customer.firstname') }}</th>
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
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('ui.dialogHeaderAdd') }}</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="#" method="post" id="create-application-form">
            <input type="hidden" data-validate="#customer-col .selectize-control" name="id">
            <div class="row">
              <div class="col-sm-12" id="customer-col">
                <small class="form-text text-muted"> {{ __('customer.customer') }} </small>
                <select id="select-tools" class="w-100" placeholder="{{ __('customer.customer') }}"></select>
                <div class="invalid-feedback" id="id-feedback"></div>
              </div>
            </div>
          </form>
          <div class="alert alert-danger" style="display: none;" id="create-alert" role="alert">
            {{ __('ui.error') }}
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('ui.dialogCancel') }}</button>
          <button class="btn btn-primary" type="submit"
            form="create-application-form">{{ __('ui.dialogConfirm') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script type="text/javascript">
    const modal = $('#create');

    const $select = $('#select-tools').selectize({
      maxItems: 1,
      delimiter: " - ",
      valueField: 'id',
      labelField: 'name',
      searchField: 'name',
      options: [],
      create: false,
      onChange: (val) => {
        $('#create-application-form input[name="id"]').val(val);
      }
    });
    const selectize = $select[0].selectize;

    $.ajax({
      url: "{{ route('customers3') }}",
      success: (data) => {
        selectize.clearOptions();
        data = data.map(d => ({
          id: d.id,
          name: `${d.firstname} ${d.lastname}`
        }));
        for (const i in data) {
          selectize.addOption(data[i]);
        }
        selectize.refreshOptions()
      }
    })

    $('#create-form-btn').on('click', () => {
      modal.modal('show');
    })

    $("#create-application-form").submit(function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      validation.clear("#create")
      $.ajax({
        url: "{{ route('applications') }}",
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: (data) => {
          modal.modal('hide');
          validation.clear("#create", false);
          Toast.fire({
            icon: "success",
            title: "{{ __('ui.added') }}"
          });
        },
        error: (error) => {
          if (!validation.error("#create", error)) {
            modal.modal('hide');
            Toast.fire({
              icon: "error",
              title: "{{ __('ui.error') }}"
            });
          }
        }
      });
    })
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      const RefreshTable = () => {
        const Table = $("#dataTable").dataTable();
        Table.fnDraw(false);
      }

      $("#dataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('applications') }}",
        order: [
          [0, 'desc']
        ],
        columns: [
          {
            data: 'created_at',
            name: 'created_at',
            render: ff.date
          },
          {
            data: 'name',
            name: 'name',
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
@endsection
