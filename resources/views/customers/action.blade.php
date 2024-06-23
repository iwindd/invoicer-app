<div class="dropdown dropleft">
  <a class="btn btn-primary dropright btn-sm sm-3 shadow-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{__('ui.more')}}
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <a class="dropdown-item fa-fw" href="{{route("customer", ['id' => $id])}}"><i class="fas fa-info-circle mr-2 text-info"></i>{{__('ui.details')}}</a>
    <a class="dropdown-item fa-fw" href="#" onclick="delFunc({{$id}}, '{{$firstname}} {{$lastname}}')"><i class="fas fa-trash mr-2 text-danger"></i>{{__('ui.delete-btn')}}</a>
  </div>
</div>