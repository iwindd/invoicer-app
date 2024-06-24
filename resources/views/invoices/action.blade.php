<div class="dropdown dropleft">
  <a class="btn btn-primary dropright btn-sm sm-3 shadow-sm dropdown-toggle" href="#" role="button"
    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{ __('ui.more') }}
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <a class="dropdown-item fa-fw" href="{{route("customer", ['id' => $user_id])}}"><i class="fas fa-user fa-fw mr-2 text-gray-400"></i>{{__('customer.customer')}}</a>
    @if ($status == 0)
      <button class="dropdown-item" onclick="editFunc({{$id}}, '{{$note}}', '{{$start}}', '{{$end}}', '{{json_encode($items)}}')"><i class="fas fa-pen fa-fw mr-2 text-primary"></i>{{ __('ui.edit-btn') }}</button>
      <div class="dropdown-divider"></div>
      <button class="dropdown-item" onclick="patchFunc({{$id}}, 1)"><i class="fas fa-check mr-2 fa-fw text-success"></i>{{ __('invoice.type-success') }}</button>
      <button class="dropdown-item" onclick="patchFunc({{$id}}, -1)"><i class="fas fa-times mr-2 fa-fw text-secondary"></i>{{ __('invoice.type-cancel') }}</button>
    @endif

    @if ($status == 1 || $status == -1)
      <button class="dropdown-item" onclick="lookFunc({{$id}}, '{{$note}}', '{{$start}}', '{{$end}}', '{{json_encode($items)}}')"><i class="fas fa-search fa-fw mr-2 text-info"></i>{{ __('ui.details') }}</button>
      <div class="dropdown-divider"></div>
    @endif

    @if ($status == 1)
      <button class="dropdown-item" onclick="patchFunc({{$id}}, 0)"><i class="fas fa-undo fa-fw mr-2 text-danger"></i>{{ __('invoice.cancel-payment') }}</button>
    @endif

    @if ($status == -1)
      <button class="dropdown-item" onclick="patchFunc({{$id}}, 0)"><i class="fas fa-undo fa-fw mr-2 text-secondary"></i>{{ __('ui.recovery') }}</button>
    @endif
  </div>
</div>

