<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //
    public function index(){
        return view('welcome');
    }

    public function store(StoreInvoiceRequest $request)
    {
        $invoiceData = $request->safe()->only(['id','note', 'start', 'end', 'items']);
        $customer = Customer::find($invoiceData['id']);
        $invoice  = $customer->invoices()->create([
            'note' => $invoiceData['note'] ,
            'start' => $invoiceData['start'],
            'end' => $invoiceData['end'],
            'status' => 0,
            'createdby_id' => auth()->id()
        ]); 

        $invoice->items()->createMany($invoiceData['items']);

        return Response()->noContent();
    }
}
