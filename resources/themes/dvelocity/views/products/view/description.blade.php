{!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

<accordian class="accdescrip" :title="'{{ __('shop::app.products.description') }}'" :active="true">
    <div slot="header">
        <h3 class="no-margin display-inbl">
            {{ __('velocity::app.products.details') }}
        </h3>

        <i class="rango-arrow"></i>
    </div>

    <div slot="body">
        <div class="full-description">
            {!! $product->description !!}

            <br>
            <table class="table table-borderless">
                <tbody>
                    @if(!empty($product->sku))
                    <tr>
                        <th class="col label" scope="row">SKU</th>
                        <td class="col data" data-th="Weight (kg)">{{$product->sku}}</td>
                    </tr>
                    @endif
                    @if(!empty($product->weight))
                    <tr>
                        <th class="col label" scope="row">Weight (kg)</th>
                        <td class="col data" data-th="Weight (kg)">{{$product->weight}}</td>
                    </tr>
                    @endif
                    @if(!empty($product->publisher_label))
                    <tr>
                        <th class="col label" scope="row">Publisher</th>
                        <td class="col data" data-th="Publisher">{{$product->publisher_label}}</td>
                    </tr>
                    @endif
                    @if(!empty($product->book_langauge_label))
                    <tr>
                        <th class="col label" scope="row">Book By Language</th>
                        <td class="col data" data-th="Book By Language">{{$product->book_langauge_label}}</td>
                    </tr>
                    @endif
                    @if(!empty($product->AgGroups_label))
                    <tr>
                        <th class="col label" scope="row">Age Groups</th>
                        <td class="col data" data-th="Age Groups">{{$product->AgGroups_label}}</td>
                    </tr>
                    @endif
                    @if(!empty($product->product_size_label))
                    <tr>
                        <th class="col label" scope="row">Size cm</th>
                        <td class="col data" data-th="Size cm">{{$product->product_size_label}}</td>
                    </tr>
                    @endif
                    @if(!empty($product->Author))
                    <tr>
                        <th class="col label" scope="row">Author(s)</th>
                        <td class="col data" data-th="Author(s)">{{$product->Author}}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</accordian>

{!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}