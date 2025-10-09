<?php

/*
 * @package Custom_Shop
 */

namespace Custom\Shop\Base;

class Activate
{
    public static function activate()
    {
        flush_rewrite_rules();
    }
}
