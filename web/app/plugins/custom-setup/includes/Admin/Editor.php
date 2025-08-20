<?php

namespace Custom\Setup\Admin;

use Custom\Setup\ServiceInterface;

class Editor implements ServiceInterface
{
    public function register()
    {
        add_action('admin_head', [$this, 'adminEditorStyleFixes']);
    }

    public function adminEditorStyleFixes()
    {
        echo '<style>
            .editor-visual-editor {
                overflow: visible;
            }
    
            .interface-navigable-region.components-resizable-box__container.edit-post-meta-boxes-main.is-resizable {
                height: auto !important;
                max-height: none !important;
            }
        </style>';
    }
}
