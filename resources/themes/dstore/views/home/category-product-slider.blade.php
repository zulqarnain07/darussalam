@php
    $direction = core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr';
@endphp

<category-products title="{{ $title }}"></category-products>
@push('scripts')
    <script type="text/x-template" id="category-products-template-{{ $category }}">
        <div class="container-fluid category-products">
            <shimmer-component v-if="isLoading && !isMobileView"></shimmer-component>

            <template >

                <div class="row mb15 col-12 undefined">
                    <div class="col-4 no-padding">
                        <h2 class="fs20 fw6">@{{ title }}</h2>
                    </div>
                    <div class="col-8 no-padding">
                        <div class="row justify-content-end text-right">
                            <div>
                                <a href="{{ url($category) }}" title="View all @{{ title }} products" class="remove-decoration link-color">
                                    <h2 class="fs16 fw6 cursor-pointer tab">View All</h2>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="carousel-products vc-full-screen {{ $direction }} " v-if="!isMobileView">
                    <carousel-component
                        slides-per-page="6"
                        navigation-enabled="show"
                        pagination-enabled="hide"
                        id="fearured-products-carousel-{{ $category }}"
                        locale-direction="{{ $direction }}"
                        :autoplay="false"
                        :slides-count="categoryProducts.length">

                        <slide
                            :key="index"
                            :slot="`slide-${index}`"
                            v-for="(product, index) in categoryProducts">
                            <product-card
                                :list="list"
                                :product="product">
                            </product-card>
                        </slide>
                    </carousel-component>
                </div>

                <div class="carousel-products vc-small-screen {{ $direction }}" v-else>
                    <carousel-component
                        slides-per-page="2"
                        navigation-enabled="hide"
                        pagination-enabled="hide"
                        id="fearured-products-carousel-{{ $category }}"
                        locale-direction="{{ $direction }}"
                        :slides-count="categoryProducts.length">

                        <slide
                            :key="index"
                            :slot="`slide-${index}`"
                            v-for="(product, index) in categoryProducts">
                            <product-card
                                :list="list"
                                :product="product">
                            </product-card>
                        </slide>
                    </carousel-component>
                </div>
            </template>

        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('category-products', {
                props: ['title'],
                'template': '#category-products-template-{{ $category }}',

                data: function () {
                    return {
                        'list': false,
                        'isLoading': true,
                        'categoryProducts': [],
                        'categories': [],
                        'isMobileView': this.$root.isMobile(),
                        'category': '',
                        'title': ''
                    }
                },

                mounted: function () {
                    this.getCategoryProducts();
                },

                methods: {
                    'getCategoryProducts': function () {
                        this.$http.get(`${this.baseUrl}/category-details?category-slug={{$category}}&count=20`)
                        .then(response => {
                            var count = 20;
                            if (response.data.status && count != 0 )
                            {
                                this.categoryProducts = response.data.categoryProducts;
                                this.category = '{{ $category }}';
                                this.title1 = '{{ $title }}';
                            }else{
                                this.categoryProducts = 0;
                                this.category = '{{ $category }}';
                                this.title = '{{ $title }}';
                            }

                            this.isLoading = false;
                        })
                        .catch(error => {
                            this.isLoading = false;
                            console.log(this.__('error.something_went_wrong'));
                        })
                    }
                }
            });
        })()
    </script>
@endpush
