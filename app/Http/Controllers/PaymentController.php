<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class PaymentController extends Controller
{
    private function deactivePayment(){
        Payment::where([
            ['application', Auth::user()->application],
            ['active', true]
        ])->update(['active' => false]);
    }
    //
    public function index() {
        if (request()->ajax()) {
            return datatables()->of(Payment::where('application', Auth::user()->application)->select('*'))
                ->addColumn("action", "payments.action")
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('payments.index');
    }

    public function store(StorePaymentRequest $request) {
        $data = $request->validated();

        if ($data['active'] == '1'){
            $this->deactivePayment();
        }

        $payment = new Payment;
        $payment->application = Auth::user()->application;
        $payment->title = $data['title'];
        $payment->account = $data['account'];
        $payment->name = $data['name'];
        $payment->active = $data['active'] == '1';
        $payment->save();

        return Response()->json(isNull($request->use));
    }
}
