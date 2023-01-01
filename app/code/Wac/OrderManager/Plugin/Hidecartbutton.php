<?php

namespace Wac\OrderManager\Plugin;


use Magento\Catalog\Model\Product;


class Hidecartbutton
{
    public function afterIsSaleable(Product $product)
    {
        return [];
    }
}