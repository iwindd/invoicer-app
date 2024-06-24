<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchInvoiceRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    //
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(
                $this->auth()->invoices()
                    ->with(['items', 'customer'])
                    ->whereHas('customer', function($query) {
                        $query->withoutTrashed();
                    })
                    ->select('*') 
                )
                ->addColumn("action", "invoices.action")
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        
        return view('invoices.index');
    }

    public function get(Request $request)
    {
        if (request()->ajax()) {
            return datatables()->of($this->auth()->customers()->find($request->id)->invoices()->with('items')->select('*'))
                ->addColumn("action", "customers.customer.action")
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function store(StoreInvoiceRequest $request)
    {
        $payload  = array_merge($request->safe()->except('items'), ['user_id' => auth()->id()]);
        $customer = $this->auth()->customers()->find($request->id);
        $invoice  = $customer->invoices()->create($payload);
        $invoice->items()->createMany($request->safe()->only('items')['items']); 

        return Response()->noContent( );
    }

    public function update(UpdateInvoiceRequest $request)
    {
        $invoice = $this->auth()->invoices()->find($request->id);
        $invoice->update($request->safe()->only("note", "start", "end"));
        $invoice->items()->delete();
        $invoice->items()->createMany($request->safe()->only('items')['items']);

        return Response()->noContent();
    }

    public function patch(PatchInvoiceRequest $request)
    {
        $invoice = $this->auth()->invoices()->find($request->id);
        $invoice->update($request->safe()->only('status'));

        return Response()->noContent();
    }
}
