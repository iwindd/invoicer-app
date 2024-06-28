@extends('layouts.app')

@section('heading')
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ __('nav.invoices') }}</h1>
    <a href="#" class="sm-3 btn btn-sm btn-primary shadow-sm" id="create-btn"><i
        class="fas fa-plus fa-sm text-white-50"></i> {{ __('invoice.create') }}</a>
  </div>
@endsection

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">{{ __('invoice.table') }}</h6>
      <section>
        <select class="form-control" id="invoices-filter-type">
          <option value="--">{{__('invoice.type-all')}}</option>
          <option value="2">{{__('invoice.type-checking')}}</option>
          <option value="4">{{__('invoice.type-overtime')}}</option>
          <option value="3">{{__('invoice.type-process')}}</option>
          <option value="0">{{__('invoice.type-waiting')}}</option>
          <option value="-1">{{__('invoice.type-cancel')}}</option>
        </select>
      </section>
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
              <th>{{ __('customer.customer') }}</th>
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
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('ui.dialogHeaderAdd') }}</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12 col-md-12 detail-section">
              <form action="#" method="post" id="create-invoice-form">
                <input type="hidden" name="id" value="0">
                <div class="row">
                  <div class="col-sm-12" id="customer-col">
                    <small class="form-text text-muted"> {{ __('customer.customer') }} </small>
                    <select id="select-tools" class="w-100" placeholder="{{ __('customer.customer') }}"></select>
                    <div class="id-feedback" id="note-feedback"></div>
                  </div>
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
            <div class="col-lg-4 col-md-12 evidence-section" style="display: none; overflow-y: scroll;">
              <section class="w-full " style="background: black; width:100%">
                <img src="" style="width: 100%;" id="evidence-image" alt="evidence">
              </section>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <section>
            <button class="btn btn-success btn-evidence" data-action="allow">{{ __('invoice.allow') }}</button>
            <button class="btn btn-danger btn-evidence" data-action="deny">{{ __('invoice.deny') }}</button>
          </section>
          <section>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('ui.dialogCancel') }}</button>
            <button class="btn btn-primary" type="submit"
              form="create-invoice-form">{{ __('ui.dialogConfirm') }}</button>
          </section>
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
    
    $('#invoices-filter-type').on('change', RefreshTable)

    $(document).ready(function() {
      $("#dataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('invoices') }}",
          data: (data) => {
            data.filterType = $("#invoices-filter-type").val();
          }
        },
        order: [
          [0, 'desc']
        ],
        columns: [{
            data: 'id',
            name: 'id',
            render: ff.number
          },
          {
            data: 'status',
            name: 'status',
            orderable: false,
            render: (_, __, row) => ff.invoice_label(ff.invoice(row), true)
          },
          {
            data: 'note',
            name: 'note',
            render: ff.text
          },
          {
            data: 'items',
            name: 'items',
            orderable: false,
            searchable: false,
            render: ff.itemsvalue
          },
          {
            data: 'start',
            name: 'start',
            render: ff.date
          },
          {
            data: 'end',
            name: 'end',
            render: ff.date
          },
          {
            data: 'customer',
            name: 'customer.firstname',
          },
          {
            data: 'action',
            name: 'action',
            orderable: false
          },
        ],
      })
    })
  </script>
  <script type="text/javascript">
    const table = $('#invoice-items tbody');
    const total = $('#invoices-all-total');
    const noteInput = $("#create-invoice-form input[name='note']");
    const startInput = $('#create input[name="start"]');
    const endInput = $('#create input[name="end"]');
    const modal = $('#create');
    const submitBtn = $('#create button[type="submit"]');
    const addItemBtn = $('#add-invoice-item');
    const customerCol = $('#customer-col');
    const detailSection = $("#create .detail-section")
    const evidenceSection = $("#create .evidence-section")
    let status = 0; //  -1 = look, 0 = create, 1 = update
    let items = [];

    const $select = $('#select-tools').selectize({
      maxItems: 1,
      delimiter: " - ",
      valueField: 'id',
      labelField: 'name',
      searchField: 'name',
      options: [],
      create: false,
      onChange: (val) => {
        $("#create-invoice-form input[name='id']").val(val);
      }
    });
    const selectize = $select[0].selectize;

    $.ajax({
      url: "{{ route('customers2') }}",
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
      if (status == 0) {
        submitBtn.html("{{ __('ui.dialogConfirm') }}");
        $('#create .modal-title').html('{{ __('ui.dialogHeaderAdd') }}')
        if (submitBtn.hasClass('btn-success')) {
          submitBtn.addClass('btn-primary').removeClass('btn-success')
        }
        addItemBtn.show();
        submitBtn.show();
        noteInput.attr('disabled', false);
        startInput.attr('disabled', false);
        endInput.attr('disabled', false);
        customerCol.show();
      } else if (status == 1) {
        submitBtn.html("{{ __('ui.save') }}");
        $('#create .modal-title').html('{{ __('ui.edit-btn') }}')
        if (submitBtn.hasClass('btn-primary')) {
          submitBtn.removeClass('btn-primary').addClass('btn-success')
        }
        addItemBtn.show();
        submitBtn.show();
        noteInput.attr('disabled', false);
        startInput.attr('disabled', false);
        endInput.attr('disabled', false);
        customerCol.hide();
      } else {
        $('#create .modal-title').html('{{ __('ui.details') }}')
        addItemBtn.hide();
        submitBtn.hide();
        noteInput.attr('disabled', true);
        startInput.attr('disabled', true);
        endInput.attr('disabled', true);
        $select.attr('disabled', true);
        customerCol.hide();
      }

      const startVal = startInput.val();
      const endVal = endInput.val();

      if (endVal && startVal && dayjs(startVal).isAfter(dayjs(endVal))) endInput.val(startVal);
      if (startVal) endInput.attr('min', startVal);

      table.html("")
      if (items.length > 0) {
        items.map((item, index) => {
          const formatter = () => {
            return `
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
            `
          }

          const lookFormatter = () => {
            return `
              <tr>
                <th>${ff.number(index+1)}</th>
                <td>${item.name}</td>
                <td>${ff.number(item.amount)}</td>
                <td>${ff.money(item.value)}</td>
                <td id="${index}-total">${ff.money(item.amount * item.value)}</td>
                <td>
                  <button 
                    class="sm-3 btn btn-sm btn-danger shadow-sm"
                    disabled={true}
                    >
                    <i class="fas fa-trash fa-sm text-white-50"></i>
                      {{ __('ui.delete-btn') }}
                    </button>
                </td>
              </tr>
            `
          }

          table.append(status != -1 ? formatter() : lookFormatter());
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

    addItemBtn.on("click", () => {
      items.push({
        name: "",
        amount: 0,
        value: 0
      })
      update()
    })

    $('#create-invoice-form').submit(function(e) {
      e.preventDefault();

      if (status == -1) return;

      validation.clear("#create");
      const id = $("#create-invoice-form input[name='id']").val();
      const payload = {
        note: $("#create-invoice-form input[name='note']").val(),
        start: startInput.val(),
        end: endInput.val(),
        items: items,
      }

      if (status == 0) {
        $.ajax({
          url: "{{ route('invoice', ['id' => ':id']) }}".replace(":id", id),
          type: 'POST',
          data: JSON.stringify(payload),
          contentType: 'application/json',
          success: (data) => {
            items = []
            $('#create-invoice-form').trigger("reset");
            modal.modal('hide');
            validation.clear("#create", false);
            Toast.fire({
              icon: "success",
              title: "{{ __('ui.added') }}"
            });
            update()
            RefreshTable()
          },
          error: (error) => {
            if (!validation.error("#create", error)) {
              items = []
              $('#create-invoice-form').trigger("reset");
              modal.modal('hide');
              Toast.fire({
                icon: "error",
                title: "{{ __('ui.error') }}"
              });
              update()
            }
          }
        });
      } else {
        $.ajax({
          url: "{{ route('invoice', ['id' => ':id']) }}".replace(":id", id),
          type: 'PUT',
          data: JSON.stringify(payload),
          contentType: 'application/json',
          success: (data) => {
            items = []
            $('#create-invoice-form').trigger("reset");
            modal.modal('hide');
            validation.clear("#create", false);
            Toast.fire({
              icon: "success",
              title: "{{ __('ui.edited', ['text' => ':id']) }}".replace(":id", "{{ __('invoice.id') }}" +
                ff.number(id))
            });
            update()
            RefreshTable()
          },
          error: (error) => {
            if (!validation.error("#create", error)) {
              items = []
              $('#create-invoice-form').trigger("reset");
              modal.modal('hide');
              Toast.fire({
                icon: "error",
                title: "{{ __('ui.error') }}"
              });
              update()
            }
          }
        });
      }
    })

    startInput.on("change", update)

    const updateEvidence = (evidence = false) => {
      if (evidence) {
        evidenceSection.show();
        $('.btn-evidence').show();
        
        if (detailSection.hasClass("col-lg-12")) {
          detailSection.removeClass("col-lg-12").addClass("col-lg-8");
        }
      } else {
        evidenceSection.hide();
        $('.btn-evidence').hide();

        if (detailSection.hasClass("col-lg-8")) {
          detailSection.addClass("col-lg-12").removeClass("col-lg-8");
        }
      }
    }

    const editFunc = (id, note, start, end, _items) => {
      status = 1;
      $("#create-invoice-form input[name='id']").val(id);
      startInput.val(dayjs(start).format('YYYY-MM-DD'));
      endInput.val(dayjs(end).format('YYYY-MM-DD'));
      noteInput.val(note);
      items = JSON.parse(_items);
      update();
      validation.clear("#create", false);
      modal.modal('show');
      
      updateEvidence(false);
    }

    const lookFunc = (id, note, start, end, _items, evidence = null) => {
      status = -1;
      $("#create-invoice-form input[name='id']").val(id)
      startInput.val(dayjs(start).format('YYYY-MM-DD'));
      endInput.val(dayjs(end).format('YYYY-MM-DD'));
      noteInput.val(note);
      items = JSON.parse(_items);
      update();
      validation.clear("#create", false);
      modal.modal('show');
      
      if (evidence != null) {
        $('#evidence-image').attr('src', "{{ url('storage/images/'.':image') }}".replace(":image", evidence));
        $('.btn-evidence').attr('data-id', id);
      }

      updateEvidence(evidence != null)
    }

    const patchFunc = (id, status) => {
      Confirmation.fire({
        text: (
          status == -1 ? `{{ __('invoice.change-status-(-1)', ['id' => ':id']) }}` :
          status == 0 ? `{{ __('invoice.change-status-(0)', ['id' => ':id']) }}` :
          `{{ __('invoice.change-status-(1)', ['id' => ':id']) }}`
        ).replace(":id", ff.number(id)),
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),

        preConfirm: async () => {
          try {
            const resp = await $.ajax({
              url: "{{ route('invoice', ['id' => ':id']) }}".replace(":id", id),
              type: 'PATCH',
              data: {
                status
              }
            });
            return true;
          } catch (error) {
            return Swal.showValidationMessage(`{{ __('ui.error') }}`);
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          RefreshTable();
          modal.modal('hide');
          Alert.success.fire({
            text: `{{ __('invoice.changed-status') }}`,
          });
        }
      });
    }

    $('#create-btn').on('click', () => {
      status = 0;
      startInput.val("");
      endInput.val("");
      items = [];
      update();
      validation.clear("#create", false);
      modal.modal('show');
      updateEvidence(false);
    })

    $('.btn-evidence').on('click', function(){
      const action = $(this).attr('data-action');
      const id = $(this).attr('data-id');

      return action == "allow" ? patchFunc(id, 1) : patchFunc(id, 0);
    })


    update()
  </script>
@endsection
