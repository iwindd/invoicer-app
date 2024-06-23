@extends('layouts.app')

@section('heading')
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ __('nav.invoices') }}</h1>
    <a href="#" class="sm-3 btn btn-sm btn-primary shadow-sm" data-toggle="modal"
      data-target="#create"><i class="fas fa-plus fa-sm text-white-50"></i> {{ __('invoice.create') }}</a>
  </div>
@endsection

@section('content')
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
@endsection

@section('modals')

@endsection

@section('scripts')

@endsection
