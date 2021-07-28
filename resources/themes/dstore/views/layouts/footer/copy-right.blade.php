<div class="footer-copy-right">
    <div class="footer-margin">
    <span>
        @if (core()->getConfigData('general.content.footer.footer_content'))
            {!! core()->getConfigData('general.content.footer.footer_content') !!}
        @else
            {!! trans('admin::app.footer.copy-right') !!}
        @endif
    </span>
    </div>
</div>
