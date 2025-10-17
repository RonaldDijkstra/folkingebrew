<?php

if (is_product_category() || is_product_tag()) {
    echo \Roots\view('webshop.archive-product-category')->render();
} else {
    echo \Roots\view('webshop.archive-product')->render();
}
