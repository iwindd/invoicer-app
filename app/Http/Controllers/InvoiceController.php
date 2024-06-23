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
                    Invoice::with(['items', 'user', 'customer'])
                    ->whereHas('user', function($query) {
                        $query->where('application', Auth::user()->application);
                    })
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
            return datatables()->of(Invoice::with(['items', 'user'])->where('owner_id', $request->id)->select('*'))
                ->addColumn("action", "customers.customer.action")
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function store(StoreInvoiceRequest $request)
    {
        $invoiceData = $request->safe()->only(['id', 'note', 'start', 'end', 'items']);
        $customer = Customer::find($invoiceData['id']);
        $startOfDay = Carbon::parse($invoiceData['start'])->startOfDay();
        $endOfDay = Carbon::parse($invoiceData['end'])->endOfDay();
        $invoice  = $customer->invoices()->create([
            'note' => $invoiceData['note'],
            'start' => $startOfDay,
            'end' => $endOfDay,
            'status' => 0,
            'createdby_id' => auth()->id()
        ]);

        $invoice->items()->createMany($invoiceData['items']);

        return Response()->noContent();
    }

    public function update(UpdateInvoiceRequest $request)
    {
        $invoiceData = $request->safe()->only(['id', 'note', 'start', 'end', 'items']);
        $invoice = Invoice::find($invoiceData['id']);
        $startOfDay = Carbon::parse($invoiceData['start'])->startOfDay();
        $endOfDay = Carbon::parse($invoiceData['end'])->endOfDay();
        $invoice->update([
            'note' => $invoiceData['note'],
            'start' => $startOfDay,
            'end' => $endOfDay,
        ]);

        $invoice->items()->delete();
        $invoice->items()->createMany($invoiceData['items']);

        return Response()->noContent();
    }

    public function patch(PatchInvoiceRequest $request)
    {
        $data = $request->validated();
        Invoice::find($data['id'])->update($request->safe()->only(['status']));
    }
}
