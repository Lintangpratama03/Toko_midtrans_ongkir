@extends('frontend.layouts.master')

@section('title', 'Order Success')

@section('main-content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>Terima Kasih atas Pesanan Anda!</h2>
                <p>Nomor Pesanan Anda: {{ $order->order_number }}</p>
                <p>Total Pembayaran: Rp {{ number_format($order->total_amount, 2) }}</p>
                <p>Kami akan segera memproses pesanan Anda.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
@endsection
