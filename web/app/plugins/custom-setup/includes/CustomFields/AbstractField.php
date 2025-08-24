<?php

namespace Custom\Setup\CustomFields;

use Custom\Setup\ServiceInterface;

abstract class AbstractField implements ServiceInterface
{
    public function register() {
        add_action('acf/init', [$this, 'register_acf_field_group'], 25);
    }
}
