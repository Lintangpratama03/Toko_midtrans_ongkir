<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    //


    public function showDetail($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        if ($order->payment_status == 'unpaid') {
            // Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => $order->order_number,
                    'gross_amount' => $order->total_amount,
                ),
                'customer_details' => array(
                    'first_name' => $order->first_name,
                    'last_name' => $order->last_name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                ),
            );

            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } else {
            $snapToken = null;
        }

        return view('frontend.checkout.detail', compact('order', 'snapToken'));
    }

    public function process(Request $request)
    {
        // dd($request->all());
        // Validasi input
        $request->validate([
            // tambahkan validasi lain sesuai kebutuhan
        ]);

        // Ambil cart items untuk user yang sedang login
        $cartItems = Cart::where('user_id', auth()->id())->get();

        // Hitung subtotal, total, dan quantity
        $subTotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $total = $subTotal + $request->shipping_service; // Tambahkan shipping cost jika ada
        $quantity = $cartItems->sum('quantity');

        // Buat pesanan baru
        $order = new Order();
        $order->order_number = 'ORD-' . strtoupper(uniqid());
        $order->user_id = auth()->id();
        $order->sub_total = $subTotal;
        $order->total_amount = $total;
        $order->quantity = $quantity;
        $order->payment_method = $request->payment_method;
        $order->shipping_service = $request->shipping_service;
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->country = 'ID';
        $order->post_code = $request->post_code;
        $order->address1 = $request->address1;
        $order->address2 = $request->address2;
        $order->province = $request->province;
        $order->city = $request->city;
        $order->save();

        // Pindahkan item dari cart ke order_items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        // Hapus cart items setelah pesanan dibuat
        Cart::where('user_id', auth()->id())->delete();

        // Hapus cart items setelah pesanan dibuat
        Cart::where('user_id', auth()->id())->delete();

        // Redirect ke halaman detail checkout
        return redirect()->route('checkout.detail', $order->order_number);
    }
    // public function midtransCallback(Request $request)
    // {
    //     $serverKey = 'SB-Mid-server-AqEXKgaXMOW2VRTS9VzHziBS';
    //     $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
    //     if ($hashed == $request->signature_key) {
    //         if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
    //             $order = Order::where('order_number', $request->order_id)->first();
    //             if ($order) {
    //                 $order->payment_status = 'paid';
    //                 $order->save();
    //             }
    //         }
    //     }

    //     return response('OK', 200);
    // }

    public function midtransCallback(Request $request)
    {
        Log::info('Midtrans Callback', $request->all());

        $serverKey = 'SB-Mid-server-AqEXKgaXMOW2VRTS9VzHziBS';
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $order = Order::where('order_number', $request->order_id)->first();
                if ($order) {
                    $order->payment_status = 'paid';
                    $order->save();
                    Log::info('Order updated', ['order_number' => $order->order_number]);
                }
            }
        }

        return response('OK', 200);
    }
}
