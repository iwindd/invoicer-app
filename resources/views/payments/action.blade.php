<div class="dropdown dropleft">
  <a class="btn btn-primary dropright btn-sm sm-3 shadow-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{__('ui.more')}}
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <button class="dropdown-item" onclick="editFunc({{$id}}, '{{$title}}', '{{$account}}', '{{$name}}')"><i class="fas fa-pen fa-fw mr-2 text-primary"></i>{{ __('ui.edit-btn') }}</button>
    @if (!$active)
      <button class="dropdown-item" onclick="delFunc({{$id}}, '{{$title}}: {{$account}}')"><i class="fas fa-trash fa-fw mr-2 text-danger"></i>{{ __('ui.delete-btn') }}</button>
      <button class="dropdown-item" onclick="patchFunc({{$id}}, '{{$title}}: {{$account}}')"><i class="fas fa-toggle-on fa-fw mr-2 text-info"></i>{{ __('payment.use2') }}</button>
    @endif
  </div>
</div>