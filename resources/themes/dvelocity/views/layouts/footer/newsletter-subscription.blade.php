@if (
    $velocityMetaData
    && $velocityMetaData->subscription_bar_content
    || core()->getConfigData('customer.settings.newsletter.subscription')
)


    <div class="newsletter-subscription" >
        <div class="newsletter-wrapper row col-12">
            @if ($velocityMetaData && $velocityMetaData->subscription_bar_content)
                {!! $velocityMetaData->subscription_bar_content !!}
            @endif

            
                <div class="subscribe-newsletter col-lg-6">
                    <div class="form-container">
                        <form action="{{ route('shop.subscribe') }}">
                            <div class="subscriber-form-div">
                                <div class="control-group" id="subcri">
                                    <input
                                        type="email"
                                        name="subscriber_email"
                                        class="mysub"
                                        placeholder="{{ __('velocity::app.customer.login-form.your-email-address') }}"
                                        required />

                                    <button  class="subbtn">
                                        
                                        {{ __('shop::app.subscription.subscribe') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        
        </div>
    </div>
@endif
