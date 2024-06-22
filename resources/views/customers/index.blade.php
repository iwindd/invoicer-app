@extends('layouts.app')

@section('heading')
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ __('nav.customers') }}</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
        class="fas fa-plus fa-sm text-white-50"></i> {{ __('customer.create') }}</a>
  </div>
@endsection

@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">{{__('customer.table')}}</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>{{__("customer.joinedAt")}}</th>
              <th>{{__("customer.name")}}</th>
              <th>{{__("customer.invoice")}}</th>
              <th>{{__("customer.createdBy")}}</th>
              <th>{{__("ui.actions")}}</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>{{__("customer.joinedAt")}}</th>
              <th>{{__("customer.name")}}</th>
              <th>{{__("customer.invoice")}}</th>
              <th>{{__("customer.createdBy")}}</th>
              <th>{{__("ui.actions")}}</th>
            </tr>
          </tfoot>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
