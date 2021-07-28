{!! view_render_event('bagisto.shop.layout.header.account-item.before') !!}
    <login-header></login-header>
{!! view_render_event('bagisto.shop.layout.header.account-item.after') !!}
<style>

</style>
<script type="text/x-template" id="login-header-template">
    <div class="dropdown">
        <div id="account">

            <div class="welcome-content pull-right" @click="togglePopup">
                <i class="material-icons align-vertical-top">perm_identity</i>
                <span class="text-center">
                    @guest('customer')
                        {{ __('velocity::app.header.welcome-message', ['customer_name' => trans('velocity::app.header.guest')]) }}!
                    @endguest

                    @auth('customer')
                        {{ __('velocity::app.header.welcome-message', ['customer_name' => auth()->guard('customer')->user()->first_name]) }}
                    @endauth
                </span>
                <span class="select-icon rango-arrow-down"></span>
            </div>
            @php
                $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false
            @endphp

            {!! view_render_event('bagisto.shop.layout.header.compare.before') !!}
                @if ($showCompare)
                    <a
                        class="compare-btn unset pull-right"
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
                <a class="wishlist-btn unset pull-right" :href="`${isCustomer ? '{{ route('customer.wishlist.index') }}' : '{{ route('velocity.product.guest-wishlist') }}'}`">
                    <i class="material-icons">favorite_border</i>
                    <div class="badge-container" v-if="wishlistCount > 0">
                        <span class="badge" v-text="wishlistCount"></span>
                    </div>
                    <span>{{ __('shop::app.layouts.wishlist') }}</span>
                </a>
            {!! view_render_event('bagisto.shop.layout.header.wishlist.after') !!}
        </div>

        <div class="account-modal sensitive-modal hide mt5">
            <!--Content-->
                @guest('customer')
                    <div class="modal-content">
                        <!--Header-->
                        <div class="modal-header no-border pb0">
                            <label class="fs18 grey">{{ __('shop::app.header.title') }}</label>

                            <button type="button" class="close disable-box-shadow" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="white-text fs20" @click="togglePopup">×</span>
                            </button>
                        </div>

                        <!--Body-->
                        <div class="pl10 fs14">
                            <p>{{ __('shop::app.header.dropdown-text') }}</p>
                        </div>

                        <!--Footer-->
                        <div class="modal-footer">
                            <div>
                                <a href="{{ route('customer.session.index') }}">
                                    <button
                                        type="button"
                                        class="theme-btn fs14 fw6">

                                        {{ __('shop::app.header.sign-in') }}
                                    </button>
                                </a>
                            </div>

                            <div>
                                <a href="{{ route('customer.register.index') }}">
                                    <button
                                        type="button"
                                        class="theme-btn fs14 fw6">
                                        {{ __('shop::app.header.sign-up') }}
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endguest

                @auth('customer')
                    <div class="modal-content customer-options">
                        <div class="customer-session">
                            <label class="">
                                {{ auth()->guard('customer')->user()->first_name }}
                            </label>
                        </div>

                        <ul type="none">
                            <li>
                                <a href="{{ route('customer.profile.index') }}" class="unset">{{ __('shop::app.header.profile') }}</a>
                            </li>

                            <li>
                                <a href="{{ route('customer.orders.index') }}" class="unset">{{ __('velocity::app.shop.general.orders') }}</a>
                            </li>

                            <li>
                                <a href="{{ route('customer.wishlist.index') }}" class="unset">{{ __('shop::app.header.wishlist') }}</a>
                            </li>

                            @php
                                $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false
                            @endphp

                            @if ($showCompare)
                                <li>
                                    <a href="{{ route('velocity.customer.product.compare') }}" class="unset">{{ __('velocity::app.customer.compare.text') }}</a>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('customer.session.destroy') }}" class="unset">{{ __('shop::app.header.logout') }}</a>
                            </li>
                        </ul>logo
                    </div>
                @endauth
            <!--/.Content-->
        </div>
        <div class="dropdown pull-right">
            <button class="btn btn-link " type="button" id="ResourcesButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                GBP <i class="fa fa-angle-down"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="ResourcesButton">
                <a class="dropdown-item" href="#">EUR - Euro</a>
                <a class="dropdown-item" href="#">SAR - Saudi Riyal</a>
                <a class="dropdown-item" href="#">USD - US Dollar</a>
                <a class="dropdown-item" href="#">AED - United Arab Emirates Dirham</a>
            </div>
        </div>
    </div>
</script>

@push('scripts')
    <script type="text/javascript">

        Vue.component('login-header', {
            template: '#login-header-template',
            data: function () {
                return {
                    compareCount: 0,
                    wishlistCount: 0,
                    isCustomer: '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                }
            },
            watch: {
                '$root.headerItemsCount': function () {
                    this.updateHeaderItemsCount();
                }
            },

            created: function () {


                this.updateHeaderItemsCount();
            },
            methods: {
                togglePopup: function (event) {
                    let accountModal = this.$el.querySelector('.account-modal');
                    let modal = $('#cart-modal-content')[0];

                    if (modal)
                        modal.classList.add('hide');

                    accountModal.classList.toggle('hide');

                    event.stopPropagation();
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
            }
        })

    </script>
@endpush

