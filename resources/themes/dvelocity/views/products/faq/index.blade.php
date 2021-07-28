@extends('shop::layouts.master')

@section('page_title')
    {{-- {{ __('shop::app.reviews.product-review-page-title') }} --}}
@endsection


@push('css')
    <style>
        .reviews {
            display: none !important;
        }
    </style>
@endpush

@section('content-wrapper')
    <div class="container">
        <div class="row review-page-container">
            @include ('shop::products.view.small-view', ['product' => $product])

            <div class="col-lg-7 col-md-12 fs16">
                <h2 class="full-width mb30">Faqs</h2>

                @include ('shop::products.view.faqs')
            </div>
        </div>
    </div>
@endsection