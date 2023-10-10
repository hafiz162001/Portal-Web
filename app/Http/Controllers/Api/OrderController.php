<?php

namespace App\Http\Controllers\Api;
use App\Http\Library\Xendit;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

use App\Models\Order;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;

use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';
        $perPage = 5;

        try {
            $order = Order::query()->with('galleries', 'pricing');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $order = $order->orderBy('id', $sort);
            }else {
                $order = $order->orderBy('id', 'desc');
            }

            if ($request->q) {
                $safetyFields = ['title', 'id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        if ($field == 'id') {
                            $order = $order->where($field, '=', $request->q);
                        }else {
                            $order = $order->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                        }
                    }
                }
            }

            if (isset($request->category))
            {
                $order = $order->Where([ ['category', '=', $request->category] ]);
            }

            if (isset($request->type))
            {
                $order = $order->Where([ ['type', '=', $request->type] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $order = $order->paginate($perPage)->appends($request->query());

            if (count($order) < 1) {
                $msg = 'Order Data Not Found';
            }

            // $data = $order;
            $data = OrderResource::collection($order);

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];

        return $resp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $post)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $invoice_number = Str::random(4).'-'.time();

            $cart = Cart::where('id', $post['cart_id'])->first();

            if (!$cart) {
                throw new \Exception("Cart not found");
            }

            $cartDetail = CartDetail::where('cart_id', $cart->id)->get();
            $amount = 0;
            $disc = 0;
            $ppn = 0;

            foreach ($cartDetail as $key => $val) {
                $product = Product::where([ ['id', '=', $val->product_id] ])->first();
                if ($product->stock < $val['quantity']) {
                    $cart->delete();
                    throw new \Exception("Mohon maaf product yang anda pilih stocknya sudah habis");
                }
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
            $based_price = $amount;
            $ppn = $amount * 11 / 100;
            $price = $amount + $ppn;
            $discount = 0;

            $order = new Order;
            $order->invoice_number = $invoice_number;
            $order->order_type = 'mbloc-market';
            $order->based_price = $based_price;
            $order->discount = $discount;
            $order->price = $price;
            $order->quantity = $post->quantity;
            $order->status = 0;
            $order->description = null;

            if ($order->save()) {
                $dataSend = [
                    'id' => $order->id,
                    'amount' => $order->based_price,
                    'ppn' => $ppn,
                    'total_amount' => $order->price,
                    'checkout_method' => 'ONE_TIME_PAYMENT',
                    'channel_code' => $post['channel_code'],
                    'phone' => auth()->user()->phone,
                    'invoice_number' => $order->invoice_number,
                    'currency' => 'IDR',
                    'channel_category' => $post['channel_category'],
                ];

                // $charge = Xendit::charge($dataSend);
                $data = Xendit::charge(json_encode($dataSend));
            }
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    public function getPaymentChannel(Request $request){
        $success = true;
        $data = [];
        $msg = 'Ok';

        try {
            $data = Xendit::getPaymentChannel();

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

}
