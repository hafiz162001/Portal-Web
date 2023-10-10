<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Location;
use App\Models\EventTicket;
use App\Http\Resources\TicketResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
    public function add(Request $post){
        $success = true;
        $data = null;
        $msg = null;
        $ringkasanPembayaran = [];
        $dataResponse = [];
        $dataDetail = [];
        $arrProduct['product'] = [];
        $amount = 0;
        $ppn = 0;
        $diskon = 0;
        $total_amount = 0;
        $uaid = auth()->user()->id;
        // $uaid = 1;

        try {

            if (auth()->user()->isRegistered == false) {
                throw new \Exception("Anda harus melengkapi profile terlebih dahulu");
            }

            if(auth()->user()->name == ''){
                throw new \Exception("Anda harus melengkapi profile terlebih dahulu");
            }

            $product = Product::with(['location', 'galleries'])->find($post['product_id']);

            if($product){
                if ($product->stock > $post['quantity']) {
                    $checkCart = Cart::where([ ['user_app_id', '=', $uaid], ['location_id', '<>', $product->location->id] ])->first();

                    if ($checkCart) {
                        $checkCartDetail = CartDetail::where([ ['cart_id', '=', $checkCart->id], ['user_app_id', '=', $uaid] ])->delete();
                        $checkCart->delete();
                    }

                    $cart = Cart::where([ ['location_id', '=', $product->location->id], ['user_app_id', '=', $uaid] ])->first();

                    if (!$cart) {
                        $cart = new Cart();
                        $cart->user_app_id = $uaid;
                        $cart->nama_pelanggan = $post['nama_pelanggan'];
                        $cart->location_id = $product->location->id;
                        $cart->based_price = 0;
                        $cart->discount = 0;
                        $cart->ppn = 0;
                        $cart->total_price = 0;
                        $cart->seats_number = 1;
                        if (isset($post['number'])) {
                            $cart->seats_number = $post['number'];
                        }
                        $cart->save();
                    }

                    $cartDetail = CartDetail::updateOrCreate(
                        ['product_id' => $product->id, 'user_app_id' => $uaid],
                        [
                            'cart_id' => $cart->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'product_description' => $post['catatan'],
                            'product_based_price' => $product->price,
                            'product_discount' => $product->discount_amount,
                            'quantity' => $post['quantity'],
                            'product_image' => count($product->galleries) > 0 ? $product->galleries[0]->image : null,
                            'user_app_id' => $uaid,
                        ]
                    );

                    $dataDetail = CartDetail::where('cart_id', $cart->id)->get();
                    foreach ($dataDetail as $key => $val) {
                        $arrProduct['product'][] = ['price'=>($val['product_based_price'] * $val['quantity'])];
                    }

                    if (count($arrProduct['product']) > 0) {
                        foreach ($arrProduct['product'] as $key => $val) {
                            $amount = $amount + ($val['price']);
                        }
                    }

                    $ppn = $amount * 11 / 100;
                    $total_amount = $amount+$ppn;
                    // $rProduct = Cart::with(['location', 'galleries', 'details'])->find(3);
                    $rProduct = Cart::with(['location', 'galleries', 'details'])->where([ ['id', '=', $cart->id], ['user_app_id', '=', $uaid] ])->paginate(1);
                    $dataResponse = CartResource::collection($rProduct);

                    $ringkasanPembayaran = [
                        [
                            'name'   => 'Harga',
                            'amount' => 'Rp '.number_format($amount, 0, ",", "."),
                        ],
                        [
                            'name'   => 'PPN 11%',
                            'amount' => 'Rp '.number_format($ppn, 0, ",", "."),
                        ],
                        [
                            'name'   => 'Diskon',
                            'amount' => 'Rp '.number_format($diskon, 0, ",", "."),
                        ],
                        [
                            'name'   => 'Total Pembayaran',
                            'amount' => 'Rp '.number_format($total_amount, 0, ",", "."),
                        ]
                    ];
                }else {
                    throw new \Exception("Product tidak tersedia");
                }

            }else {
                if (isset($post['nama_pelanggan'])) {
                    $cart = Cart::updateOrCreate(
                        ['user_app_id' => $uaid],
                        ['nama_pelanggan' => $post['nama_pelanggan']]
                    );
                }

                if (isset($post['order_type'])) {
                    $cart = Cart::updateOrCreate(
                        ['user_app_id' => $uaid],
                        ['order_type' => $post['order_type']]
                    );
                }

                $cart = Cart::where([ ['user_app_id', '=', $uaid] ])->first();
                $dataDetail = CartDetail::where('cart_id', $cart->id)->get();
                foreach ($dataDetail as $key => $val) {
                    $arrProduct['product'][] = ['price'=>($val['product_based_price'] * $val['quantity'])];
                }

                if (count($arrProduct['product']) > 0) {
                    foreach ($arrProduct['product'] as $key => $val) {
                        $amount = $amount + ($val['price']);
                    }
                }

                $ppn = $amount * 11 / 100;
                $total_amount = $amount+$ppn;
                // $rProduct = Cart::with(['location', 'galleries', 'details'])->find(3);
                $rProduct = Cart::with(['location', 'galleries', 'details'])->where([ ['id', '=', $cart->id], ['user_app_id', '=', $uaid] ])->paginate(1);
                $dataResponse = CartResource::collection($rProduct);

                $ringkasanPembayaran = [
                    [
                        'name'   => 'Harga',
                        'amount' => 'Rp '.number_format($amount, 0, ",", "."),
                    ],
                    [
                        'name'   => 'PPN 11%',
                        'amount' => 'Rp '.number_format($ppn, 0, ",", "."),
                    ],
                    [
                        'name'   => 'Diskon',
                        'amount' => 'Rp '.number_format($diskon, 0, ",", "."),
                    ],
                    [
                        'name'   => 'Total Pembayaran',
                        'amount' => 'Rp '.number_format($total_amount, 0, ",", "."),
                    ]
                ];
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $res = [
            'success' => $success,
            'data'    => $dataResponse,
            'ringkasan_pembayaran' => $ringkasanPembayaran,
            'jumlah_product' => count($dataDetail),
            'amount' => 'Rp '.number_format($amount, 0, ",", "."),
            'message' => $msg,
        ];

        return $res;
    }

    public function remove(Request $post){
        $success = true;
        $data = null;
        $msg = null;
        $uaid = auth()->user()->id;
        $ringkasanPembayaran = [];
        $dataResponse = [];
        $dataDetail = [];
        $arrProduct['product'] = [];
        $amount = 0;
        $ppn = 0;
        $diskon = 0;
        $total_amount = 0;

        try {
            if (isset($post['cart_id'])) {
                $delete = Cart::where([ ['id', '=', $post['cart_id']] ])->delete();
            }

            if (isset($post['cart_detail_id'])) {
                $deleteCartDetail = CartDetail::where([ ['id', '=', $post['cart_detail_id']] ])->delete();
            }

            $cart = Cart::where([ ['user_app_id', '=', $uaid] ])->first();
            if ($cart) {
                $dataDetail = CartDetail::where('cart_id', $cart->id)->withTrased()->get();

                foreach ($dataDetail as $key => $val) {
                    $arrProduct['product'][] = ['price'=>($val['product_based_price'] * $val['quantity'])];
                    // $pd['disc'][] = ['discount_amount'=>($val['product_discount'] * $val['quantity'])];
                }

                if (count($arrProduct['product']) > 0) {
                    foreach ($arrProduct['product'] as $key => $val) {
                        $amount = $amount + ($val['price']);
                    }
                }

                // if (count($pd['disc']) > 0) {
                //     foreach ($pd['disc'] as $key => $val) {
                //         $diskon = $diskon + ($val['discount_amount']);
                //     }
                // }

                $amount = $amount - $diskon;
                $ppn = $amount * 11 / 100;
                $total_amount = $amount + $ppn;
                // $rProduct = Cart::with(['location', 'galleries', 'details'])->find(3);
                $rProduct = Cart::with(['location', 'galleries', 'details'])->where([ ['id', '=', $cart->id], ['user_app_id', '=', $uaid] ])->paginate(1);
                $dataResponse = CartResource::collection($rProduct);

                $ringkasanPembayaran = [
                    [
                        'name'   => 'Harga',
                        'amount' => 'Rp '.number_format($amount, 0, ",", "."),
                    ],
                    [
                        'name'   => 'PPN 11%',
                        'amount' => 'Rp '.number_format($ppn, 0, ",", "."),
                    ],
                    [
                        'name'   => 'Diskon',
                        'amount' => 'Rp '.number_format($diskon, 0, ",", "."),
                    ],
                    [
                        'name'   => 'Total Pembayaran',
                        'amount' => 'Rp '.number_format($total_amount, 0, ",", "."),
                    ]
                ];
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $res = [
            'success' => $success,
            'data'    => $dataResponse,
            'ringkasan_pembayaran' => $ringkasanPembayaran,
            'jumlah_product' => count($dataDetail),
            'amount' => 'Rp '.number_format($amount, 0, ",", "."),
            'message' => $msg,
        ];

        return $res;
    }

    public function cartDetail(Request $post){
        $success = true;
        $data = null;
        $msg = null;
        $uaid = auth()->user()->id;
        // $uaid = 1;
        $ringkasanPembayaran = [];
        $dataResponse = [];

        try {
            $cart = Cart::find($post['cart_id']);

            if ($cart) {
                $dataDetail = CartDetail::where('cart_id', $cart->id)->get();
                $arrProduct['product'] = [];
                $amount = 0;
                $ppn = 0;
                $diskon = 0;
                $total_amount = 0;
                foreach ($dataDetail as $key => $val) {
                    $arrProduct['product'][] = ['price'=>($val['product_based_price'] * $val['quantity'])];
                    $pd['disc'][] = ['discount_amount'=>($val['product_discount'] * $val['quantity'])];
                }

                if (count($arrProduct['product']) > 0) {
                    foreach ($arrProduct['product'] as $key => $val) {
                        $amount = $amount + ($val['price']);
                    }
                }

                if (count($pd['disc']) > 0) {
                    foreach ($pd['disc'] as $key => $val) {
                        $diskon = $diskon + ($val['discount_amount']);
                    }
                }

                $amount = $amount - $diskon;
                $ppn = $amount * 11 / 100;
                $total_amount = $amount + $ppn;

                $rProduct = Cart::with(['location', 'galleries', 'details'])->where([ ['id', '=', $cart->id], ['user_app_id', '=', $uaid] ])->paginate(1);

                $dataResponse = CartResource::collection($rProduct);

                $ringkasanPembayaran = [
                    [
                        'name'   => 'Harga',
                        'amount' => 'Rp '.number_format($amount, 0, ",", "."),
                    ],
                    [
                        'name'   => 'PPN 11%',
                        'amount' => 'Rp '.number_format($ppn, 0, ",", "."),
                    ],
                    [
                        'name'   => 'Diskon',
                        'amount' => 'Rp '.number_format($diskon, 0, ",", "."),
                    ],
                    [
                        'name'   => 'Total Pembayaran',
                        'amount' => 'Rp '.number_format($total_amount, 0, ",", "."),
                    ]
                ];
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $res = [
            'success' => $success,
            'data'    => $dataResponse,
            'ringkasan_pembayaran' => $ringkasanPembayaran,
            'nama_pelanggan' => $cart->nama_pelanggan ? $cart->nama_pelanggan : '',
            'jumlah_product' => count($dataDetail),
            'amount' => 'Rp '.number_format($amount, 0, ",", "."),
            'message' => $msg,
        ];

        return $res;
    }

    public function getCart(Request $post){
        $success = true;
        $data = null;
        $msg = null;
        $uaid = auth()->user()->id;

        try {
            $cart = Cart::where([ ['user_app_id', '=', $uaid] ])->orderByDesc('id')->first();
            $data = $cart;
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $res;
    }
}
