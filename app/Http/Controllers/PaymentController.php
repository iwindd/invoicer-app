<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchPaymentRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class PaymentController extends Controller
{    
    /**
     * deactivePayment
     *
     * @return void
     */
    private function deactivePayment(){
        $this->auth()->payments()->where('active', true)->update(['active' => false]);
    }
        
    /**
     * index
     *
     * @return void
     */
    public function index() {
        if (request()->ajax()) {
            return datatables()->of($this->auth()->payments())
                ->addColumn("action", "payments.action")
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('payments.index');
    }
    
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(StorePaymentRequest $request) {
        if ($request->validated()['active']) {
            $this->deactivePayment();
        }

        $payment = $this->auth()->payments();
        $payment->create($request->validated());
        $this->activity('payment-create', $request->validated());
        
        return Response()->noContent();
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(UpdatePaymentRequest $request){
        $payment = $this->auth()->payments()->find($request->id);
        $payment->update($request->validated());
        $this->activity('payment-update', $request->validated());

        return Response()->noContent();
    }
    
    /**
     * patch
     *
     * @param  mixed $request
     * @return void
     */
    public function patch(PatchPaymentRequest $request) {
        $this->deactivePayment(); 
        $payment = $this->auth()->payments()->find($request->id);
        $payment->update(['active' => true]);
        $this->activity('payment-patch', $payment->attributesToArray());

        return Response()->noContent();
    }
    
    /**
     * destroy
     *
     * @param  mixed $request
     * @return void
     */
    public function destroy(Request $request) {
        $payment = $this->auth()->payments()->find($request->id);
        $payment->delete();
        $this->activity('payment-delete', $payment->attributesToArray());

        return Response()->noContent();
    }
}
