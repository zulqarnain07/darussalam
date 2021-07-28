@inject ('faqHelper', 'Webkul\Product\Helpers\Faq')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper')

@php
    if (! isset($total)) {
        $total = $faqHelper->getTotalFaq($product);
    }
@endphp

{!! view_render_event('bagisto.shop.products.faq.before', ['product' => $product]) !!}

        @if (isset($accordian) && $accordian)
            <accordian class="reviaccord" :title="'{{ __('shop::app.products.total-reviews') }}'" :active="true">
                {{-- customer reviews --}}
                <div slot="header" class="col-lg-12 no-padding">
                    {{-- <h3 class="display-inbl">
                        {{-- <span>{{ __('velocity::app.products.reviews-title') }}</span> 
                    </h3> --}}

                    <i class="rango-arrow"></i>
                </div>

                <div class="customer-reviews" slot="body">
                    @foreach ($faqHelper->getFaqs($product)->paginate(10) as $review)
                        <div class="row customrevbox">
                            <h4 class="col-lg-4" style="text-align: right"><b>Question</b></h4> 
                            <h4 class="col-lg-8 fs18">{{ $review->question }}</h4>
                            <h4 class="col-lg-4" style="text-align: right"><b>Answer</b></h4>
                            <div class="review-description col-lg-8 padding0">
                              <p><span>{!! $review->answer !!}</span></p>  
                            </div>

                            <div class="col-lg-12 mt5 col-sm-offset-4">
                                <span> <b>By</b></span>
                                <b>
                                <span class="fs16 fw6">
                                   {{ $review->name }},
                                </span>

                                <span>{{ core()->formatDate($review->created_at, 'F d, Y') }}
                                </span></b>
                            </div>
                        </div>
                    @endforeach

                    {{-- <a
                        href="{{ route('shop.reviews.index', ['slug' => $product->url_key ]) }}"
                        class="mb20 link-color"
                    >{{ __('velocity::app.products.view-all-reviews') }}</a> --}}
                </div>
            </accordian>
        {{-- @else
            <h3 class="display-inbl mb20 col-lg-12 no-padding">
                {{ __('velocity::app.products.reviews-title') }}
            </h3>

            <div class="customer-reviews">
                @foreach ($faqHelper->getFaqs($product)->paginate(10) as $review)
                    <div class="row">
                        <h4 class="col-lg-12 fs18">{{ $review->question }}</h4>


                        <div class="review-description col-lg-12">
                            <span>{{ $review->answer }}</span>
                        </div>

                        <div class="col-lg-12 mt5">
                            @if ("{{ $review->name }}")
                                <span>{{ __('velocity::app.products.review-by') }} -</span>

                                <label>
                                    {{ $review->name }},
                                </label>
                            @endif

                            <span>{{ core()->formatDate($review->created_at, 'F d, Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div> --}}
        @endif
      
{!! view_render_event('bagisto.shop.products.faq.after', ['product' => $product]) !!}