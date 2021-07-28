@extends('shop::layouts.master')

@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper')


@php
use Illuminate\Support\Str;
    $total = $reviewHelper->getTotalReviews($product);

    $avgRatings = $reviewHelper->getAverageRating($product);
    $avgStarRating = round($avgRatings);

    $productImages = [];
    $images = productimage()->getGalleryImages($product);

    foreach ($images as $key => $image) {
        array_push($productImages, $image['medium_image_url']);
    }
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

    <?php $productBaseImage = productimage()->getProductBaseImage($product, $images); ?>

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

        .buynow {
            height: 40px;
            text-transform: uppercase;
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

                            <div class="row">
                                {{-- product-gallery --}}
                                <div class="left col-lg-5 col-md-6">
                                    @include ('shop::products.view.gallery')
                                </div>

                                {{-- right-section --}}
                                <div class="right col-lg-7 col-md-6">
                                    {{-- product-info-section --}}
                                    <div class="row info">
                                        <h2 class="col-12">{{ $product->name }}</h2>

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

                                        @include ('shop::products.view.stock', ['product' => $product])

                                        <div class="col-12 price">
                                            @include ('shop::products.price', ['product' => $product])
                                        </div>

                                        @if (count($product->getTypeInstance()->getCustomerGroupPricingOffers()) > 0)
                                            <div class="col-12">
                                                @foreach ($product->getTypeInstance()->getCustomerGroupPricingOffers() as $offers)
                                                    {{ $offers }} </br>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="product-actions">
                                            @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
                                                @include ('shop::products.buy-now', [
                                                    'product' => $product,
                                                ])
                                            @endif

                                            @include ('shop::products.add-to-cart', [
                                                'form' => false,
                                                'product' => $product,
                                                'showCartIcon' => false,
                                                'showCompare' => core()->getConfigData('general.content.shop.compare_option') == "1"
                                                                ? true : false,
                                            ])
                                        </div>
                                    </div>

                                    {!! view_render_event('bagisto.shop.products.view.short_description.before', ['product' => $product]) !!}

                                    @if ($product->short_description)
                                        <div class="description">
                                           
                                            <h3 class="col-lg-12">{{ __('velocity::app.products.short-description') }}</h3>

                                            {!! $product->short_description !!}
                                        </div>
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.short_description.after', ['product' => $product]) !!}


                                    {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                    @if ($product->getTypeInstance()->showQuantityBox())
                                        <div>
                                            <quantity-changer></quantity-changer>
                                        </div>
                                    @else
                                        <input type="hidden" name="quantity" value="1">
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                                    @include ('shop::products.view.configurable-options')

                                    @include ('shop::products.view.downloadable')

                                    @include ('shop::products.view.grouped-products')

                                    @include ('shop::products.view.bundle-options')

                                    @include ('shop::products.view.attributes', [
                                        'active' => true
                                    ])

                                    {{-- product long description --}}
                                    @include ('shop::products.view.description')
                                    
                                    {{-- reviews count --}}
                                    @include ('shop::products.view.reviews', ['accordian' => true])
                                   
                                    
                                   
                                 
                                </div>
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
                            <img src="{{ url()->to('/') }}/storage/{{ $image }}" alt=""/>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}
  <!--start of faqs section-->
   

                <!--end of faqs section-->
                <section class="py-5">
                <div class="container">
                    <div class="row">
    <div class="col-md-12">
        <input type="text" style="font-size: 20px;" class="form-control" name="search" id="search" placeholder="Have a question? Search for answers" />
    </div>
    <?php
  for($i=0; $i<5; $i++)
  {
      ?>
<div class="col-md-12 py-5">
    <div class="container">
        <div class="col-md-2">
            <i style="     cursor: pointer;   font-size: 35px;
            font-weight: bold;" class="fa fa-sort-up"></i>
               <p class="fqsp"> <b> Vote  {{$i}} </b> </p>
            <i style="    cursor: pointer;    font-size: 35px;
            font-weight: bold;" class="fa fa-sort-down"></i>
    
        </div>
    <div class="col-md-10">
        <p style="font-size: 20px;
        font-weight: bold;">Question :  <a style="float: right" href="javascript::void(0)">How many pages does this book contain?</a></p>
       <a style="float: right;     font-size: 15px;" href="javascript::void(0)">Add new Answer</a>
        <p style="font-size: 20px;
        font-weight: bold; margin-top: 19px;" class="pt-4">Answer :</p>
        <span>
            <p style="font-size: 20px" class="pt-1">This book contains 576 Pages.</p>
            <p style="font-size: 18px"><b>By Admin - on August 13, 2020</b></p>
            
        </span>
           
    </div>
    
    </div>
    <hr>
        </div>
       
      <?php
  }

    ?>

                    </div>
                </div>
                </section>
    <section
    style="    background: #fafafa;"
    >
        <div class="container">
            <div class="row">
              
                <div class="col-md-12">
                    <div class="py-3">
                        <strong style="    position: relative;
                        display: inline-block;
                        padding: 0 20px 0 0;
                        font-size: 22px;
                        font-weight: bold;
                        font-style: italic;
                        line-height: 42px;
                        color: #aaa;
                        text-decoration: none;
                        text-transform: uppercase;
                        cursor: pointer;
                        z-index: 1;
                        color: #336b75;
                        border-bottom: 0;
                    ">
                            Customer Reviews        </strong>
                            
                           
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-2">
                        <h3 style="font-size: 36px;"><strong>4.8</strong></h3>    
                        </div>   
                        <div class="col-md-4">
                            <?php
                            for($i=0; $i<5; $i++)
                            {
                                ?>
 <i style="font-size: 28px;     color: chartreuse;" class="fa fa-star"></i>
<?php
                            }

                            ?>
                           
                           
                            <p style="    font-size: 16px;" >5 reviews</p>

                        </div>
                        <div class="col-md-6">
                        <!--first--> 
                          <span style="font-size: 13px;">5 stars <span style="float: right">80% (4)</span> </span>
                          <span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning progress-bar-striped bg-warning" role="progressbar"
                                aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%">
                                 
                                </div>
                              </div>
                          </span>
                         
                          <!--end of first-->
                          <span style="font-size: 13px;">4 stars <span style="float: right">60% (3)</span> </span>
                          <span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning progress-bar-striped bg-warning" role="progressbar"
                                aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:60%">
                                 
                                </div>
                              </div>
                          </span>
                          <!--end of second-->
                          <span style="font-size: 13px;">3 stars <span style="float: right">0% (0)</span> </span>
                          <span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning progress-bar-striped bg-warning" role="progressbar"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                 
                                </div>
                              </div>
                          </span>
                          <!--end of thired-->
                          <span style="font-size: 13px;">2 stars <span style="float: right">0% (0)</span> </span>
                          <span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning progress-bar-striped bg-warning" role="progressbar"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                 
                                </div>
                              </div>
                          </span>
                          <!--end of fourth-->
                          <span style="font-size: 13px;">1 star <span style="float: right">0% (0)</span> </span>
                          <span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning progress-bar-striped bg-warning" role="progressbar"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                 
                                </div>
                              </div>
                          </span>
                          <!--end of fifth-->
                        </div>     
                    </div>
                    <div class="col-md-6">
                        @include ('shop::products.view.reviews', ['accordian' => true])
                        <p style="font-size: 15px;">Share your thoughts with other customers</p>  
                    </div>
                </div>
                <div class="col-md-12">
                    
                    <p class="topcreview pt-3">Top Customer Reviews</p>
                </div>
                <div class="col-md-12 py-5">
                    <div style="text-align: right;">
                        <input type="checkbox" name="verify" id="verify"  />
                        <label class="verify_label">Verifed Buyers</label>
                        <span class="px-3"> | </span>
                        <label class="verify_label px-3">Sort By</label>
                        <select style="width: 10%;
                        height: 71%;
                        font-size: 14px;">
                            <option>Date</option>
                            <option>Rating</option>
                        </select>
                    </div>
                    
                </div>
                    
                   <?php
for($i=0; $i<5; $i++)
{
    $ark = array('Nice','Learn full','Must be Read','Very Helpfull','ideal');
    $name = array('rohan ali','Ali khan','Saeed mustafa','Noman Afzal','Kamran Khan');
    $content = array(
   'I have just ended up with this book and loved the fact that it is very precise with reference to its topic. It is all about what is happening in our surroundings. A reader friendly language is used which makes it even more interesting. The format and layout is also a treat for eyes. Suggested for all open minded people who are willing to get the trusted and factual knowledge of this topic. I wish Darussalam continues its efforts to bring more books like this.',
   '"This book describes the events of the last days of the life of the universe.Although I read a lot on this topic, this book added to my information a lot of what I did not know, and was even surprised and shocked me For me, I considered this book after reading it as a reference for me. Whenever I need a hadith or verse about this topic, I return to it to extract it from there.May God reward the author ~ A book worth reading"',
   '
I was encouraged to read it after I found it on the top of the best-selling books in Jarir .. Also what was written on the cover with pictures, maps and clarifications.',
'
I learned a lot from this book, and it invoked me to think a lot of things. For example, when the author mentioned that one of the signs of the hour is the spread of ignorance',
'"The fact that I expected it to take me at least four days and was surprised that I could do it in about a day, I started with it yesterday evening, and I finished it today, and this book mainly focuses on the remembrance of God. I have heard audio lectures about the signs of the hour, but for the first time I understood such topics in a clear and better manner"
'

    )
    ?>
   <div class="col-md-12">
    <img src="https://static.thenounproject.com/png/538846-200.png" style="    height: 25px;
    width: 27px; border-radius: 34px;
border: 2px solid #fff;
background-color: #fff;
vertical-align: top;" ><span style="font-size: 17px;
position: relative;
color: #111;
unicode-bidi: isolate;" class="px-3">{{$name[$i]}}</span>
<div class="py-4">
<i style="font-size: 20px;     color: chartreuse;" class="fa fa-star"></i>
<i style="font-size: 20px;     color: chartreuse;" class="fa fa-star"></i>
<i style="font-size: 20px; color: chartreuse;" class="fa fa-star"></i>
<i style="font-size: 20px;     color: chartreuse;" class="fa fa-star"></i>
<i style="font-size: 20px; color: chartreuse;" class="fa fa-star-o"></i>
<span style="    font-size: 20px;"><b>{{$ark[$i]}}</b></span>
<p style="font-size: 15px;">{{$i+1}} Feb 2020</p>
<p style="font-size: 15px;" class="py-2">{{$content[$i]}}</p>
<div class="p-3">
<button style="     cursor: pointer;   font-size: 14px;" type="button" class="btn btn-outline-secondary">Help Full <i style="    font-size: 20px;" class="fa fa-thumbs-up"></i></button>
<button style="      cursor: pointer;  font-size: 14px;" type="button" class="btn btn-outline-primary px-3"> <i class="fa fa-reply" aria-hidden="true"></i> Replay</button>
<span style="     cursor: pointer;   color: blue;
font-size: 17px;">Comments ({{ $i }})</span>
</div>
</div>
</div>
<hr style="width:100%;text-align:left;margin-left:0">
    <?php
}
                   ?>
                 
        

            </div>
        </div>
    
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