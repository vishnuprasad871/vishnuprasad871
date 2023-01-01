<?php

namespace SuperWebShare\Magento\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class ExcludePages implements ArrayInterface
{


    public function toOptionArray()
    {
       
        $optionArray[] = [
            'value' => "homepage",
            'label' => "Home Page",
        ];

        $optionArray[] = [
            'value' => "product_page",
            'label' =>"Product Page"
        ];
        $optionArray[] = [
            'value' => "category_page",
            'label' => "Category Page",
        ];
       
        return $optionArray;
    }
}