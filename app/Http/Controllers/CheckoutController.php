<?php

namespace App\Http\Controllers;

use App\Modules\Event\Models\Event;
use Illuminate\Http\Request;
use PayOS\PayOS;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    public function __construct()
    {
    }

    public function createPaymentLink(Request $request)
    {
       
        // dd($request->all());
        $YOUR_DOMAIN = "http://localhost:8000";
        $invoice = Event::find($request->id);

        $data = [
            "orderCode" => intval(substr(strval(microtime(true) * 10000), -6)),
            "amount" => (int)$invoice->price,
            "description" => "Thanh toán đơn hàng",
            "returnUrl" => $YOUR_DOMAIN . "/success.html".'/'.$request->id.'/'.$request->user_id,
            "cancelUrl" => $YOUR_DOMAIN . "/cancel.html"
        ];
        $PAYOS_CLIENT_ID = env('PAYOS_CLIENT_ID');
        $PAYOS_API_KEY = env('PAYOS_API_KEY');
        $PAYOS_CHECKSUM_KEY = env('PAYOS_CHECKSUM_KEY');

        $payOS = new PayOS($PAYOS_CLIENT_ID, $PAYOS_API_KEY, $PAYOS_CHECKSUM_KEY);
       
        try {
            $response = $payOS->createPaymentLink($data);
            return redirect($response['checkoutUrl']);
                // Generate QR code from the API response
            
                // return $this->success('Thành công', ['url_banking' => $response['checkoutUrl'], 'base64_img' => $base64Img]);   
          
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
