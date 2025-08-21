<?php

/*
 * @package Custom_Setup
 */

namespace Custom\Setup\Base;

class Deactivate
{
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
