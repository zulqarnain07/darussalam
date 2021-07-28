<style>
    .camera-icon {
        background-image: url("{{ asset('/vendor/webkul/ui/assets/images/Camera.svg') }}");
    }
    .search-suggestions {
        top: 45px;
        background: white;
        position: absolute;
        width: 60%;
        list-style: none;
        height: auto !important;
        max-height: 300px;
        overflow: auto;
        left: 0;
    }
    .search-suggestions li{
        height:auto !important;
    }
    .search-suggestions li a.row {
        margin: auto;
        float: left;
    }
    .search-suggestions a.row:hover {
        background: #eaeaea;
    }
    .search-suggestions li a.row img {
        width: 100%;
    }
    .search-suggestions li p.search-title {
        color: black;
        font-size: 15px;
        margin: auto;
    }
    .search-suggestions li p.search-price {
        font-size: 16px;
        float: right;
    }
    .search-price {
        color:black;
    }
    .search-price .regular-price {
        text-decoration: line-through;
        padding: 0px 5px;
    }
    .sticky-header .velocity-divide-page{
        height:90px;
        background: #fff;
        text-align: center;
    }
    /* .sticky-header .velocity-divide-page a{
        width:100%;

    } */
    header #search-form #header-search-icon {
            position: absolute;
    }
    .welcome-content.pull-right .badge-container .badge{
        border-radius: 50%;
        top: 0px;
        padding: 3px;
        position: absolute;
        background: #21a179;
    }
</style>

<script type="text/x-template" id="cart-btn-template">
    <button
        type="button"
        id="mini-cart"
        @click="toggleMiniCart"
        :class="`btn btn-link disable-box-shadow ${itemCount == 0 ? 'cursor-not-allowed' : ''}`">

        <div class="mini-cart-content">
            <i class="material-icons-outlined text-down-3">shopping_cart</i>
            <span class="badge" v-text="itemCount" v-if="itemCount != 0"></span>
            <span class="fs18 fw6 cart-text">{{ __('velocity::app.minicart.cart') }}</span>
        </div>
        <div class="down-arrow-container">
            <span class="rango-arrow-down"></span>
        </div>
    </button>
</script>

<script type="text/x-template" id="close-btn-template">
    <button type="button" class="close disable-box-shadow">
        <span class="white-text fs20" @click="togglePopup">×</span>
    </button>
</script>

<script type="text/x-template" id="quantity-changer-template">
    <div :class="`quantity control-group ${errors.has(controlName) ? 'has-error' : ''}`">
        <label class="required">{{ __('shop::app.products.quantity') }}</label>
        <button type="button" class="decrease" @click="decreaseQty()">-</button>

        <input
            :value="qty"
            class="control"
            :name="controlName"
            :v-validate="validations"
            data-vv-as="&quot;{{ __('shop::app.products.quantity') }}&quot;"
            readonly />

        <button type="button" class="increase" @click="increaseQty()">+</button>

        <span class="control-error" v-if="errors.has(controlName)">@{{ errors.first(controlName) }}</span>
    </div>
</script>

@include('velocity::UI.header')

<script type="text/x-template" id="logo-template">
    <a
        :class="`left ${addClass}`"
        href="{{ route('shop.home.index') }}">

        @if ($logo = core()->getCurrentChannel()->logo_url)
            <img class="logo" src="{{ $logo }}" />
        @else
            <img class="logo" src="{{ asset('themes/velocity/assets/images/logo-text.png') }}" />
        @endif
    </a>
</script>

<script type="text/x-template" id="searchbar-template">
    <div class="row no-margin  searchbar pull-right">
        <div class="col-lg-5 col-md-12 no-padding input-group">
            <form
                method="GET"
                role="search"
                id="search-form"
                action="{{ route('velocity.search.index.ajax') }}">

                <div
                    class="btn-toolbar full-width"
                    role="toolbar">

                    <div class="btn-group full-width">
                        {{-- <div class="selectdiv">
                             <select class="form-control fs13 styled-select" name="category" @change="focusInput($event)">
                                <option value="">
                                    {{ __('velocity::app.header.all-categories') }}
                                </option>

                                <template v-for="(category, index) in $root.sharedRootCategories">
                                    <option
                                        :key="index"
                                        selected="selected"
                                        :value="category.id"
                                        v-if="(category.id == searchedQuery.category)">
                                        @{{ category.name }}
                                    </option>

                                    <option :key="index" :value="category.id" v-else>
                                        @{{ category.name }}
                                    </option>
                                </template>
                            </select>

                            <div class="select-icon-container">
                                <span class="select-icon rango-arrow-down"></span>
                            </div>
                        </div>--}}

                        <div class="full-width">
                            <input
                                required
                                name="term"
                                type="search"
                                class="form-control"
                                placeholder="{{ __('velocity::app.header.search-text') }}"
                                :value="searchedQuery.term ? searchedQuery.term.split('+').join(' ') : ''"
                                @input="ajaxSearch($event)"
                                v-model="searchTerm"/>

                            <!--<image-search-component></image-search-component>-->

                            <button class="btn" type="submit" id="header-search-icon">
                                <i class="fs16 fw6 rango-search"></i>
                            </button>
                            <ul v-if="results.length > 0" class="search-suggestions">
                                <li>

                                </li>
                                <li v-for="result in results" :key="result.id">
                                    <a target="_blank" v-bind:href="result.url_key" class="row">
                                        <div class="col-xs-4">
                                            <img v-bind:src="result.image"/>
                                        </div>
                                        <div class="col-xs-8">
                                            <p class="search-title" v-text="results.length+result.name" > </p>
                                            <p class="search-price" v-html="result.priceHtml" > </p>
                                        </div>
                                    </a>

                                </li>
                            </ul>
                            <ul v-else class="search-suggestions" style="display:none">

                            </ul>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div class="col-lg-7 col-md-12">
            {!! view_render_event('bagisto.shop.layout.header.cart-item.before') !!}
                @include('shop::checkout.cart.mini-cart')
            {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}

            {{-- @php
                $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false
            @endphp

            {!! view_render_event('bagisto.shop.layout.header.compare.before') !!}
                @if ($showCompare)
                    <a
                        class="compare-btn unset"
                        @auth('customer')
                            href="{{ route('velocity.customer.product.compare') }}"
                        @endauth

                        @guest('customer')
                            href="{{ route('velocity.product.compare') }}"
                        @endguest
                        >

                        <i class="material-icons">compare_arrows</i>
                        <div class="badge-container" v-if="compareCount > 0">
                            <span class="badge" v-text="compareCount"></span>
                        </div>
                        <span>{{ __('velocity::app.customer.compare.text') }}</span>
                    </a>
                @endif
            {!! view_render_event('bagisto.shop.layout.header.compare.after') !!}

            {!! view_render_event('bagisto.shop.layout.header.wishlist.before') !!}
                <a class="wishlist-btn unset" :href="`${isCustomer ? '{{ route('customer.wishlist.index') }}' : '{{ route('velocity.product.guest-wishlist') }}'}`">
                    <i class="material-icons">favorite_border</i>
                    <div class="badge-container" v-if="wishlistCount > 0">
                        <span class="badge" v-text="wishlistCount"></span>
                    </div>
                    <span>{{ __('shop::app.layouts.wishlist') }}</span>
                </a>
            {!! view_render_event('bagisto.shop.layout.header.wishlist.after') !!} --}}
        </div>
    </div>
</script>

<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet"></script>

<script type="text/x-template" id="image-search-component-template">
    <div class="d-inline-block">
        <label class="image-search-container" for="image-search-container">
            <i class="icon camera-icon"></i>

            <input
                type="file"
                class="d-none"
                ref="image_search_input"
                id="image-search-container"
                v-on:change="uploadImage()" />

            <img
                class="d-none"
                id="uploaded-image-url"
                :src="uploadedImageUrl" />
        </label>
    </div>
</script>

<script type="text/javascript">
    (() => {
        Vue.component('cart-btn', {
            template: '#cart-btn-template',

            props: ['itemCount'],

            methods: {
                toggleMiniCart: function () {
                    let modal = $('#cart-modal-content')[0];
                    if (modal)
                        modal.classList.toggle('hide');

                    let accountModal = $('.account-modal')[0];
                    if (accountModal)
                        accountModal.classList.add('hide');

                    event.stopPropagation();
                }
            }
        });

        Vue.component('close-btn', {
            template: '#close-btn-template',

            methods: {
                togglePopup: function () {
                    $('#cart-modal-content').hide();
                }
            }
        });

        Vue.component('quantity-changer', {
            template: '#quantity-changer-template',
            inject: ['$validator'],
            props: {
                controlName: {
                    type: String,
                    default: 'quantity'
                },

                quantity: {
                    type: [Number, String],
                    default: 1
                },

                minQuantity: {
                    type: [Number, String],
                    default: 1
                },

                validations: {
                    type: String,
                    default: 'required|numeric|min_value:1'
                }
            },

            data: function() {
                return {
                    qty: this.quantity
                }
            },

            watch: {
                quantity: function (val) {
                    this.qty = val;

                    this.$emit('onQtyUpdated', this.qty)
                }
            },

            methods: {
                decreaseQty: function() {
                    if (this.qty > this.minQuantity)
                        this.qty = parseInt(this.qty) - 1;

                    this.$emit('onQtyUpdated', this.qty)
                },

                increaseQty: function() {
                    this.qty = parseInt(this.qty) + 1;

                    this.$emit('onQtyUpdated', this.qty)
                }
            }
        });

        Vue.component('logo-component', {
            template: '#logo-template',
            props: ['addClass'],
        });
        var cancel;
        var CancelToken = axios.CancelToken;
        Vue.component('searchbar-component', {
            template: '#searchbar-template',
            data: function () {
                return {
                    compareCount: 0,
                    wishlistCount: 0,
                    searchTerm: '',
                    results: '',
                    searchedQuery: [],
                    isCustomer: '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                }
            },

            watch: {
                '$root.headerItemsCount': function () {
                    this.updateHeaderItemsCount();
                }
            },

            created: function () {
                let searchedItem = window.location.search.replace("?", "");
                searchedItem = searchedItem.split('&');

                let updatedSearchedCollection = {};

                searchedItem.forEach(item => {
                    let splitedItem = item.split('=');
                    updatedSearchedCollection[splitedItem[0]] = decodeURI(splitedItem[1]);
                });

                if (updatedSearchedCollection['image-search'] == 1) {
                    updatedSearchedCollection.term = '';
                }

                this.searchedQuery = updatedSearchedCollection;

                this.updateHeaderItemsCount();
            },

            methods: {
                'focusInput': function (event) {
                    $(event.target.parentElement.parentElement).find('input').focus();
                },

                'updateHeaderItemsCount': function () {
                    if (! this.isCustomer) {
                        let comparedItems = this.getStorageValue('compared_product');
                        let wishlistedItems = this.getStorageValue('wishlist_product');

                        if (wishlistedItems) {
                            this.wishlistCount = wishlistedItems.length;
                        }

                        if (comparedItems) {
                            this.compareCount = comparedItems.length;
                        }
                    } else {
                        this.$http.get(`${this.$root.baseUrl}/items-count`)
                            .then(response => {
                                this.compareCount = response.data.compareProductsCount;
                                this.wishlistCount = response.data.wishlistedProductsCount;
                            })
                            .catch(exception => {
                                console.log(this.__('error.something_went_wrong'));
                            });
                    }
                },

                'ajaxSearch': function(event) {
                    if(this.searchTerm != '' && this.searchTerm != null){
                        // axios.get('{{ route("velocity.search.index.ajax") }}', { params: { term: this.searchTerm } })
                        //     .then(response => this.results = response.data)
                        //     .catch(error => {});
                        if (cancel != undefined) {
                            cancel();
                        }
                        axios({
                            method: "get",
                            url: '{{ route("velocity.search.index.ajax") }}',
                            cancelToken: new CancelToken(function executor(c) {
                                cancel = c;
                            }),
                            params: {
                                term: this.searchTerm
                            }
                        }).then(
                            response => this.results = response.data
                        ).catch(
                            error => {}
                        );
                    }else{
                        this.results = [];
                    }
                },

            }
        });

        Vue.component('image-search-component', {
            template: '#image-search-component-template',
            data: function() {
                return {
                    uploadedImageUrl: ''
                }
            },

            methods: {
                uploadImage: function() {
                    var imageInput = this.$refs.image_search_input;

                    if (imageInput.files && imageInput.files[0]) {
                        if (imageInput.files[0].type.includes('image/')) {
                            this.$root.showLoader();

                            var formData = new FormData();

                            formData.append('image', imageInput.files[0]);

                            axios.post(
                                "{{ route('shop.image.search.upload') }}",
                                formData,
                                {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                }
                            ).then(response => {
                                var net;
                                var self = this;
                                this.uploadedImageUrl = response.data;


                                async function app() {
                                    var analysedResult = [];

                                    var queryString = '';

                                    net = await mobilenet.load();

                                    const imgElement = document.getElementById('uploaded-image-url');

                                    try {
                                        const result = await net.classify(imgElement);

                                        result.forEach(function(value) {
                                            queryString = value.className.split(',');

                                            if (queryString.length > 1) {
                                                analysedResult = analysedResult.concat(queryString)
                                            } else {
                                                analysedResult.push(queryString[0])
                                            }
                                        });
                                    } catch (error) {
                                        self.$root.hideLoader();

                                        window.showAlert(
                                            `alert-danger`,
                                            this.__('shop.general.alert.error'),
                                            "{{ __('shop::app.common.error') }}"
                                        );
                                    }

                                    localStorage.searchedImageUrl = self.uploadedImageUrl;

                                    queryString = localStorage.searched_terms = analysedResult.join('_');

                                    self.$root.hideLoader();

                                    window.location.href = "{{ route('shop.search.index') }}" + '?term=' + queryString + '&image-search=1';
                                }

                                app();
                            }).catch(() => {
                                this.$root.hideLoader();

                                window.showAlert(
                                    `alert-danger`,
                                    this.__('shop.general.alert.error'),
                                    "{{ __('shop::app.common.error') }}"
                                );
                            });
                        } else {
                            imageInput.value = '';

                            alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                        }
                    }
                }
            }
        });
    })()
</script>