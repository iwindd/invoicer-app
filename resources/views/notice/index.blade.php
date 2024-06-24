<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Invoicer">
  <meta name="author" content="SiamIT">

  <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
    rel="stylesheet">


  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
  <style>
    .invoice:not(.border-primary) {
      cursor: pointer;
    }
  </style>

  <div class="w-full h-full d-flex justify-content-center align-items-center flex-column" style="height: 100vh; ">
    <section style="height: fit-content; margin-bottom: 5%; color: black;" class="text-center">
      @if ($invoices->where('status', 0)->count() > 0)
        <h1 class="display-4 mb-4" style="font-weight: 500;">{{ __('notice.header') }}</h1>
        <p class="mb-4">{{ __('notice.content', ['date' => $endDate, 'total' => $total]) }}</p>
        <button class="btn btn-primary" data-toggle="modal" data-target="#payment"><i
            class="far fa-credit-card mr-2 fa-fw"></i>{{ __('notice.button') }}</button>
      @else
        <h1 class="display-4 mb-4" style="font-weight: 500;">{{ __('notice.header2') }}</h1>
      @endif
    </section>
    <section>
      @if ($invoices->where('status', 0)->count() > 0)
        <span>{{ __('notice.footer') }}</span>
      @endif
    </section>
  </div>

  <div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('notice.list') }}</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('notice.patch') }}" method="post" id="payment-form" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-sm-4"><b>{{ __('payment.title') }}</b></div>
              <div class="col-sm-8">{{ $payment->title }}</div>
              <div class="col-sm-4"><b>{{ __('payment.name') }}</b></div>
              <div class="col-sm-8">{{ $payment->name }}</div>
              <div class="col-sm-4"><b>{{ __('payment.account') }}</b></div>
              <div class="col-sm-8">{{ $payment->account }}</div>
            </div>
            <hr>
            <section>
              @if ($errors->any())
                <input type="hidden" name="invoice" value="{{ old('invoice') }}">
              @else
                <input type="hidden" name="invoice" value="{{ $invoices->first()->id }}">
              @endif
              @foreach ($invoices as $invoice)
                @if ($invoice->status == 0)
                  <div data-id="{{ $invoice->id }}"
                    class="invoice card-body mb-1 border rounded d-flex align-items-center p-2 px-4">
                    <div class="mr-4">
                      <i class="fas fa-receipt fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="card-title mb-0 text-dark">{{ __('notice.itemHeader', ['id' => $invoice->id]) }}</h6>
                      <small class="card-subtitle text-muted">{{ __('notice.itemContent') }} <span
                          data-format="date">{{ $invoice->end }}</span> </small>
                    </div>
                    <div data-format="money" class="text-dark">
                      {{ $invoice->totalValue }}
                    </div>
                  </div>
                @endif
              @endforeach
              @error('id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </section>
            <section>
              <div class="custom-file mb-3">
                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" name="image"
                  accept='image/*' required>
                <label class="custom-file-label" for="validatedCustomFile">{{ __('notice.choose') }}</label>
                <div class="invalid-feedback">
                  @error('image')
                    {{ $message }}
                  @enderror"
                </div>
              </div>
            </section>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('ui.dialogCancel') }}</button>
          <button class="btn btn-primary" type="submit" form="payment-form">{{ __('notice.button') }}</button>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/formatter.js') }}"></script>
  <script src="{{ asset('js/autoformatter.js') }}"></script>
  <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

  @if ($errors->any())
    <script type="text/javascript">
      $('#payment').modal('show');
    </script>
  @endif
  <script type="text/javascript">
    let selected = $('input[name="invoice"]');

    $('.invoice[data-id]').on('click', function() {
      const id = $(this).attr('data-id');
      if (selected.val() == id) return;
      selected.val(id);

      $('.invoice[data-id]').removeClass('border-primary');
      $(this).addClass('border-primary');
    })

    $('input[name="image"]').change(function() {
      if (this.files.length > 0) {
        $('.custom-file-label').html(this.files[0].name)
      }
    })

    $(`.invoice[data-id="${selected.val()}"]`).addClass('border-primary');
  </script>
</body>

</html>
