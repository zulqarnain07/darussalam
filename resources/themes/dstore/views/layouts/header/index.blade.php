<header class="sticky-header" v-if="!isMobile()">
    <div class="row col-12 remove-padding-margin velocity-divide-page">
        <div class="logoimg">
            <logo-component></logo-component>
        </div>
        {{-- <searchbar-component></searchbar-component>. --}}
    </div>
</header>

@push('scripts')
    <script type="text/javascript">
        (() => {
            document.addEventListener('scroll', e => {
                scrollPosition = Math.round(window.scrollY);

                if (scrollPosition > 50){
                    document.querySelector('#top').classList.add('sticky');
                    document.querySelector('#megamenu').classList.add('sticky');
                    document.querySelector('.logoimg').classList.add('sticky');
                } else {
                    document.querySelector('#top').classList.remove('sticky');
                    document.querySelector('#megamenu').classList.remove('sticky');
                    document.querySelector('.logoimg').classList.remove('sticky');
                }
            });
        })()
    </script>
@endpush
