@extends('frontend.layouts.master')

@section('title', 'Detail Checkout')

@section('main-content')
    <div class="container">
        <h2>Detail Pesanan</h2>
        <div class="row">
            <div class="col-md-6">
                <h4>Informasi Pesanan</h4>
                <p><strong>Nomor Pesanan:</strong> {{ $order->order_number }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Status Pembayaran:</strong> {{ ucfirst($order->payment_status) }}</p>
                <p><strong>Metode Pembayaran:</strong> {{ strtoupper($order->payment_method) }}</p>
            </div>
            <div class="col-md-6">
                <h4>Informasi Pengiriman</h4>
                <p><strong>Nama:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                <p><strong>Email:</strong> {{ $order->email }}</p>
                <p><strong>Telepon:</strong> {{ $order->phone }}</p>
                <p><strong>Alamat:</strong> {{ $order->address1 }}, {{ $order->city }}, {{ $order->province }}
                    {{ $order->post_code }}</p>
            </div>
        </div>

        @if ($order->payment_status == 'unpaid')
            <div class="row mt-4">
                <div class="col-md-12">
                    <button id="pay-button" class="btn btn-primary">Bayar Sekarang dengan Midtrans</button>
                </div>
            </div>
        @endif
    </div>

    @if ($order->payment_status == 'unpaid')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>
        <script>
            const payButton = document.querySelector('#pay-button');
            payButton.addEventListener('click', function(e) {
                e.preventDefault();

                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        alert('Pembayaran berhasil!');
                        console.log(result);
                        window.location.href = '{{ route('order.success', $order->id) }}';
                    },
                    onPending: function(result) {
                        alert('Pembayaran tertunda!');
                        console.log(result);
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal!');
                        console.log(result);
                    },
                    onClose: function() {
                        alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                    }
                });
            });
        </script>
    @endif
@endsection
