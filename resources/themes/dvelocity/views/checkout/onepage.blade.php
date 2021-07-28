@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.onepage.title') }}
@stop

@section('content-wrapper')
    <checkout></checkout>
@endsection

@push('scripts')
    @include('shop::checkout.cart.coupon')

    <script type="text/x-template" id="checkout-template">
        <div class="container">
            <div id="checkout" class="checkout-process row col-md-12">
                <div class="w3-panel w3-note col-md-12">
                    <p><strong>Note!</strong> Delivery Only available in UK. For delivery outside Uk <a href="{{url('page/contact-us')}}">click here</a></p>
                  </div>
                <div class="col-lg-8 col-md-12">
                    <ul id="progressbar" class="arrow-steps clearfix">
                        <li class="step current"  id="account"><strong>BASKET</strong></li>
                        <li id="personal" class="step" :class="{'current': step2 }"><strong>DELIVERY</strong></li>
                        <li id="payment" class="step" :class="{'current': step3 }"><strong>PAYMENT</strong></li>
                        <li id="confirm" class="step" :class="{'current': step4}"><strong>FINISH</strong></li>
                    </ul>
                    <fieldset v-if="step==1">
                        <div class="step-content information" id="address-cart">
                            @include('shop::checkout.cart.index')
                            <cart-component></cart-component>
                        </div>
                        <div class="divBasket">
                            <input type="button" @click.prevent='nextStep' name="next" class="next action-button" value="Next Step" />
                        </div>
                    </fieldset>
                    <fieldset v-if="step==2">
                    <div class="step-content information" id="address-section">
                        @include('shop::checkout.cart.index')
                        @include('shop::checkout.onepage.customer-info')
                    </div>
                    <div class="step-content shipping" id="shipping-section" v-if="showShippingSection">

                        <shipping-section @onShippingMethodSelected="shippingMethodSelected($event)">
                        </shipping-section>
                    </div>
                    <div class="divBasket">
                    <input type="button" @click.prevent='prevStep' name="previous" class="previous action-button-previous" value="Previous" /> 
                    <input type="button" @click.prevent='nextStep' name="next" class="next action-button" value="Next Step" />
                    </div>
                </fieldset>
                    <fieldset v-if="step==3">
                        <div
                        class="step-content payment"
                        v-if="showPaymentSection"
                        id="payment-section">

                        <payment-section @onPaymentMethodSelected="paymentMethodSelected($event)">
                        </payment-section>

                        <coupon-component
                            @onApplyCoupon="getOrderSummary"
                            @onRemoveCoupon="getOrderSummary">
                        </coupon-component>
                    </div>
                    <div class="divBasket">
                    <input type="button" @click.prevent='prevStep' name="previous" class="previous action-button-previous" value="Previous" /> 
                    <input type="button" @click.prevent='nextStep' name="next" class="next action-button" value="Next Step" />
                    <div>
                </fieldset>
                    <fieldset  v-if="step==4">
                        <div
                        class="step-content review"
                        v-if="showSummarySection"
                        id="summary-section">

                        <review-section :key="reviewComponentKey">
                            <div slot="summary-section">
                                <summary-section
                                    discount="1"
                                    :key="summeryComponentKey"
                                    @onApplyCoupon="getOrderSummary"
                                    @onRemoveCoupon="getOrderSummary"
                                ></summary-section>
                            </div>

                            <div slot="place-order-btn">
                                <div class="mb20">
                                    <button
                                        type="button"
                                        class="theme-btn"
                                        @click="placeOrder()"
                                        :disabled="!isPlaceOrderEnabled"
                                        id="checkout-place-order-button">
                                        {{ __('shop::app.checkout.onepage.place-order') }}
                                    </button>
                                </div>
                            </div>
                        </review-section>
                    </div>
                    <div class="divBasket">
                    <input type="button" @click.prevent='prevStep' name="previous" class="previous action-button-previous" value="Previous" /> 
                    <input type="button" v-if="step!=4" @click.prevent='nextStep' name="next" class="next action-button" value="Next Step" />
                    </div>
                </fieldset>

                    
                </div>

                <div class="col-lg-4 col-md-12 order-summary-container top pt0">
                    <summary-section :key="summeryComponentKey"></summary-section>
                </div>
            </div>
        </div>
    </script>
   
    <script type="text/javascript">
        (() => {
            var reviewHtml = '';
            var paymentHtml = '';
            var summaryHtml = '';
            var shippingHtml = '';
            var paymentMethods = '';
            var customerAddress = '';
            var shippingMethods = '';

            var reviewTemplateRenderFns = [];
            var paymentTemplateRenderFns = [];
            var summaryTemplateRenderFns = [];
            var shippingTemplateRenderFns = [];

            @auth('customer')
                @if(auth('customer')->user()->addresses)
                    customerAddress = @json(auth('customer')->user()->addresses);
                    customerAddress.email = "{{ auth('customer')->user()->email }}";
                    customerAddress.first_name = "{{ auth('customer')->user()->first_name }}";
                    customerAddress.last_name = "{{ auth('customer')->user()->last_name }}";
                @endif
            @endauth

            Vue.component('checkout', {
                template: '#checkout-template',
                inject: ['$validator'],

                data: function () {
                    return {
                        step:1,
                        totalstep:4,
                        step2 : false,
                        step3 : false,
                        step4 : false,
                        allAddress: {},
                        current_step: 1,
                        completed_step: 0,
                        isCheckPayment: true,
                        is_customer_exist: 0,
                        disable_button: false,
                        reviewComponentKey: 0,
                        summeryComponentKey: 0,
                        showPaymentSection: false,
                        showSummarySection: false,
                        isPlaceOrderEnabled: false,
                        new_billing_address: false,
                        showShippingSection: false,
                        new_shipping_address: false,
                        selected_payment_method: '',
                        selected_shipping_method: '',
                        country: @json(core()->countries()),
                        countryStates: @json(core()->groupedStatesByCountries()),
                        UKCountry: true,
                        addressSelected: false,
                        step_numbers: {
                            'information': 1,
                            'shipping': 2,
                            'payment': 3,
                            'review': 4
                        },

                        address: {
                            billing: {
                                address1: [''],

                                use_for_shipping: true,
                                country : 'GB'
                            },

                            shipping: {
                                address1: ['']
                            },
                        },
                    }
                },

                created: function () {
                    this.getOrderSummary();

                    if (! customerAddress) {
                        this.new_shipping_address = true;
                        this.new_billing_address = true;
                    } else {
                        this.address.billing.first_name = this.address.shipping.first_name = customerAddress.first_name;
                        this.address.billing.last_name = this.address.shipping.last_name = customerAddress.last_name;
                        this.address.billing.email = this.address.shipping.email = customerAddress.email;

                        if (customerAddress.length < 1) {
                            this.new_shipping_address = true;
                            this.new_billing_address = true;
                        } else {
                            this.allAddress = customerAddress;

                            for (var country in this.country) {
                                for (var code in this.allAddress) {
                                    if (this.allAddress[code].country) {
                                        if (this.allAddress[code].country == this.country[country].code) {
                                            this.allAddress[code]['country'] = this.country[country].name;
                                        }
                                    }
                                }
                            }
                        }
                    }
                },

                methods: {
                    nextStep(){
                        this.step++;
                        if(this.step == 2)
                        {
                            this.step2 = true;
                        }
                        if(this.step == 3)
                        {
                            this.step3 = true
                        }
                        if(this.step == 4)
                        {
                            this.step4 = true
                        }
                    },
                    prevStep(){
                        
                        if(this.step == 2)
                        {
                            this.step2 = false
                        }
                        if(this.step == 3)
                        {
                            this.step3 = false
                        }
                        if(this.step == 4)
                        {
                            this.step4 = false
                        }
                        this.step--;
                    },
                    navigateToStep: function (step) {
                        if (step <= this.completed_step) {
                            this.current_step = step
                            this.completed_step = step - 1;
                        }
                    },

                    haveStates: function (addressType) {
                        if (this.countryStates[this.address[addressType].country] && this.countryStates[this.address[addressType].country].length)
                            return true;

                        return false;
                    },

                    validateForm: function (scope) {
                        var isManualValidationFail = false;

                        if (scope == 'address-form') {
                            isManualValidationFail = this.validateAddressForm();
                        }

                        if (! isManualValidationFail) {
                            this.$validator.validateAll(scope)
                            .then(result => {
                                if (result) {
                                    this.$root.showLoader();

                                    switch (scope) {
                                        case 'address-form':
                                            this.saveAddress();
                                            break;

                                        case 'shipping-form':
                                            if (this.showShippingSection) {
                                                this.$root.showLoader();
                                                this.saveShipping();
                                                break;
                                            }

                                        case 'payment-form':
                                            this.$root.showLoader();
                                            this.savePayment();

                                            this.isPlaceOrderEnabled = ! this.validateAddressForm();
                                            break;

                                        default:
                                            break;
                                    }

                                } else {
                                    this.isPlaceOrderEnabled = false;
                                }
                            });
                        } else {
                            this.isPlaceOrderEnabled = false;
                        }
                    },

                    validateAddressForm: function () {
                        var isManualValidationFail = false;

                        let form = $(document).find('form[data-vv-scope=address-form]');

                        // validate that if all the field contains some value
                        if (form) {
                            form.find(':input').each((index, element) => {
                                let value = $(element).val();
                                let elementId = element.id;

                                if (value == ""
                                    && element.id != 'sign-btn'
                                    && element.id != 'billing[company_name]'
                                    && element.id != 'shipping[company_name]'
                                ) {
                                    // check for multiple line address
                                    if (elementId.match('billing_address_')
                                        || elementId.match('shipping_address_')
                                    ) {
                                        // only first line address is required
                                        if (elementId == 'billing_address_0'
                                            || elementId == 'shipping_address_0'
                                        ) {
                                            isManualValidationFail = true;
                                        }
                                    } else {
                                        isManualValidationFail = true;
                                    }
                                }
                            });
                        }

                        // validate that if customer wants to use different shipping address
                        if (! this.address.billing.use_for_shipping) {
                            if (! this.address.shipping.address_id && ! this.new_shipping_address) {
                                isManualValidationFail = true;
                            }
                        }

                        return isManualValidationFail;
                    },

                    isCustomerExist: function() {
                        this.$validator.attach('address-form.billing[email]', 'required|email');

                        this.$validator.validate('address-form.billing[email]', this.address.billing.email)
                        .then(isValid => {
                            if (! isValid)
                                return;

                            this.$http.post("{{ route('customer.checkout.exist') }}", {email: this.address.billing.email})
                            .then(response => {
                                this.is_customer_exist = response.data ? 1 : 0;
                                console.log(this.is_customer_exist);

                                if (response.data)
                                    this.$root.hideLoader();
                            })
                            .catch(function (error) {})
                        })
                        .catch(error => {})
                    },

                    makeZipCodeSearchBox: function() {
                        // $('#zipcode_searcher').setupPostcodeLookup({
                        PostcodeLookup.setup({
                            // *Required* Insert your API Key
                            apiKey: "ak_kkcm32u0dYIVpYIsuGfdvyV6qQmOE",

                            // *Required* Specify the target element with ID `postcode_lookup` to house the search tools
                            context: "#zipcode_searcher",

                            // Configures how address results are sent to inputs with IDs `line_1`, `line_2`, `line_3`, `post_town` and `postcode`.
                            outputFields: {

                            },
                            onAddressSelected: address => {
                                const result = [
                                    address.line_1,
                                    address.line_2,
                                    address.line_3,
                                ].filter(elem => elem !== "")
                                    .join(", ");

                                const post_town_result = address.post_town.toLowerCase().replace(/\b[a-z]/g, function(txtVal) {return txtVal.toUpperCase();});
                                const county_result = address.county.toLowerCase().replace(/\b[a-z]/g, function(txtVal) {return txtVal.toUpperCase();});

                                this.address.billing.address1[0] = result;
                                this.address.billing.state = county_result;
                                this.address.billing.city = post_town_result;
                                this.addressSelected = true;
                            }
                        });
                    },

                    checkZipCodeSearch: function() {
                        if ( this.address.billing.country == "GB"){
                            this.UKCountry = true;
                            this.addressSelected = false;
                            this.makeZipCodeSearchBox();
                        } else {
                            this.UKCountry = false;
                            this.addressSelected = false;
                        }
                    },

                    searchZipCode: function() {

                    },

                    validateFormAndCheckUK: function(scope) {
                        // event.target.selected = "GB"
                        this.validateForm(scope);
                        this.checkZipCodeSearch();
                    },

                    loginCustomer: function () {
                        this.$http.post("{{ route('customer.checkout.login') }}", {
                                email: this.address.billing.email,
                                password: this.address.billing.password
                            })
                            .then(response => {
                                if (response.data.success) {
                                    window.location.href = "{{ route('shop.checkout.onepage.index') }}";
                                } else {
                                    window.showAlert(`alert-danger`, this.__('shop.general.alert.danger'), response.data.error);
                                }
                            })
                            .catch(function (error) {})
                    },

                    getOrderSummary: function () {
                        this.$http.get("{{ route('shop.checkout.summary') }}")
                            .then(response => {
                                summaryHtml = Vue.compile(response.data.html)

                                this.summeryComponentKey++;
                                this.reviewComponentKey++;
                            })
                            .catch(function (error) {})
                    },

                    saveAddress: function () {
                        this.disable_button = true;

                        if (this.allAddress.length > 0) {
                            let address = this.allAddress.forEach(address => {
                                if (address.id == this.address.billing.address_id) {
                                    this.address.billing.address1 = [address.address1];

                                    if (address.email) {
                                        this.address.billing.email = address.email;
                                    }

                                    if (address.first_name) {
                                        this.address.billing.first_name = address.first_name;
                                    }

                                    if (address.last_name) {
                                        this.address.billing.last_name = address.last_name;
                                    }
                                }

                                if (address.id == this.address.shipping.address_id) {
                                    this.address.shipping.address1 = [address.address1];

                                    if (address.email) {
                                        this.address.shipping.email = address.email;
                                    }

                                    if (address.first_name) {
                                        this.address.shipping.first_name = address.first_name;
                                    }

                                    if (address.last_name) {
                                        this.address.shipping.last_name = address.last_name;
                                    }
                                }
                            });
                        }

                        this.$http.post("{{ route('shop.checkout.save-address') }}", this.address)
                            .then(response => {
                                this.disable_button = false;
                                this.isPlaceOrderEnabled = true;

                                if (this.step_numbers[response.data.jump_to_section] == 2) {
                                    this.showShippingSection = true;
                                    shippingHtml = Vue.compile(response.data.html);
                                
                                } else {
                                    paymentHtml = Vue.compile(response.data.html)
                                }

                                this.completed_step = this.step_numbers[response.data.jump_to_section] + 1;
                                this.current_step = this.step_numbers[response.data.jump_to_section];

                                if (response.data.jump_to_section == "payment") {
                                    this.showPaymentSection = true;
                                    paymentMethods  = response.data.paymentMethods;
                                }

                                shippingMethods = response.data.shippingMethods;
                                this.getOrderSummary();
                 
                                this.$root.hideLoader();
                                
                            })
                            .catch(error => {
                                this.disable_button = false;
                                this.$root.hideLoader();

                                this.handleErrorResponse(error.response, 'address-form')
                            })
                    },

                    saveShipping: function () {
                        this.disable_button = true;
                        this.$http.post("{{ route('shop.checkout.save-shipping') }}", {'shipping_method': this.selected_shipping_method})
                            .then(response => {
                                this.$root.hideLoader();
                                this.disable_button = false;
                                this.showPaymentSection = true;

                                paymentHtml = Vue.compile(response.data.html)

                                // this.completed_step = this.step_numbers[response.data.jump_to_section] + 1;

                                // this.current_step = this.step_numbers[response.data.jump_to_section];

                                paymentMethods = response.data.paymentMethods;

                                if (this.selected_payment_method) {
                                    this.savePayment();
                                }

                                // this.getOrderSummary();
                                
                            })
                            .catch(error => {
                                this.disable_button = false;
                                this.$root.hideLoader();
                                this.handleErrorResponse(error.response, 'shipping-form')
                            })
                    },

                    savePayment: function () {
                        this.disable_button = true;

                        if (this.isCheckPayment) {
                            this.isCheckPayment = false;

                            this.$http.post("{{ route('shop.checkout.save-payment') }}", {'payment': this.selected_payment_method})
                            .then(response => {
                                this.isCheckPayment = true;
                                this.disable_button = false;

                                this.showSummarySection = true;
                                this.$root.hideLoader();

                                reviewHtml = Vue.compile(response.data.html)
                                this.completed_step = this.step_numbers[response.data.jump_to_section] + 1;
                                this.current_step = this.step_numbers[response.data.jump_to_section];

                                document.body.style.cursor = 'auto';

                                this.getOrderSummary();
                            })
                            .catch(error => {
                                this.disable_button = false;
                                this.$root.hideLoader();
                                this.handleErrorResponse(error.response, 'payment-form')
                            });
                        }
                    },

                    placeOrder: function () {
                        if (this.isPlaceOrderEnabled) {
                            this.disable_button = false;
                            this.isPlaceOrderEnabled = false;

                            this.$root.showLoader();

                            this.$http.post("{{ route('shop.checkout.save-order') }}", {'_token': "{{ csrf_token() }}"})
                            .then(response => {
                                if (response.data.success) {
                                    if (response.data.redirect_url) {
                                        this.$root.hideLoader();
                                        window.location.href = response.data.redirect_url;
                                    } else {
                                        this.$root.hideLoader();
                                        window.location.href = "{{ route('shop.checkout.success') }}";
                                    }
                                }
                            })
                            .catch(error => {
                                this.disable_button = true;
                                this.$root.hideLoader();

                                window.showAlert(`alert-danger`, this.__('shop.general.alert.danger'), "{{ __('shop::app.common.error') }}");
                            })
                        } else {
                            this.disable_button = true;
                        }
                    },

                    handleErrorResponse: function (response, scope) {
                        if (response.status == 422) {
                            serverErrors = response.data.errors;
                            this.$root.addServerErrors(scope)
                        } else if (response.status == 403) {
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            }
                        }
                    },

                    shippingMethodSelected: function (shippingMethod) {
                        this.selected_shipping_method = shippingMethod;
                    },

                    paymentMethodSelected: function (paymentMethod) {
                        this.selected_payment_method = paymentMethod;
                    },

                    newBillingAddress: function () {
                        this.new_billing_address = true;
                        this.isPlaceOrderEnabled = false;
                        this.address.billing.address_id = null;
                    },

                    newShippingAddress: function () {
                        this.new_shipping_address = true;
                        this.isPlaceOrderEnabled = false;
                        this.address.shipping.address_id = null;
                    },

                    backToSavedBillingAddress: function () {
                        this.new_billing_address = false;
                        this.validateFormAfterAction()
                    },

                    backToSavedShippingAddress: function () {
                        this.new_shipping_address = false;
                        this.validateFormAfterAction()
                    },

                    validateFormAfterAction: function () {
                        setTimeout(() => {
                            this.validateForm('address-form');
                        }, 0);
                    }
                }
            });

            Vue.component('shipping-section', {
                inject: ['$validator'],

                data: function () {
                    return {
                        templateRender: null,

                        selected_shipping_method: '',

                        first_iteration : true,
                    }
                },

                staticRenderFns: shippingTemplateRenderFns,

                mounted: function () {
                    this.templateRender = shippingHtml.render;

                    for (var i in shippingHtml.staticRenderFns) {
                        shippingTemplateRenderFns.push(shippingHtml.staticRenderFns[i]);
                    }

                    eventBus.$emit('after-checkout-shipping-section-added');
                    
                },

                render: function (h) {
                    return h('div', [
                        (this.templateRender ?
                            this.templateRender() :
                            '')
                        ]);
                },

                methods: {
                    methodSelected: function () {
                        this.$parent.validateForm('shipping-form');

                        this.$emit('onShippingMethodSelected', this.selected_shipping_method)

                        eventBus.$emit('after-shipping-method-selected');
                    }
                }
            })

            Vue.component('payment-section', {
                inject: ['$validator'],

                data: function () {
                    return {
                        templateRender: null,

                        payment: {
                            method: ""
                        },

                        first_iteration : true,
                    }
                },

                staticRenderFns: paymentTemplateRenderFns,

                mounted: function () {
                    this.templateRender = paymentHtml.render;

                    for (var i in paymentHtml.staticRenderFns) {
                        paymentTemplateRenderFns.push(paymentHtml.staticRenderFns[i]);
                    }

                    eventBus.$emit('after-checkout-payment-section-added');
                },

                render: function (h) {
                    return h('div', [
                        (this.templateRender ?
                            this.templateRender() :
                            '')
                        ]);
                },

                methods: {
                    methodSelected: function () {
                        this.$parent.validateForm('payment-form');

                        this.$emit('onPaymentMethodSelected', this.payment)

                        eventBus.$emit('after-payment-method-selected');
                    }
                }
            })

            Vue.component('review-section', {
                data: function () {
                    return {
                        error_message: '',
                        templateRender: null,
                    }
                },

                staticRenderFns: reviewTemplateRenderFns,

                render: function (h) {
                    return h(
                        'div', [
                            this.templateRender ? this.templateRender() : ''
                        ]
                    );
                },

                mounted: function () {
                    this.templateRender = reviewHtml.render;

                    for (var i in reviewHtml.staticRenderFns) {
                        reviewTemplateRenderFns[i] = reviewHtml.staticRenderFns[i];
                    }

                    this.$forceUpdate();
                }
            });

            Vue.component('summary-section', {
                inject: ['$validator'],

                staticRenderFns: summaryTemplateRenderFns,

                props: {
                    discount: {
                        default: 0,
                        type: [String, Number],
                    }
                },

                data: function () {
                    return {
                        changeCount: 0,
                        coupon_code: null,
                        error_message: null,
                        templateRender: null,
                        couponChanged: false,
                    }
                },

                mounted: function () {
                    this.templateRender = summaryHtml.render;

                    for (var i in summaryHtml.staticRenderFns) {
                        summaryTemplateRenderFns[i] = summaryHtml.staticRenderFns[i];
                    }

                    this.$forceUpdate();
                },

                render: function (h) {
                    return h('div', [
                        (this.templateRender ?
                            this.templateRender() :
                            '')
                        ]);
                },

                methods: {
                    onSubmit: function () {
                        var this_this = this;
                        const emptyCouponErrorText = "Please enter a coupon code";
                    },

                    changeCoupon: function () {
                        if (this.couponChanged == true && this.changeCount == 0) {
                            this.changeCount++;

                            this.error_message = null;

                            this.couponChanged = false;
                        } else {
                            this.changeCount = 0;
                        }
                    },
                }
            });

        })()
    </script>

@endpush