<?php

namespace Webkul\Product\Helpers;

use Illuminate\Support\Facades\DB;

class Faq extends AbstractProduct
{
    /**
     * Returns the product's faq
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return float
     */
    public function getFaqs($product)
    {
        static $faqs = [];

        if (array_key_exists($product->sku, $faqs)) {
            return $faqs[$product->sku];
        }

        return $faqs[$product->sku] = $product->faq()->where('status', 1)->where('store_code','!=','ar')->orderBy('created_at','desc');
    }


    /**
     * Returns the total faq of the product
     *
    * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return int
     */
    public function getTotalFaq($product)
    {
        static $totalFaq = [];

        if (array_key_exists($product->id, $totalFaq)) {
            return $totalFaq[$product->sku];
        }

        return $totalFaq[$product->sku] = $product->faq()->where('status', 1)->where('store_code','!=','ar')->count();
    }
}