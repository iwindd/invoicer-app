<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchNoticeRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use NumberFormatter;

class NoticeController extends Controller
{    
    /**
     * index
     *
     * @param  mixed $request
     * @return void
     */
    public function index(Request $request)
    {
        // customer
        $customer = Customer::find($request->id);

        // invoices
        $invoices = $customer->invoices()
            ->whereIn('status', [0, 2])
            ->where('start', '<=', now())->with('items:invoice_id,amount,value')->get(['id', 'customer_id', 'end', 'status']);

        if ($invoices->count() <= 0) abort(404);

        // payment
        $payment = Payment::where([
            ['active', true],
            ['user_id', $customer->user_id]
        ])->first(['title', 'account', 'name']);

        if (!$payment) abort(404);

        // invoices total value
        $total = $invoices->sum(function ($invoice) {
            return $invoice->items->sum('value');
        });

        $invoices->each(function ($invoice) {
            $invoice->totalValue = $invoice->items->sum('value');
        });

        // end date parse
        $endDate = Carbon::parse($invoices->first()->end)
            ->locale('th_TH')
            ->translatedFormat('j F Y');

        // format total;
        $fmt = new NumberFormatter('th_TH', NumberFormatter::CURRENCY);
        $total = $fmt->formatCurrency($total, 'THB');

        return view('notice.index', compact("invoices", "payment", "endDate", "total"));
    }
    
    /**
     * patch
     *
     * @param  mixed $request
     * @return void
     */
    public function patch(PatchNoticeRequest $request)
    {
        $invoice = Invoice::find($request->invoice);
        $user = $invoice->user;
        $extention = $request->image->getClientOriginalExtension();
        $imageName = date('mdYHis') . uniqid() . '.' . $extention;
        $request->image->storeAs('images', $imageName, 'public');

        $invoice->evidence()->create([
            'image' => $imageName,
            'status' => false
        ]);

        $invoice->update(['status' => 2]);
        $this->activity("invoice-patch-report", ['id' => $request->validated()['invoice']], $user);

        return redirect()->back();
    }
    
    /**
     * api
     *
     * @param  mixed $request
     * @return void
     */
    public function api(Request $request)
    {
        $customer = Customer::find($request->id);
        if (!$customer) return Response()->noContent();
        $route = route('notice', ['id' => $request->id]);
        $invoices = $customer->invoices()->where([
            ['status', 0],
            ['start', '<', now()],
            ['customer_id', $request->id]
        ])
        ->orWhere(function($query) use ($request){
            $query->where('status', 2)
            ->where('end', '<', now())
            ->where('customer_id', $request->id);
        });
        $isWarning = $invoices->count();
        
        if (!$isWarning) return Response()->noContent();
        
        if ($request->only == null) {
            $canClose = ($customer->invoices()
                ->where([
                    ['status', 0],
                    ['end', '<', now()]
                ])->orWhere(function($query) use ($request){
                    $query->where('status', 2)
                    ->where('end', '<', now());
                })->count() <= 0) ;
        }else{
            $canClose = $request->only == "0" ? false: true;
        }

        $ui = $canClose ? ( 
            <<<EOT
                const createContainer = () => {
                    const container = document.createElement("div");
                    container.style.display = "block";
                    container.style.width = "100%";
                    container.style.height = "100%";
                    container.style.overflow = "hidden";
                    container.style.position = "fixed";
                    container.style.zIndex = 99999999999999;
                    container.style.paddingTop = "100px";
                    container.style.left = 0;
                    container.style.top = 0;
                    container.style.backgroundColor = "rgba(0, 0, 0, 0.4)";
                    const content = document.createElement("div");
                    content.style.margin = "auto";
                    content.style.padding = "10px";
                    content.style.boxSizing = "border-box";
                    content.style.border = "1px solid #888";
                    content.style.width = "65%";
                    content.style.height = "80%";
                    content.style.display = "flex";
                    content.style.flexDirection = "column";
                    content.style.backgroundColor = "#fefefe";
                    const close = document.createElement("span");
                    close.innerHTML = "&times;";
                    close.style.color = "#e64e4e";
                    close.style.fontSize = "5em";
                    close.style.fontWeight = "bold";
                    close.style.marginLeft = "auto";
                    close.style.cursor = "pointer";
                    const wrapper = document.createElement("div");
                    wrapper.style.flexGrow = "1";
                    const iframe = document.createElement("iframe");
                    iframe.style.width = "100%";
                    iframe.style.height = "100%";
                    iframe.style.border = 0;
                    iframe.setAttribute(
                        "src",
                        "$route"
                    );
                    iframe.setAttribute("scrolling", "no");
                    close.onclick = () => {
                        const date = new Date();
                        document.cookie = "__payment_delay=\${date.toISOString()}; path=/"
                        container.style.display = "none"
                    }
                    function reSm(x) {
                        if (x.matches) { // If media query matches
                        content.style.width = "100%";
                        content.style.height = "100%";
                        container.style.paddingTop = "0px";
                        } else {
                        content.style.width = "65%";
                        content.style.height = "80%";
                        container.style.paddingTop = "100px";
                        }
                    }
                    var sm = window.matchMedia("(max-width: 900px)")
                    reSm(sm); // Call listener function at run time
                    sm.addEventListener("change", function() { reSm(sm); });
                    wrapper.appendChild(iframe);
                    content.appendChild(close);
                    content.appendChild(wrapper);
                    container.appendChild(content);
                    return container
                    }
            EOT
        ) : (
            <<<EOT
                const createContainer = () => {
                    const container = document.createElement("div");
                    container.style.width = '100%';
                    container.style.height = '100%';
                    container.style.overflow = "hidden";
                    const iframe = document.createElement("iframe");
                    iframe.style.zIndex = 999999;
                    iframe.style.width = '100%';
                    iframe.style.height = '100%';
                    iframe.style.position = 'absolute';
                    iframe.style.top = 0;
                    iframe.style.left = 0;
                    iframe.setAttribute("src", "$route");
                    iframe.setAttribute('scrolling', 'no');
                    container.appendChild(iframe)
                    return container
                }
            EOT
        );

        $script = $canClose ? (
            <<<EOT
                const getCookie = (cname) => {
                    let name = cname + "=";
                    let decodedCookie = decodeURIComponent(document.cookie);
                    let ca = decodedCookie.split(';');
                    for(let i = 0; i <ca.length; i++) {
                        let c = ca[i];
                        while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                        }
                        if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                        }
                    }
                    return "";
                }
                const isDelay = getCookie('__payment_delay');
                const lastWarningDate = new Date(isDelay);
                const currentDate = new Date();
                const timeDifferenceInMilliseconds = currentDate.getTime()- lastWarningDate.getTime()
                const DayMilliseconds = timeDifferenceInMilliseconds - (24 * (60 * 60000));
                if (DayMilliseconds < 0) return;
                const modal = createContainer();
                document.body.appendChild(modal);
            EOT
        ):(
            <<<EOT
                const iframe = createContainer();
                document.body.appendChild(iframe);
            EOT
        );

        $code = <<<EOT
            document.addEventListener('DOMContentLoaded', function () {
                $ui
                $script
            });
        EOT;

        $response = Response($code);
        $response->header('Content-Type', 'application/javascript'); 
        return $response; 
    }
}
