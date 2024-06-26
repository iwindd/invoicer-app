<div class="dropdown dropleft">
  <a class="btn btn-primary dropright btn-sm sm-3 shadow-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{__('ui.more')}}
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <a class="dropdown-item fa-fw" href="{{route("customer", ['id' => $customer['id']])}}"><i class="fas fa-user mr-2 text-secondary"></i>{{__('customer.customer')}}</a>
    <button class="dropdown-item fa-fw" onclick="ApplicationLogin({{$id}})" ><i class="fas fa-sign-in-alt mr-2 text-info"></i>{{__('application.login')}}</button>
  </div>
</div>