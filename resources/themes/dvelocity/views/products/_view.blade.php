@extends('shop::layouts.master')

@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('faqHelper', 'Webkul\Product\Helpers\Faq')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper')
@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')
@php
    $total = $reviewHelper->getTotalReviews($product);
    $totalfaq = $faqHelper->getTotalFaq($product);

    $avgRatings = $reviewHelper->getAverageRating($product);
    $avgStarRating = round($avgRatings);

    $productImages = [];
    $images = $productImageHelper->getGalleryImages($product);

    foreach ($images as $key => $image) {
        array_push($productImages, $image['medium_image_url']);
    }
    $customAttributeValues = $productViewHelper->getAdditionalData($product);
    $author = ($customAttributeValues[array_search('Author', array_column($customAttributeValues, 'code'))]['value']);
    $publisher = ($customAttributeValues[array_search('publisher', array_column($customAttributeValues, 'code'))]['value']);
    $bookPreviewID = ($customAttributeValues[array_search('bookPreview', array_column($customAttributeValues, 'code'))]['id']);
    $bookPreviewValue = ($customAttributeValues[array_search('bookPreview', array_column($customAttributeValues, 'code'))]['value']);

@endphp

@section('page_title')
    {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
@stop

@section('seo')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) !!}
        </script>
    @endif

    <?php $productBaseImage = app('Webkul\Product\Helpers\ProductImage')->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{{ $product->description }}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{{ $product->description }}" />

    <meta property="og:url" content="{{ route('shop.productOrCategory.index', $product->url_key) }}" />
@stop

@push('css')
    <style type="text/css">
        .related-products {
            width: 100%;
        }

        .recently-viewed {
            margin-top: 20px;
        }

        .store-meta-images > .recently-viewed:first-child {
            margin-top: 0px;
        }

        .main-content-wrapper {
            margin-bottom: 0px;
        }
        .product-detail #product-form .form-container>.left{
            top:0;
            position: relative;
        }
        .display-table{
            display: table;
        }
        .display-table-cell{
            display: table-cell;
        }
        .right.product-actions.display-table-cell,.right.price.display-table-cell{
            text-align: right;
        }
        .product-price-stock-box {
            border: 1px #ebebeb solid;
            width: 100%;
            padding: 15px !important;
            position: relative;
            margin-bottom: 0px !important;
        }
		.row.stockbox {
			padding: 20px 27px !important;
			border-bottom: 1px #ebebeb solid;
		}
		.row.addcardbox .quantity label {
			padding: 5px;
			margin: 0;
			font-weight: normal;
		}
		.product-price {
			margin-bottom: 0 !important;
			text-align: right;
		}
		.row.addcardbox {
			padding: 20px 27px !important;
		}
		.product-actions {
			text-align: right;
			padding: 0 !important;
		}
		.padding0 {
			padding: 0 !important;
		}
        .product-detail .right>div{
            border:none;
        }
        .quantity.control-group {
            display: inline-flex;
        }
		.addcardbox .quantity {
			padding-bottom: 0;
		}
        .share-buttons-product-page .add-to-cart-btn.pl0 {
            display: none;
        }
        .left.preview-sample-box.display-table-cell .btn{
            padding: 10px 25px;
            font-size: 15px;
            border: 1px #ebebeb solid;
			border-top: 0px;
			border-radius: 0px;
        }
        .preview-sample-share-btn-box{
            padding-right: 0px !important;
        }
		.left.preview-sample-box.display-table-cell {
			vertical-align: top;
		}
        .border1 {
            border: 1px #ebebeb solid !important;
        }
        .product-detail .right .info p {
            color: #6c6fb3;
			font-weight: bold;
            font-size: 12px;
        }
        .product-detail .right .info p span {
            color: #212529;
			font-weight: normal;
        }
		.row.dis_co {
			margin: 0;
		}
        .stars.mr5.fs16 {
            border-right: 1px solid #bdbdbd;
            padding-right: 10px;
            line-height: 14px;
            margin-right: 10px;
        }
        .material-icons, .material-icons-outlined {
            font-size: 14px;
            color: #000;
        }
        .description p {
            font-size: 14px;
            color: #848484 !important;
            font-weight: 600;
            margin-bottom: 15px !important;
        }
        .product-price span {
            color: #5c5c5c !important;
            line-height: 37px;
        }
        .product-detail .right .add-to-cart-btn button {
            text-transform: uppercase;
            background: #26298e !important;
            opacity: 1;
            line-height: 35px;
            padding: 0 !important;
            width: 135px !important;
            border: none !important;
        }
        .product-detail .product-actions > div .compare-icon {
            display: table-cell;
            padding: 0;
            height: auto !important;
            line-height: 45px;
            text-align: center !important;
        }
        .product-detail .product-actions > div .wishlist-icon {
            float: none;
            display: table-cell;
            padding: 0 !important;
            height: auto !important;
            line-height: 45px;
            text-align: center !important;
        }
        .share-buttons-product-page .mx-0.no-padding.border1 {
            width: 120px;
            display: table;
            float: right;
            text-align: center;
        }
        .product-detail .product-actions > div .compare-icon i, .product-detail .product-actions > div .wishlist-icon i {
            text-align: center;
            font-size: 30px;
            color: #336b75;
            display: inline-block;
        }
		.product-detail .product-actions > div .wishlist-icon i {
			margin-top: -8px;
		}
		.stars.mr5.fs16 .material-icons {
			font-size: 14px;
			color: #ed9f43;
		}
        .accordian.ratingaccord {
            border: none;
            padding:25px 20px 0;
			background: #fafafa;
            margin-bottom: 0;
        }
        .accordian.ratingaccord h3 {
            font-weight: bold;
			margin-bottom: 15px !important;
			font-size: 30px;
			color: #336b75;
			font-style: italic;
			position: relative;
			width: 100%;
			z-index: 0;
        }
		.accordian.ratingaccord h3 span {
			background: #fafafa;
			position: relative;
			z-index: 1;
			padding: 0px 25px 0px 0px;
		}
		.accordian.ratingaccord h3::after {
			content: "";
			position: absolute;
			top: 50%;
			left: 0;
			width: 100%;
			height: 1px;
			background: #dcdcdc;
			transform: translateY(-50%);
			z-index: 0;
		}
        .newsletter-wrapper h2 {
            margin-top: 15px;
            font-size: 30px;
        }
        .newsletter-wrapper p {
            font-size: 14px;
        }
        .footer .footer-content .newsletter-subscription {
            background-color: #2b297e;
        }
        .accordian .accordian-header {
            padding-bottom: 0;
        }
        header #search-form #header-search-icon {
            background-color: #2b297e;
        }
        .main-content-wrapper .content-list ul {
            background-color: #2b297e;
        }
        .product-detail .right .info .availability button {
            background: #26298e;
        }
        .col-lg-12.fs16 {
            text-align: left;
            font-size: 30px;
            font-weight: 600;
        }
        .stars.mr5.fs24 {
            text-align: left;
        }
        .material-icons, .material-icons-outlined {
            font-size: 24px;
            color: #000;
        }
        .fs16.fw6.display-block {
            text-align: left;
            margin-top: -5px;
			color: #8f8f8f;
        }
		.reviewbtnbox {
			text-align: right;
		}
        .customer-rating a {
            color: #333;
			display: inline-block;
			margin-top: 26px;
        }
        .theme-btn.light {
            background: #2b297e !important;
            color: #fff !important;
            font-size: 15px;
        }
        .review-description.col-lg-12 {
            font-size: 14px;
            margin-bottom: -5px;
        }
        .col-lg-12.mt5 {
            font-size: 14px;
        }
        .mb20.link-color {
			color: #2b297e;
			font-size: 14px;
			margin: 16px 0 0 0;
			display: block;
		}
        .customer-rating .rating-bar {
            top: 8px;
			background: #dfdfdf;
            height: 6px;
        }
        .customer-rating .rating-bar > div {
            background-color: #ed9d00;
        }
		.details_dis {
			display: block;
			width: 100%;
			text-align: center;
			line-height: 20px;
			padding: 15px 0px;
			color: #fff;
		}
		.details_dis .details_dis_b {
			font-weight: bold;
		}
		.description {
			width:100%;
		}
		.row.dis_co .col-sm-4 {
		padding: 0;
	}
		.leftrevbox .reatingtext {
			font-size: 36px;
			line-height: 1;
			color: #000;
			width: 50px;
			height: 37px;
			overflow: hidden;
			margin: 10px auto 0;
		}
		.ratingrow {
			padding-top: 30px;
		}
		.col-3.no-padding.fs16.fw6 {
			color: #8f8f8f;
			font-weight: normal;
			font-size: 1.3rem;
		}
		.row.leftrevbox {
			border-right: 2px solid #c4c4c4;
		}
		.accordian .accordian-header i.rango-arrow {
			display: none;
		}
		.accordian.reviaccord {
            border: none;
            padding:0 25px 20px;
			background: #fafafa;
            margin-bottom: 0;
        }
        .accordian.reviaccord h3 {
            font-weight: bold;
			margin-bottom: 15px !important;
			font-size: 30px;
			color: #336b75;
			font-style: italic;
			position: relative;
			width: 100%;
			z-index: 0;
        }
		.accordian.reviaccord h3 span {
			background: #fafafa;
			position: relative;
			z-index: 1;
			padding: 0px 25px 0px 0px;
		}
		.accordian.reviaccord h3::after {
			content: "";
			position: absolute;
			top: 50%;
			left: 0;
			width: 100%;
			height: 1px;
			background: #dcdcdc;
			transform: translateY(-50%);
			z-index: 0;
		}
		.row.customrevbox {
			padding: 15px 0 10px 0;
			border-bottom: 1px solid #c9c9c9;
		}
		.row.customrevbox .stars.mr5.fs16 .material-icons {
			font-size: 20px;
			color: #ed9d00;
		}
		.row.customrevbox .stars.mr5.fs16 {
			margin-bottom: 0px;
			max-width: 130px !important;
			float: left;
			border: none;
			margin-right: 0;
		}
		.row.customrevbox .review-description.col-lg-12 {
			font-size: 14px;
			margin-bottom: 0;
			/* max-width: 200px; */
            margin-left: 15px;
			float: left;
			font-weight: normal;
			color: #000;
            text-transform: capitalize;
		}
		.reviaccord .link-color {
			background: #2b297e !important;
			color: #fff !important;
			font-size: 15px;
			margin-top: 20px !important;
			display: inline-block;
			padding: 8px 25px;
		}
		.accdescrip {
			margin-top: 50px;
		}
		.accdescrip .accordian-content {
			border: 1px solid #ddd;
			padding: 40px;
			margin-bottom: 40px;
		}
		.accdescrip h3 {
			position: relative;
			display: inline-block;
			padding: 0 20px;
			border: 1px solid #ddd;
			border-bottom: none;
			font-size: 22px;
			font-weight: bold;
			font-style: italic;
			line-height: 42px;
			color: #aaa;
			text-decoration: none;
			text-transform: uppercase;
			cursor: pointer;
			color: #336b75;
			border-bottom: 0;
			bottom: -1px;
			background: #fff;
		}
    </style>
@endpush

@section('full-content-wrapper')
    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}
        <div class="row no-margin">
            <section class="col-12 product-detail">
                <div class="layouter">
                    <product-view>
                        <div class="form-container">
                            @csrf()

                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                            {{-- product-gallery --}}
                            <div class="left col-lg-5 col-md-5">
                                @include ('shop::products.view.gallery')
                            </div>

                            {{-- right-section --}}
                            <div class="right col-lg-7 col-md-7">
                                {{-- product-info-section --}}
                                <div class="row info">
                                    <h2 class="col-lg-12">{{ $product->name }}</h2>
                                    <p>
                                        @if( $product->Author && $product->Author!='' && $product->Author!=null ) {{$product->Author}} <span> (Author) </span>@endif
                                        @if( $product->publisher_label && $product->publisher_label!='' && $product->publisher_label!=null ){{$product->publisher_label}} <span>(Publisher)</span>@endif
                                    </p>

                                    @if ($total)
                                        <div class="reviews col-lg-12">
                                            <star-ratings
                                                push-class="mr5"
                                                :ratings="{{ $avgStarRating }}"
                                            ></star-ratings>

                                            <div class="reviews">
                                                <span>
                                                    {{ __('shop::app.reviews.ratingreviews', [
                                                        'rating' => $avgRatings,
                                                        'review' => $total])
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    {!! view_render_event('bagisto.shop.products.view.short_description.before', ['product' => $product]) !!}

                                    @if ($product->short_description)
                                        <div class="description">
                                            {{-- <h3 class="col-lg-12">{{ __('velocity::app.products.short-description') }}</h3> --}}

                                            {!! $product->short_description !!}
                                        </div>
                                    @endif


                                    {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}
                                    <div class="product-price-stock-box">
                                        <div class="row stockbox">
                                            <div class="col-sm-6 padding0">
                                                @include ('shop::products.view.stock', ['product' => $product])
                                            </div>
                                            <div class="col-sm-6 padding0">
                                                @include ('shop::products.price', ['product' => $product])
                                            </div>
                                        </div>
                                        <div class="row addcardbox">
                                            <div class="col-sm-6 padding0">
                                                @if ($product->getTypeInstance()->showQuantityBox())
                                                    <div>
                                                        <quantity-changer></quantity-changer>
                                                    </div>
                                                @else
                                                    <input type="hidden" name="quantity" value="1">
                                                @endif

                                                {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}
                                            </div>
                                            <div class="col-sm-6 product-actions padding0">
                                                @include ('shop::products.add-to-cart', [
                                                    'form' => false,
                                                    'product' => $product,
                                                    'showCartIcon' => false,
                                                    'showCompare' => false,
                                                    'showWishlist' => false,
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-sample-share-btn-box col-md-12 display-table">
                                        <div class="left  preview-sample-box display-table-cell">
                                            @if($bookPreviewValue && $bookPreviewValue!=null && $bookPreviewValue!='')
                                            <a  class="btn" href="{{ route('shop.product.file.download', [$product->product_id, $bookPreviewID])}}" style="color:black;">
<!--                                                <i class="icon rango-download-1"></i>-->
												<img src="https://darussalamstore.com/pub/media/flatIcons/readPDF.svg" alt="" style="width: 30px;height: 30px;">
												Preview Sample
                                            </a>
                                            @endif
                                        </div>
                                        <div class="right share-buttons-product-page product-actions  display-table-cell">
                                            @include ('shop::products.add-to-cart', [
                                                    'form' => false,
                                                    'product' => $product,
                                                    'showCartIcon' => false,
                                                    'showCompare' => core()->getConfigData('general.content.shop.compare_option') == "1"
                                                                        ? true : false,
                                                ])
                                        </div>
                                    </div>
                                </div>

                                @include ('shop::products.view.configurable-options')

                                @include ('shop::products.view.downloadable')

                                @include ('shop::products.view.grouped-products')

                                @include ('shop::products.view.bundle-options')

                                {{-- @include ('shop::products.view.attributes', [
                                    'active' => true
                                ]) --}}

                                {{-- product long description --}}
                            </div>

                            <div class="col-md-12">
                                @include ('shop::products.view.description')
                            </div>
                            <div class="col-md-12">
                                {{-- reviews count --}}
                                @include ('shop::products.view.faq', ['accordian' => true])
                            </div>
                            <div class="col-md-12">
                                {{-- reviews count --}}
                                @include ('shop::products.view.reviews', ['accordian' => true])
                            </div>

                        </div>

                    </product-view>
                </div>
            </section>

            <div class="related-products">
                @include('shop::products.view.related-products')
                @include('shop::products.view.up-sells')
            </div>

            <div class="store-meta-images col-3">
                @if(
                    isset($velocityMetaData['product_view_images'])
                    && $velocityMetaData['product_view_images']
                )
                    @foreach (json_decode($velocityMetaData['product_view_images'], true) as $image)
                        @if ($image && $image !== '')
                            <img src="{{ url()->to('/') }}/storage/{{ $image }}" />
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}
@endsection

@push('scripts')
    <script type='text/javascript' src='https://unpkg.com/spritespin@4.1.0/release/spritespin.js'></script>

    <script type="text/x-template" id="product-view-template">
        <form
            method="POST"
            id="product-form"
            @click="onSubmit($event)"
            action="{{ route('cart.add', $product->product_id) }}">

            <input type="hidden" name="is_buy_now" v-model="is_buy_now">

            <slot v-if="slot"></slot>

            <div v-else>
                <div class="spritespin"></div>
            </div>

        </form>
    </script>

    <script>
        Vue.component('product-view', {
            inject: ['$validator'],
            template: '#product-view-template',
            data: function () {
                return {
                    slot: true,
                    is_buy_now: 0,
                }
            },

            mounted: function () {
                let currentProductId = '{{ $product->url_key }}';
                let existingViewed = window.localStorage.getItem('recentlyViewed');

                if (! existingViewed) {
                    existingViewed = [];
                } else {
                    existingViewed = JSON.parse(existingViewed);
                }

                if (existingViewed.indexOf(currentProductId) == -1) {
                    existingViewed.push(currentProductId);

                    if (existingViewed.length > 3)
                        existingViewed = existingViewed.slice(Math.max(existingViewed.length - 4, 1));

                    window.localStorage.setItem('recentlyViewed', JSON.stringify(existingViewed));
                } else {
                    var uniqueNames = [];

                    $.each(existingViewed, function(i, el){
                        if ($.inArray(el, uniqueNames) === -1) uniqueNames.push(el);
                    });

                    uniqueNames.push(currentProductId);

                    uniqueNames.splice(uniqueNames.indexOf(currentProductId), 1);

                    window.localStorage.setItem('recentlyViewed', JSON.stringify(uniqueNames));
                }
            },

            methods: {
                onSubmit: function(event) {
                    if (event.target.getAttribute('type') != 'submit')
                        return;

                    event.preventDefault();

                    this.$validator.validateAll().then(result => {
                        if (result) {
                            this.is_buy_now = event.target.classList.contains('buynow') ? 1 : 0;

                            setTimeout(function() {
                                document.getElementById('product-form').submit();
                            }, 0);
                        }
                    });
                },
            }
        });

        window.onload = function() {
            var thumbList = document.getElementsByClassName('thumb-list')[0];
            var thumbFrame = document.getElementsByClassName('thumb-frame');
            var productHeroImage = document.getElementsByClassName('product-hero-image')[0];

            if (thumbList && productHeroImage) {
                for (let i=0; i < thumbFrame.length ; i++) {
                    thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                    thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                }

                if (screen.width > 720) {
                    thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.height = productHeroImage.offsetHeight + "px";
                }
            }

            window.onresize = function() {
                if (thumbList && productHeroImage) {

                    for(let i=0; i < thumbFrame.length; i++) {
                        thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                        thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                    }

                    if (screen.width > 720) {
                        thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                        thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                        thumbList.style.height = productHeroImage.offsetHeight + "px";
                    }
                }
            }
        };
    </script>
@endpush