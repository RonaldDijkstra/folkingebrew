<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Custom\Setup\Helpers\FileLocator;

class Icon extends Component
{
    /**
     * The icon SVG.
     *
     * @var string
     */
    public $icon;

    /**
     * The icon SVG.
     *
     * @var string
     */
    public $classes;

    /**
     * Create a new component instance.
     *
     * @param  string  $name
     * @param  string  $classes
     * @return void
     */
    public function __construct($name = '', $classes = '')
    {
        $this->icon = $this->getIconSVG($name);
        $this->classes = $classes;
    }

    /**
     * Retrieves the SVG markup for a given icon name.
     *
     * @param string $name The name of the icon to retrieve
     */
    public function getIconSVG($name): string
    {
        $iconPath = FileLocator::getViteAssetUrl('resources/images/icons/' . $name . '.svg');
        $iconContents = '';

        // If the path is a URL, try to get the file contents via file_get_contents
        if (filter_var($iconPath, FILTER_VALIDATE_URL)) {
            $iconContents = @file_get_contents($iconPath);
        } else if (file_exists($iconPath)) {
            $iconContents = @file_get_contents($iconPath);
        }

        // Fallback to empty string if not found
        return $iconContents ?: '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.icon');
    }
}
