<div class="dropdown dropleft">
  <a class="btn btn-primary dropright btn-sm sm-3 shadow-sm dropdown-toggle" href="#" role="button"
    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{ __('ui.more') }}
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <button class="dropdown-item"><i class="fas fa-search fa-fw mr-2 text-info"></i>{{ __('ui.more') }}</button>
    <button class="dropdown-item"><i class="fas fa-pen fa-fw mr-2 text-primary"></i>{{ __('ui.edit-btn') }}</button>
    <div class="dropdown-divider"></div>
    <button class="dropdown-item"><i class="fas fa-check mr-2 fa-fw text-success"></i>{{ __('invoice.type-success') }}</button>
    <button class="dropdown-item"><i class="fas fa-undo fa-fw mr-2 text-danger"></i>{{ __('invoice.cancel-payment') }}</button>
    <button class="dropdown-item"><i class="fas fa-times mr-2 fa-fw text-secondary"></i>{{ __('invoice.type-cancel') }}</button>
  </div>
</div>

