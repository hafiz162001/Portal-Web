<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\EatsOrder;
use App\Models\Seats;
use App\Http\Resources\EatsOrderResource;
use App\Models\Product;

class EatsOrderController extends Controller
{
    public function myOrder(Request $post){
        $success = true;
        $data = null;
        $msg = null;

        try {
            $uaid = $post['id'];
            $order = EatsOrder::where([ ['user_app_id', '=', $uaid], ['status', '=', 0] ])->count();
            $data = $order;
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];
        return $res;
    }
    public function order(Request $post){
        $success = true;
        $data = null;
        $msg = 'Order anda berhasil';
        $paymentResponse = null;

        try {
            $cart = Cart::where('id', $post['id'])->first();

            if (!$cart) {
                throw new \Exception("Cart not found");
            }

            $cartDetail = CartDetail::where('cart_id', $cart->id)->get();
            $amount = 0;
            $disc = 0;
            $ppn = 0;



            foreach ($cartDetail as $key => $val) {
                $product = Product::where([ ['id', '=', $val->product_id] ])->first();
                // if ($product->stoct < $val['quantity']) {
                //     $cart->delete();
                //     throw new \Exception("Mohon maaf product yang anda pilih stocknya sudah habis");
                // }
                $pd['product'][] = ['price'=>($val['product_based_price'] * $val['quantity'])];
                $pd['disc'][] = ['discount_amount'=>($val['product_discount'] * $val['quantity'])];
                $product->stock = $product->stock - $val['quantity'];
                $product->save();
            }

            if (count($pd['product']) < 1) {
                throw new \Exception("No product selected");
            }

            if (count($pd['disc']) > 0) {
                foreach ($pd['disc'] as $key => $val) {
                    $disc = $disc + ($val['discount_amount']);
                }
            }

            foreach ($pd['product'] as $key => $val) {
                $amount = $amount + ($val['price']);
            }

            $amount = $amount - $disc;
            $ppn = $amount * 11 / 100;
            $totalPrice = $amount + $ppn;

            $eatsOrder = EatsOrder::create([
                'cart_id'       => $cart->id,
                'user_app_id'   => $cart->user_app_id,
                'based_price'   => $amount,
                'discount'      => 0,
                'ppn'           => $ppn,
                'total_price'   => $totalPrice,
                'status'        => ($totalPrice == 0) ? 1 : 0,
            ]);

            $dataSend['amount'] = $totalPrice;
            $dataSend['ppn'] = $ppn;
            $dataSend['total_amount'] = $totalPrice;
            $dataSend['id'] = $eatsOrder->id;
            $dataSend['product-desc'] = 'Eats Order';
            $datasend['seat_number'] = $cart->seats_number;
            $paymentResponse = payToMidtrans($dataSend);
                if(!empty($paymentResponse)){
                    if(!empty($paymentResponse["data"]) && !empty($paymentResponse["data"]["invoice-id"])){
                        EatsOrder::where('id', $dataSend['id'])->update([
                            'invoice_number' => $paymentResponse["data"]["invoice-id"]
                        ]);
                    }
                }
            $data = $dataSend;

            //update cart status to waiting for payment
            $cart->delete();
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        $res = [
            'request' => $post->all(),
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'paymentResponse' => $paymentResponse,
        ];
        return $res;
    }

    public function orderDetail(Request $post){
        $success = true;
        $data = null;
        $msg = null;
        $paymentResponse = null;

        try {
            $cart = Cart::where('id', $post['cart_id'])->withTrashed()->first();

            if (!$cart) {
                throw new \Exception("Cart not found");
            }

            $cartDetail = CartDetail::where('cart_id', $cart->id)->withTrashed()->get();
            $amount = 0;
            $disc = 0;
            $ppn = 0;

            foreach ($cartDetail as $key => $val) {
                $pd['product'][] = ['price'=>($val['product_based_price'] * $val['quantity'])];
                $pd['disc'][] = ['discount_amount'=>($val['product_discount'] * $val['quantity'])];
            }

            if (count($pd['product']) < 1) {
                throw new \Exception("No product selected");
            }

            if (count($pd['disc']) > 0) {
                foreach ($pd['disc'] as $key => $val) {
                    $disc = $disc + ($val['discount_amount']);
                }
            }

            foreach ($pd['product'] as $key => $val) {
                $amount = $amount + ($val['price']);
            }

            $amount = $amount - $disc;
            $ppn = $amount * 11 / 100;
            $totalPrice = $amount + $ppn;

            $eatsOrder = EatsOrder::where('cart_id', $cart->id)->first();

            $dataSend['amount'] = $totalPrice;
            $dataSend['ppn'] = $ppn;
            $dataSend['total_amount'] = $totalPrice;
            $dataSend['id'] = $eatsOrder->id;
            $dataSend['product-desc'] = 'Eats Order';
            $datasend['seat_number'] = $cart->seats_number;
            $paymentResponse = payToMidtrans($dataSend);

            if(!empty($paymentResponse)){
                if(!empty($paymentResponse["data"]) && !empty($paymentResponse["data"]["invoice-id"])){
                    $eatsOrder->update([
                        'invoice_number' => $paymentResponse["data"]["invoice-id"]
                    ]);
                }
            }

            $data = $dataSend;

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        $res = [
            'request' => $post->all(),
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'paymentResponse' => $paymentResponse,
        ];
        return $res;
    }

    public function history(Request $post)
    {
        $success = true;
        $data = [];
        $msg = null;
        $uaid = $post['id'];
        try {
            $order = EatsOrder::with('cart')->orderBy('id', 'desc')->where('user_app_id', $uaid);
            $order = $order->paginate(10);
            $data = EatsOrderResource::collection($order);


        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            // 'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            // 'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];
        return $res;
    }

    function seatsLabel($status=0){
        $label[0] = 'Available';
        $label[1] = 'Un available';

        return $label[$status];
    }

    public function seatsAvailable(Request $post){
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $seats = Seats::where([ ['status', '=', 0], ['location_id', '=', $post['location_id']] ])->get();
            foreach ($seats as $key => $val) {
                $data[] = [
                    'location_id' => $val->location_id,
                    'number' => $val->number,
                    'status' => $val->status,
                    'status_label'=> $this->seatsLabel($val->status),
                ];
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];
        return $res;
    }
}
