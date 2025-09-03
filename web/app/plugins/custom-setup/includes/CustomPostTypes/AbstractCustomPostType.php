<?php 

namespace Custom\Setup\CustomPostTypes;

use Custom\Setup\ServiceInterface;

abstract class AbstractCustomPostType implements ServiceInterface
{
    protected bool|string $has_archive;
    protected string $id;
    protected string $instructions;
    protected string $label;
    protected string $plural_label;
    protected string $menu_icon;
    protected string $message = '';
    protected string $name;
    protected array|bool $rewrite;
    protected string $settings;
    protected string $slug;
    protected string $taxonomies;
    protected string $taxonomies_label;
    protected array $supports;
    protected bool $show_ui = true;
    protected bool $hierarchical;

    public function __construct()
    {
        $this->has_archive = $this->has_archive ?? false;
        $this->id = $this->id ?? uniqid('', true);
        $this->instructions = $this->instructions ?? '';
        $this->label = $this->label ?? '';
        $this->plural_label = $this->plural_label ?? $this->label;
        $this->menu_icon = $this->menu_icon ?? '';
        $this->message = $this->message ?? '';  // Ensure $message is initialized
        $this->name = ucfirst(strtolower($this->name)) ?? '';
        $this->rewrite = $this->rewrite ?? false;
        $this->settings = $this->enable_acf_settings ?? '';
        $this->slug = strtolower($this->id . '_slug') ?? '';
        $this->supports = $this->supports ?? ['title', 'editor', 'thumbnail', 'page-attributes', 'excerpt'];
        $this->taxonomies = $this->taxonomies ?? '';
        $this->taxonomies_label = $this->taxonomies_label ?? '';
        if (!isset($this->show_ui)) {
            $this->show_ui = true;
        }
        $this->hierarchical = $this->hierarchical ?? false;
    }

    public function register(): void
    {
        add_action('init', [$this, 'registerPostType']);
    }

    public function registerPostType(): void
    {
        $labels = [
            'add_new'            => sprintf(_x('Add new %s', 'folkingebrew'), $this->label),
            'add_new_item'       => sprintf(__('Add new %s', 'fo    lkingebrew'), $this->label),
            'all_items'          => sprintf(__('All %s', 'folkingebrew'), $this->plural_label),
            'edit_item'          => sprintf(__('Edit %s', 'folkingebrew'), $this->label),
            'menu_name'          => sprintf(_x('%s', 'admin menu', 'folkingebrew'), $this->plural_label),
            'name'               => sprintf(_x('%s', 'post type general name', 'folkingebrew'), $this->plural_label),
            'new_item'           => sprintf(__('New %s', 'folkingebrew'), $this->label),
            'not_found'          => sprintf(__('No %s found.', 'folkingebrew'), $this->plural_label),
            'not_found_in_trash' => sprintf(__('No %s found in trash.', 'folkingebrew'), $this->plural_label),
            'parent_item_colon'  => sprintf(__('%s Parent', 'folkingebrew'), $this->label),
            'search_items'       => sprintf(__('Search %s', 'folkingebrew'), $this->plural_label),
            'singular_name'      => sprintf(_x('%s', 'post type singular title', 'folkingebrew'), $this->label),
            'view_item'          => sprintf(__('View %s', 'folkingebrew'), $this->label),
        ];

        $slug = (!empty(get_option($this->slug))) ? get_option($this->slug) : $this->id;
        $rewrite_rules = is_array($this->rewrite) ? $this->rewrite : ['slug' => $slug, 'with_front' => false];

        $args = [
            'capability_type'       => 'post',
            'has_archive'           => $this->has_archive,
            'label'                 => $this->name,
            'labels'                => $labels,
            'rewrite'               => $rewrite_rules,
            'menu_icon'             => $this->menu_icon,
            'public'                => true,
            'show_in_rest'          => true,
            'show_in_nav_menus'     => true,
            'show_ui'               => $this->show_ui,
            'supports'              => $this->supports,
            'taxonomies'            => [$this->taxonomies],
            '_builtin'              => false,
            'hierarchical'          => $this->hierarchical,
        ];

        if (!post_type_exists($this->id)) {
            register_post_type($this->id, $args);
        }

        flush_rewrite_rules();
    }
}
