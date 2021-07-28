<div class="footer-copy-right">
    <div class="footer-margin">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    @if (core()->getConfigData('general.content.footer.footer_content'))
                    {!! core()->getConfigData('general.content.footer.footer_content') !!}
                @else
                Darussalam Store. © 2020. All Rights Reserved
                @endif  
                </div>
                <div class="col-md-4">
                    @if (core()->getConfigData('general.content.footer.footer_content'))
                    {!! core()->getConfigData('general.content.footer.footer_content') !!}
                @else
                Working Days/Hours: Sat - Thu / 9:00AM - 5:00PM (Friday is Off)
                @endif  
                </div>
                <div class="col-md-4">
                    @if (core()->getConfigData('general.content.footer.footer_content'))
                    {!! core()->getConfigData('general.content.footer.footer_content') !!}
                @else
                <img src="https://d3if69nj1e255i.cloudfront.net/darus/footer/payment-icons-store-uk.png" alt="">
                @endif  
                </div>
            </div>
        </div>
    </div>
</div>
