<?php

namespace Custom\Setup\CustomPostTypes;

use Custom\Setup\CustomPostTypes\AbstractCustomPostType;

class Beers extends AbstractCustomPostType
{
    protected string $id = 'beers';
    protected string $label = 'Beers';
    protected string $name = 'beers';
    protected string $instructions = 'Enable the custom post type <strong>Beers</strong>.';
    protected string $menu_icon = 'dashicons-beer';
    protected bool $public = true;
    protected bool $show_ui = true;
    protected bool $show_in_nav_menus = false;
    protected bool $publicly_queryable = true;
    protected bool $hierarchical = true;
    protected string $query_var = 'beers';
    protected array|bool $rewrite = false;
    protected string|bool $has_archive = true;
    protected bool $show_in_rest = true;
    protected string $capability_type = 'post';
    protected array $supports = ['title', 'page-attributes'];
}
