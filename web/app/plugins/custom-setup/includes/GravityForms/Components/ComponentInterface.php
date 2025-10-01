<?php
namespace Custom\Setup\GravityForms\Components;

interface ComponentInterface
{
    /**
     * Render the component
     *
     * @param array $data The data needed to render the component
     * @return string The rendered HTML
     */
    public function render(array $data = []): string;

    /**
     * Check if the component should be rendered
     *
     * @param array $data The data to check
     * @return bool Whether the component should be rendered
     */
    public function shouldRender(array $data = []): bool;
}
