<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Http;
class AgencyController extends Controller
{
   public function store_invoice(Request $request)
   {
        $func = "invoice_add";
        if(!$this->check_function($func))
        {
            return response()->json([
                'success' => false,
                'message' => 'Lưu thất bại!2',
            ], 200);
        }
        $this->validate($request,[
           
            'wo_id'=>'numeric|required',
            'uiid'=>'string|required',
        ]);
        $user = auth()->user();
        // return json_decode($user);
        $agency = \App\Models\Agency::where('user_id',$user->id)->first();
        if (!$agency)
        {
            return response()->json([
                'success' => false,
                'message' => 'Không có đại lý này!',
            ], 200);
        }
        $agency_invoice = \App\Models\AgencyInvoice::where('agency_id',$agency->id)->first();
        if (!$agency_invoice)
        {
            return response()->json([
                'success' => false,
                'message' => 'Chưa đăng ký callback_url!',
            ], 200);
        }
        $data = $request->all();
        $data['invoice_id'] =  $agency_invoice->id;
        $detail = \App\Models\AgencyInvoiceDetail::where('invoice_id',$agency_invoice->id)
            ->where('uiid',$data['uiid'])->first();
        if(!$detail)
        {
            $detail = \App\Models\AgencyInvoiceDetail::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Lưu thành công!',
                'uiid' => $detail->uiid,
            ], 200);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Mã điện tử đã có!',
                'uiid' => $detail->uiid,
            ], 200);
        }
       
   }
   public function getInvoice(Request $request)
   {
        
        $func = "invoice_read";
        if(!$this->check_function($func))
        {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập!',
            ], 200);
        }
        $this->validate($request,[
            'uiid'=>'string|required',
        ]);
        // $detail = \App\Models\SettingDetail::find(1);
        $invoice_detail = \App\Models\AgencyInvoiceDetail::where('uiid',$request->uiid)->first();
        if (!$invoice_detail)
        {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hóa đơn!',
            ], 200);
        }
        $agency_invoice = \App\Models\AgencyInvoice::where($invoice_detail->agency_id)->first();
        if (!$agency_invoice)
        {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy doanh nghiệp!',
            ], 200);
        }
        // return $agency_invoice->callback_url.'/api/v1/login';
        $tokenResponse = Http::post($agency_invoice->callback_url.'/api/v1/login', [
            'email' =>  'amnhaccuoituan@gmail.com' ,
            'password' =>'12345678',
        ]);
        
        if (!$tokenResponse->successful()) {
            return response()->json(['error' => 'Failed to get token'], 500);
        }

        $accessToken = $tokenResponse->json()['token']['token'];
        $response = Http::withToken($accessToken)
                    ->post($agency_invoice->callback_url.'/api/v1/get_invoice', [
                        
                        'uiid' => $request->uiid,
                        // Add other POST data as needed
                    ]);
        
        // Check response status and handle errors
        if ($response->failed()) {
        
            echo $response->status();
            echo $response->body();
            // Handle error accordingly
        }
        $responseData = "";
        if ($response->successful()) {
            // Request was successful, handle response
            $responseData = $response->json();
            // echo 'thành cong <br/>';
           
        }
        if ($responseData != "")
        {
            if($responseData['success'] == true)
            {
                $data = json_decode($responseData['data']);
                $data->invoice_id = $agency_invoice->id;
                return response()->json([
                    'success' => true,
                    'data' => json_encode( $data ),
                ], 200);
            }
            else
            {
                return $response->json();
            }
           
        }
        else
        {
            return response()->json([
                'success' => false,
                'msg' =>  'Không đọc được thông tin!',
            ], 200);
        }     
   }
}
 