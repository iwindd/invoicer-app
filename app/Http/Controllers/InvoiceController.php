<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchInvoiceRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    //
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $model = $this->auth()->invoices($request->filterType)
                ->with(['items', 'customer', 'evidence'])
                ->orderByRaw($this->statusOrder()); 

            return DataTables::eloquent($model)
            ->addColumn("action", "invoices.action")
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make();
        }
        
        return view('invoices.index');
    }

    public function get(Request $request)
    {
        if (request()->ajax()) {
            $model = $this->auth()->customers()->find($request->id)
                ->invoices($request->filterType)
                ->with(['items', 'evidence'])
                ->orderByRaw($this->statusOrder()); 

            return DataTables::eloquent($model)
            ->addColumn("action", "customers.customer.action")
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make();
        }
    }

    public function store(StoreInvoiceRequest $request)
    {
        $payload  = array_merge($request->safe()->except('items'), ['user_id' => auth()->id()]);
        $customer = $this->auth()->customers()->find($request->id);
        $invoice  = $customer->invoices()->create($payload);
        $invoice->items()->createMany($request->safe()->only('items')['items']); 
        $this->activity('invoice-create', $customer->attributesToArray());

        return Response()->noContent( );
    }

    public function update(UpdateInvoiceRequest $request)
    {
        $invoice = $this->auth()->invoices()->find($request->id);
        $invoice->update($request->safe()->only("note", "start", "end"));
        $invoice->items()->delete();
        $invoice->items()->createMany($request->safe()->only('items')['items']);

        $this->activity('invoice-update', $request->safe()->only(['id']));
        
        return Response()->noContent();
    }

    public function patch(PatchInvoiceRequest $request)
    {
        $invoice   = $this->auth()->invoices()->find($request->id);
        $oldStatus = $invoice->status;
        $invoice->update($request->safe()->only('status'));
        $this->activity("invoice-patch-({$oldStatus})-({$request->status})", $request->validated());

        return Response()->noContent();
    }

    public function statusOrder() : string {
        return "CASE 
                    WHEN status = 2 THEN 1
                    WHEN status = 0 THEN 2
                    WHEN status = 1 THEN 3
                    WHEN status = -1 THEN 4
                END";
    }
}
