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
          <button class="dropdown-item" id="delete"><i
              class="fas fa-trash mr-2 text-danger"></i>{{ __('ui.delete-btn') }}</button>
        </div>
      </div>

      <a href="#" class="sm-3 btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#create"><i
          class="fas fa-plus fa-sm text-white-50"></i>
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
                  default="{{ $customer['firstname'] }}" placeholder="{{ __('customer.firstname') }}" required>
                <div class="invalid-feedback" id="firstname-feedback"></div>
              </div>
              <div class="form-group col-md-6">
                <small class="form-text text-muted"> {{ __('customer.lastname') }} </small>
                <input type="text" class="form-control" disabled name="lastname"
                  value="{{ $customer['lastname'] }}" default="{{ $customer['lastname'] }}"
                  placeholder="{{ __('customer.lastname') }}" required>
                <div class="invalid-feedback" id="lastname-feedback"></div>
              </div>
            </div>
            <div class="form-group">
              <small class="form-text text-muted"> {{ __('auth.email') }} </small>
              <input type="email" class="form-control" disabled name="email" value="{{ $customer['email'] }}"
                default="{{ $customer['email'] }}" placeholder="{{ __('auth.email') }}" required>
              <div class="invalid-feedback" id="email-feedback"></div>
            </div>
            <div class="form-group">
              <small class="form-text text-muted"> {{ __('customer.joinedAt') }} </small>
              <input type="date" class="form-control" disabled value="{{ $customer['joinedAt'] }}"
                default="{{ $customer['joinedAt'] }}" id="joinedAt" name="joinedAt" required>
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
  <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('ui.dialogHeaderAdd') }}</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="#" method="post" id="create-invoice-form">
            <div class="row">
              <input type="hidden" name="id" value="{{ $customer['id'] }}">
              <div class="col-lg-6 col-md-12">
                <small class="form-text text-muted"> {{ __('invoice.note') }} </small>
                <input type="text" class="form-control" name="note" placeholder="{{ __('invoice.note') }}">
                <div class="invalid-feedback" id="note-feedback"></div>
              </div>
              <div class="col-lg-3 col-md-12">
                <small class="form-text text-muted"> {{ __('invoice.start') }} </small>
                <input type="date" class="form-control" name="start" required>
                <div class="invalid-feedback" id="start-feedback"></div>
              </div>
              <div class="col-lg-3 col-md-12">
                <small class="form-text text-muted"> {{ __('invoice.end') }} </small>
                <input type="date" min="{{ now()->toDateString('Y-m-d') }}" class="form-control" name="end"
                  required>
                <div class="invalid-feedback" id="end-feedback"></div>
              </div>
              <div class="col-sm-12">
                <table id="invoice-items" class="table table-striped mt-2">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">{{ __('invoice.item-name') }}</th>
                      <th scope="col">{{ __('invoice.item-amount') }}</th>
                      <th scope="col">{{ __('invoice.item-value') }}</th>
                      <th scope="col">{{ __('ui.total') }}</th>
                      <th scope="col">{{ __('ui.actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="4" class="text-right">{{ __('ui.total') }}</td>
                      <td colspan="2" id="invoices-all-total" class="text-left">0</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <div class="col-sm-12 d-flex justify-content-end">
                <button id="add-invoice-item" class="sm-3 btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50"></i>
                  {{ __('ui.dialogHeaderAdd') }}</button>
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
            form="create-invoice-form">{{ __('ui.dialogConfirm') }}</button>
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
      
      $(document).ready(function(){
        $("#dataTable").DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('invoice', ['id' => $customer['id']]) }}",
          order: [[0, 'desc']],
          columns: [
            { data: 'id', name: 'id', render: ff.number},
            { data: 'status', name: 'status', orderable: false, render: (_, __, row) => ff.invoice_label(ff.invoice(row), true)},
            { data: 'note', name: 'note', render: ff.text},
            { data: 'items', name: 'items', orderable: false, searchable: false, render: ff.itemsvalue},
            { data: 'start', name: 'start', render: ff.date},
            { data: 'end', name: 'end', render: ff.date},
            { data: 'user', name: 'user', orderable: false, searchable: false, render: data => data.name},
            { data: 'action', name: 'action', orderable: false},
          ],
        }) 
      })
  </script>

  <script type="text/javascript">
    const table = $('#invoice-items tbody');
    const total = $('#invoices-all-total');
    const startInput = $('#create input[name="start"]');
    const endInput = $('#create input[name="end"]');
    let items = [];

    const removeItem = (index) => {
      items = items.filter((_, idx) => idx !== index);
      update()
    }

    const onChange = (key, index) => {
      const val = $(`#${index}-${key}`).val();
      items[index][key] = val;
      total.html(ff.money(items.reduce((total, item) => total + (item.amount * item.value), 0)))
      $(`#${index}-total`).html(ff.money(items[index].amount * items[index].value))
    }

    const update = () => {
      table.html("")
      if (items.length > 0) {
        items.map((item, index) => {
          table.append(`
              <tr>
                <th>${ff.number(index+1)}</th>
                <td><input type="text" id="${index}-name" class="form-control" onkeyup='onChange("name", ${index})' value='${item.name}' placeholder="{{ __('invoice.item-name') }}" required></td>
                <td><input type="number" id="${index}-amount" class="form-control" onchange='onChange("amount", ${index})' onkeyup='onChange("amount", ${index})' value='${item.amount}' min="0" placeholder="{{ __('invoice.item-amount') }}" required></td>
                <td><input type="number" id="${index}-value" class="form-control" onchange='onChange("value", ${index})' onkeyup='onChange("value", ${index})' value='${item.value}' min="0" placeholder="{{ __('invoice.item-value') }}" required></td>
                <td id="${index}-total">${ff.money(item.amount * item.value)}</td>
                <td>
                  <button 
                    class="sm-3 btn btn-sm btn-danger shadow-sm"
                    data-index='${index}'
                    onclick='removeItem(${index})'
                    >
                    <i class="fas fa-trash fa-sm text-white-50"></i>
                      {{ __('ui.delete-btn') }}
                    </button>
                </td>
              </tr>
            `);
        })

        total.html(ff.money(items.reduce((total, item) => total + (item.amount * item.value), 0)))
      } else {
        total.html(ff.money(0))
        table.append(`
            <tr>
              <td colSpan="6" class="text-center">{{ __('ui.noItems') }}</td>
            </tr>
          `)
      }
    }

    $("#add-invoice-item").on("click", () => {
      items.push({
        name: "",
        amount: 0,
        value: 0
      })
      update()
    })

    $('#create-invoice-form').submit(function(e) {
      e.preventDefault();

      validation.clear("#create");
      const payload = JSON.stringify({
        id: $("#create-invoice-form input[name='id']").val(),
        note: $("#create-invoice-form input[name='note']").val(),
        start: startInput.val(),
        end: endInput.val(),
        items: items,
      })

      $.ajax({
        url: "{{ route('invoices') }}",
        type: 'POST',
        data: payload,
        contentType: 'application/json',
        success: (data) => {
          items = []
          $('#create-invoice-form').trigger("reset");
          $('#create').modal('hide');
          validation.clear("#create");
          Toast.fire({
            icon: "success",
            title: "{{ __('ui.added') }}"
          });
          update()
          RefreshTable()
        },
        error: (data) => {
          if (!validation.error("#create", error)) {
            items = []
            $('#create-invoice-form').trigger("reset");
            $('#create').modal('hide');
            Toast.fire({
              icon: "error",
              title: "{{ __('ui.error') }}"
            });
            update()
          }
        }
      });
    })

    startInput.on("change", () => {
      const startVal = startInput.val();
      const endVal = endInput.val();

      if (endVal && startVal && dayjs(startVal).isAfter(dayjs(endVal))) endInput.val(startVal);
      if (startVal) endInput.attr('min', startVal);
    })

    update()
  </script>

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
                id: "{{ $customer['id'] }}"
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
        const inputs = $('#edit-form input');
        $('#edit-profile').show();
        $('button.editMode').hide();
        inputs.attr('disabled', true);
        inputs.map((ele) => {
          const input = $(inputs[ele]);
          input.val(input.attr('default'))
        })
      })
    })

    $('#edit-form').submit(function(e) {
      e.preventDefault();
      validation.clear('#edit-form')
      const payload = {
        id: $("#edit-form input[name='id']").val(),
        firstname: $("#edit-form input[name='firstname']").val(),
        lastname: $("#edit-form input[name='lastname']").val(),
        email: $("#edit-form input[name='email']").val(),
        joinedAt: $("#edit-form input[name='joinedAt']").val(),
      }

      Confirmation.fire({
        text: "{{ __('ui.edit', ['text' => $customer['firstname'] . ' ' . $customer['lastname']]) }}",
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async () => {
          try {
            const resp = await $.ajax({
              url: "{{ route('customer', ['id' => $customer['id']]) }}",
              type: 'PUT',
              data: JSON.stringify(payload),
              contentType: 'application/json',
            });

            for (const [key, value] of Object.entries(payload)){
              if (key != 'id'){
                $(`#edit-form input[name="${key}"]`).attr('default', value)
              }
            }

            $('h1').html(`${payload.firstname} ${payload.lastname}`);

            return true;
          } catch (error) {
            try {
              if (validation.error('#edit-form', error)) return 422;
            } catch (error) {
              console.error(error);
            }
            return Swal.showValidationMessage(`{{ __('ui.error') }}`);
          }
        }
      }).then((result) => {
        if (result.value == 422) return
        if (result.isConfirmed) {
          validation.clear('#edit-form');
          Alert.success.fire({
            text: "{{ __('ui.edited', ['text' => $customer['firstname'] . ' ' . $customer['lastname']]) }}",
          });
          $('#edit-profile').show();
          $('button.editMode').hide();
          $('#edit-form input').attr('disabled', true);
        }
      });
    })
  </script>
@endsection
