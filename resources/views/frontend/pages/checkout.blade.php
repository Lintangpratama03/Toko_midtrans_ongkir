@extends('frontend.layouts.master')

@section('title', 'Checkout page')

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Checkout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-lg-8 col-12">
                        <div class="checkout-form">
                            <h2>Silahkan isi data anda</h2><br>
                            <!-- <p>Please register in order to checkout more quickly</p> -->
                            <!-- Form -->
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Nama Depan<span>*</span></label>
                                        @php
                                            $userNameParts = explode(' ', Auth()->user()->name);
                                            $firstName = $userNameParts[0] ?? ''; // Mengambil bagian pertama (nama depan), atau string kosong jika tidak ada
                                        @endphp
                                        <input type="text" name="first_name" placeholder="" value="{{ $firstName }}">
                                        @error('first_name')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Nama Belakang<span>*</span></label>
                                        @php
                                            $userNameParts = explode(' ', Auth()->user()->name);
                                            $lastName = $userNameParts[1] ?? ''; // Mengambil bagian pertama (nama depan), atau string kosong jika tidak ada
                                        @endphp
                                        <input type="text" name="last_name" placeholder="" value="{{ $lastName }}">
                                        @error('last_name')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Email<span>*</span></label>
                                        <input type="email" name="email" placeholder=""
                                            value="{{ Auth()->user()->email }}">
                                        @error('email')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>No HP <span>*</span></label>
                                        <input type="number" name="phone" placeholder="" required
                                            value="{{ old('phone') }}">
                                        @error('phone')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Provinsi</label>
                                        <select name="province" id="province" class="select2">

                                        </select>
                                        @error('province')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Kota</label>
                                        <select name="city" id="city" class="form-control select2">

                                        </select>
                                        @error('city')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Kode Pos</label>
                                        <input type="text" name="post_code" placeholder=""
                                            value="{{ old('post_code') }}">
                                        @error('post_code')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <!--/ End Form -->
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="order-details">
                            <!-- Order Widget -->
                            <div class="single-widget">
                                <h2>Total Keranjanag</h2>
                                <div class="content">
                                    <ul>
                                        <li class="order_subtotal" data-price="{{ Helper::totalCartPrice() }}">
                                            Subtotal<span>Rp.{{ number_format(Helper::totalCartPrice(), 2) }}</span></li>
                                        <li class="shipping">
                                            Pengiriman
                                            <select name="shipping" id="shipping" class="form-control select2">
                                                <option value="">Pilih Kurir</option>
                                                <option value="jne">JNE</option>
                                                <option value="tiki">TIKI</option>
                                                <option value="pos">POS Indonesia</option>
                                            </select>
                                            <select name="shipping_service" id="shipping_service"
                                                class="form-control select2" style="display:none;">
                                                <option value="">Pilih Layanan</option>
                                            </select>
                                        </li>

                                        @if (session('coupon'))
                                            <li class="coupon_price" data-price="{{ session('coupon')['value'] }}">Anda
                                                hemat<span>Rp.{{ number_format(session('coupon')['value'], 2) }}</span>
                                            </li>
                                        @endif
                                        @php
                                            $total_amount = Helper::totalCartPrice();
                                            if (session('coupon')) {
                                                $total_amount = $total_amount - session('coupon')['value'];
                                            }
                                        @endphp
                                        @if (session('coupon'))
                                            <li class="last" id="order_total_price">
                                                Total<span>Rp.{{ number_format($total_amount, 2) }}</span></li>
                                        @else
                                            <li class="last" id="order_total_price">
                                                Total<span>Rp.{{ number_format($total_amount, 2) }}</span></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <!--/ End Order Widget -->
                            <!-- Order Widget -->
                            <div class="single-widget">
                                <h2>Pembayaran</h2>
                                <div class="content">
                                    <div class="checkbox">
                                        {{-- <label class="checkbox-inline" for="1"><input name="updates" id="1" type="checkbox"> Check Payments</label> --}}
                                        <form-group>
                                            <input name="payment_method" type="radio" value="cod"> <label> Cash On
                                                Delivery (COD)</label><br>
                                            <!-- <input name="payment_method"  type="radio" value="paypal"> <label> Transfer (VA)</label><br> -->
                                            <input name="payment_method" type="radio" value="qris"> <label>
                                                QRIS</label>
                                        </form-group>

                                    </div>
                                </div>
                            </div>
                            <!--/ End Order Widget -->
                            <!-- Payment Method Widget -->
                            <div class="single-widget payement">
                                <div class="content">
                                    <img src="{{ 'backend/img/payment-method.png' }}" alt="#">
                                </div>
                            </div>
                            <!--/ End Payment Method Widget -->
                            <!-- Button Widget -->
                            <div class="single-widget get-button">
                                <div class="content">
                                    <div class="button">
                                        <button type="submit" class="btn btn-primary">Proses Checkout</button>
                                    </div>
                                </div>
                            </div>
                            <!--/ End Button Widget -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!--/ End Checkout -->

    <!-- Start Shop Services Area  -->
    <section class="shop-services section home">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-rocket"></i>
                        <h4>Gratis Ongkir</h4>
                        <p>Tunggu promonya setiap bulan</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-reload"></i>
                        <h4>Bergaransi</h4>
                        <p>Barang tidak sesuai dapat diganti</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-lock"></i>
                        <h4>Pembayaran aman</h4>
                        <p>100% pembayaran aman</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-tag"></i>
                        <h4>Harga bersahabat</h4>
                        <p>Murah dan berkualitas</p>
                    </div>
                    <!-- End Single Service -->
                </div>
            </div>
        </div>
    </section>
    <!-- End Shop Services -->
@endsection
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@push('styles')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 40px !important;
            border: 1px solid #ced4da !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
        }
    </style>
@endpush

@push('scripts')
    {{-- <script src="{{ asset('frontend/js/nice-select/js/jquery.nice-select.min.js') }}"></script> --}}
    <script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Fetch provinces
            $.ajax({
                url: '/api/provinces',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let options = '<option value="">Pilih Provinsi</option>';
                    if (Array.isArray(response)) {
                        $.each(response, function(index, province) {
                            options +=
                                `<option value="${province.province_id}">${province.province}</option>`;
                        });
                    } else if (response.data && Array.isArray(response.data)) {
                        $.each(response.data, function(index, province) {
                            options +=
                                `<option value="${province.province_id}">${province.province}</option>`;
                        });
                    } else {
                        console.error("Unexpected response format:", response);
                    }
                    // console.log(options);
                    $('#province').html(options).trigger('change');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching provinces:", error);
                }
            });

            // Fetch cities when province is selected
            $('#province').change(function() {
                let provinceId = $(this).val();
                if (provinceId) {
                    $.ajax({
                        url: '/api/cities/' + provinceId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            let options = '<option value="">Pilih Kota/Kabupaten</option>';
                            if (Array.isArray(response)) {
                                $.each(response, function(index, city) {
                                    options +=
                                        `<option value="${city.city_id}">${city.city_name}</option>`;
                                });
                            } else if (response.data && Array.isArray(response.data)) {
                                $.each(response.data, function(index, city) {
                                    options +=
                                        `<option value="${city.city_id}">${city.city_name}</option>`;
                                });
                            } else {
                                console.error("Unexpected response format:", response);
                            }
                            $('#city').html(options).trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching cities:", error);
                        }
                    });
                } else {
                    $('#city').html('<option value="">Pilih Kota/Kabupaten</option>').trigger('change');
                }
            });

            // Calculate shipping cost
            // Calculate shipping cost
            $('#shipping').change(function() {
                let courier = $(this).val();
                let destination = $('#city').val();
                let weight = {{ Helper::totalCartWeight() }};
                console.log(destination, courier, weight);
                if (courier && destination) {
                    $.ajax({
                        url: '/api/shipping-cost',
                        type: 'POST',
                        data: {
                            courier: courier,
                            destination: destination,
                            weight: weight
                        },
                        success: function(data) {
                            let options = '<option value="">Pilih Layanan</option>';
                            $.each(data, function(key, value) {
                                options +=
                                    `<option value="${value.cost[0].value}">${value.service} - Rp${value.cost[0].value}</option>`;
                            });
                            $('#shipping_service').html(options).show().select2();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error calculating shipping cost:", error);
                            console.log("Response:", xhr.responseText);
                            alert(
                                "Terjadi kesalahan saat menghitung biaya pengiriman. Silakan coba lagi."
                            );
                        }
                    });
                }
            });

            // Update total when shipping service is selected
            $(document).on('change', '#shipping_service', function() {
                let shippingCost = parseFloat($(this).val()) || 0;
                let subtotal = parseFloat($('.order_subtotal').data('price'));
                let total = subtotal + shippingCost;

                $('.shipping span').text('Rp.' + shippingCost.toFixed(2));
                $('#order_total_price span').text('Rp.' + total.toFixed(2));
            });
        });
    </script>
@endpush
