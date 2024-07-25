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


    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
    <style>
        .invoice:not(.border-primary) {
            cursor: pointer;
        }
    </style>

    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ __('notice.list') }}</h5>
            </div>
            <div class="card-body">
                <form method="post" id="payment-form" enctype="multipart/form-data">
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
                        <input type="hidden" name="invoice" value="{{ $invoices->first()->id }}">

                        @foreach ($invoices as $invoice)
                            @if ($invoice->status == 0)
                                <div data-id="{{ $invoice->id }}"
                                    class="invoice card-body mb-1 border rounded d-flex align-items-center p-2 px-4">
                                    <div class="mr-4">
                                        <i class="fas fa-receipt fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-0 text-dark">
                                            {{ __('notice.itemHeader', ['id' => $invoice->id]) }}</h6>
                                        <small class="card-subtitle text-muted">{{ __('notice.itemContent') }} <span
                                                data-format="date">{{ $invoice->end }}</span> </small>
                                    </div>
                                    <div data-format="money" class="text-dark">
                                        {{ $invoice->totalValue }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </section>
                    <section>
                        <div class="custom-file mb-3">
                            <input type="file" class="custom-file-input " name="image" accept='image/*' required>
                            <label class="custom-file-label"
                                for="validatedCustomFile">{{ __('notice.choose') }}</label>
                            <div class="invalid-feedback">

                            </div>
                        </div>
                    </section>
                </form>
            </div>
            <div class="card-footer" style="text-align: right;">
                <button class="btn btn-primary" type="submit" form="payment-form">{{ __('notice.button') }}</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/formatter.js') }}"></script>
    <script src="{{ asset('js/autoformatter.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

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

        $("#payment-form").submit(async function(e) {
            e.preventDefault();
            const invoiceInput = $('input[name="invoice"]')
            const fileInput = $('input[name="image"]');
            const formData = new FormData(this);
            $("button").attr("disabled", true);

            try {
                const resp = await fetch("{{ route('notice.patch') }}", {
                        method: "POST",
                        body: formData
                    }).then((response) => response.status)
                    .then((result) => {
                        if (result == 204) {
                            alert("{{ __('ui.success-pending') }}");
                            window.location.reload();
                        } else {
                            alert("{{ __('ui.error') }}");
                            window.location.reload();
                        }
                    })
                    .catch((error) => {
                        alert("{{ __('ui.error') }}");
                        window.location.reload();
                    });
            } catch (error) {
                alert("{{ __('ui.error') }}");
                $("button").attr("disabled", false);
            } finally {}
        })
    </script>
</body>

</html>
