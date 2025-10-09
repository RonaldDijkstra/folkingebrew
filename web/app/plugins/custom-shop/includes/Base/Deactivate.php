<?php

/*
 * @package Custom_Shop
 */

namespace Custom\Shop\Base;

class Deactivate
{
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
