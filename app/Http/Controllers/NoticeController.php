<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchNoticeRequest;
use App\Models\Customer;
use App\Models\Payment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use NumberFormatter;

class NoticeController extends Controller
{
    //
    public function index(Request $request)
    {
        // customer
        $customer = Customer::find($request->id);

        // invoices
        $invoices = $customer->invoices()
            ->whereIn('status', [0, 2])
            ->where('start', '<=', now())->with('items')->get();
        // invoices total value
        $total = $invoices->sum(function ($invoice) {
            return $invoice->items->sum('value');
        });

        $invoices->each(function ($invoice) {
            $invoice->totalValue = $invoice->items->sum('value');
        });
        // payment
        $payment = Payment::where([
            ['active', true],
            ['user_id', $customer->user_id]
        ])->first();

        // end date parse

        $endDate = Carbon::parse($invoices->first()->end)
            ->locale('th_TH')
            ->translatedFormat('j F Y');

        // format total;
        $fmt = new NumberFormatter('th_TH', NumberFormatter::CURRENCY);
        $total = $fmt->formatCurrency($total, 'THB');

        return view('notice.index', compact("invoices", "payment", "endDate", "total"));
    }

    public function patch(PatchNoticeRequest $request) {
        dd($request->image);
    }
}
