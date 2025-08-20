<?php

/*
 * @package Custom_Setup
 */

namespace Custom\Setup\Base;

class Activate
{
    public static function activate()
    {
        flush_rewrite_rules();
    }
}
