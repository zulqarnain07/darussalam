@php
    $count = $velocityMetaData ? $velocityMetaData->new_products_count : 10;
    $direction = core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr';
@endphp

<new-products></new-products>

@push('scripts')
    <script type="text/x-template" id="new-products-template">
        <div class="container-fluid">
            <shimmer-component v-if="isLoading && !isMobileView"></shimmer-component>

            <template v-else-if="newProducts.length > 0">
                <card-list-header heading="{{ __('shop::app.home.new-products') }}">
                </card-list-header>
                    <div class="carousel-products vc-full-screen" v-if="!isMobileView">
                            <carousel-component
                                slides-per-page="6"
                                navigation-enabled="hide"
                                pagination-enabled="hide"
                                id="new-products-carousel"
                                locale-direction="{{ $direction }}"
                                :slides-count="newProducts.length">

                                <slide
                                    :key="index"
                                    :slot="`slide-${index}`"
                                    v-for="(product, index) in newProducts">
                                    <product-card
                                        :list="list"
                                        :product="product">
                                    </product-card>
                                </slide>
                            </carousel-component>
                        </div>
                    <div class="carousel-products vc-small-screen" v-else>
                            <carousel-component
                                slides-per-page="2"
                                navigation-enabled="hide"
                                pagination-enabled="hide"
                                id="new-products-carousel"
                                locale-direction="{{ $direction }}"
                                :slides-count="newProducts.length">

                                <slide
                                    :key="index"
                                    :slot="`slide-${index}`"
                                    v-for="(product, index) in newProducts">
                                    <product-card
                                        :list="list"
                                        :product="product">
                                    </product-card>
                                </slide>
                            </carousel-component>
                        </div>
            </template>

            @if ($count==0)
                <template>
                        @if ($showRecentlyViewed)
                            @push('css')
                                <style>
                                    .recently-viewed {
                                        padding-right: 0px;
                                    }
                                </style>
                            @endpush

                            <div class="row {{ $direction }}">
                                <div class="col-9 no-padding carousel-products vc-full-screen with-recent-viewed" v-if="!isMobileView"></div>

                                @include ('shop::products.list.recently-viewed', [
                                    'quantity'          => 3,
                                    'addClass'          => 'col-lg-3 col-md-12',
                                ])
                            </div>
                        @endif
                </template>
            @endif
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('new-products', {
                'template': '#new-products-template',
                data: function () {
                    return {
                        'list': false,
                        'isLoading': true,
                        'newProducts': [],
                        'isMobileView': this.$root.isMobile(),
                    }
                },

                mounted: function () {
                    this.getNewProducts();
                },

                methods: {
                    'getNewProducts': function () {
                        this.$http.get(`${this.baseUrl}/category-details?category-slug=new-products&count={{ $count }}`)
                        .then(response => {
                            //console.log(response);
                             var count = '{{$count}}';
                            if (response.data.status && count != 0){
                                //console.log(response.data.products);
                                this.newProducts = response.data.products;
                            }else{
                                this.newProducts = 0;
                            }

                            this.isLoading = false;
                        })
                        .catch(error => {
                            this.isLoading = false;
                            console.log(this.__('error.something_went_wrong'));
                        })
                    }
                }
            })
        })()
    </script>
@endpush