<?php

namespace Custom\Setup\GravityForms;

use Custom\Setup\ServiceInterface;

class ShowPagesWithForm implements ServiceInterface
{
    private static $stylesAdded = false;

    public function register()
    {
        add_filter('gform_form_list_columns', [$this, 'addPagesColumn']);
        add_action('gform_form_list_column_used_on', [$this, 'renderPagesColumn']);

        // Add sidebar panel to form editor
        add_filter('gform_editor_sidebar_panels', [$this, 'addEditorSidebarPanel'], 10, 2);
        add_action('gform_editor_sidebar_panel_content_pages_used_on', [$this, 'renderEditorSidebarPanel'], 10, 2);

        // Clear cache when pages are updated
        add_action('save_post', [$this, 'clearFormsPageCache'], 10, 1);
        add_action('wp_trash_post', [$this, 'clearFormsPageCache'], 10, 1);
        add_action('untrash_post', [$this, 'clearFormsPageCache'], 10, 1);
        add_action('delete_post', [$this, 'clearFormsPageCache'], 10, 1);

        // Clear cache when ACF options are updated (for package option page forms)
        add_action('acf/save_post', [$this, 'clearFormsPageCache'], 10, 1);

        // Clear cache when forms are activated/deactivated (individual or bulk)
        add_action('gform_post_form_activated', [$this, 'clearFormsPageCache'], 10, 1);
        add_action('gform_post_form_deactivated', [$this, 'clearFormsPageCache'], 10, 1);

        // Clear cache when forms are saved (covers AJAX status changes in editor)
        add_action('gform_after_save_form', [$this, 'clearFormsPageCache'], 10, 2);

        // AJAX handler for refreshing column content
        add_action('wp_ajax_custom_gf_refresh_pages_column', [$this, 'handleAjaxRefreshPagesColumn']);
    }

    /**
     * Add a Pages column to the forms list table.
     *
     * @param array $columns The existing columns
     * @return array Modified columns array with the "Pages" column added
     */
    public function addPagesColumn(array $columns): array
    {
        $columns['used_on'] = 'Pages';

        return $columns;
    }

    /**
     * Render the "Pages" column for a specific form.
     *
     * @param object $form The form object
     * @return string The HTML for the column
     */
    public function renderPagesColumn(object $form)
    {
        $formId = (int) $form->id;
        $formsPageCache = $this->buildFormsPageCache();
        $isFormActive = !empty($form->is_active);

        if (!isset($formsPageCache[$formId]) || empty($formsPageCache[$formId])) {
            echo '<em>Not used</em>';
            return;
        }

        echo '<div class="gf-pages-column-content">';

        // Show warning if form is inactive but still used on pages
        if (!$isFormActive) {
            echo '<div class="gf-inactive-form-warning" style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 3px; padding: 8px; margin-bottom: 10px; font-size: 12px; color: #856404;">';
            echo '<strong>⚠️ Form is inactive</strong><br>';
            echo 'This form is inactive but still in use.';
            echo '</div>';
        }

        $this->renderPagesList($formsPageCache[$formId], 'column');
        echo '</div>';

        $this->addColumnStyles();
    }

    /**
     * Add a "Pages" panel to the form editor sidebar.
     *
     * @param array $panels The existing sidebar panels
     * @param array $form The form array
     * @return array Modified panels array with the "Pages" panel added
     */
    public function addEditorSidebarPanel(array $panels, array $form): array
    {
        $panels[] = [
            'id' => 'pages_used_on',
            'title' => 'Pages',
            'nav_classes' => ['gf-pages-panel'],
            'body_classes' => ['gf-pages-panel-body']
        ];

        return $panels;
    }

    /**
     * Render the content for the "Pages" sidebar panel in the form editor.
     *
     * @param array $panel The panel configuration
     * @param array $form The form array
     */
    public function renderEditorSidebarPanel(array $panel, array $form)
    {
        $formId = (int) $form['id'];
        $formsPageCache = $this->buildFormsPageCache();
        $isFormActive = !empty($form['is_active']);

        echo '<div class="gf-pages-panel-content">';

        // Show warning if form is inactive but still used on pages
        if (!$isFormActive && isset($formsPageCache[$formId]) && !empty($formsPageCache[$formId])) {
            echo '<div class="gf-inactive-form-warning" style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; padding: 12px; margin-bottom: 15px; font-size: 13px; color: #856404;">';
            echo '<div style="display: flex; align-items: center; margin-bottom: 8px;"><span style="font-size: 16px; margin-right: 8px;">⚠️</span><strong>Form is inactive</strong></div>';
            echo '<p style="margin: 0; line-height: 1.4;">This form is currently disabled but is still being used on ' . count($formsPageCache[$formId]) . ' page(s).</p>';
            echo '</div>';
        }

        if (!isset($formsPageCache[$formId]) || empty($formsPageCache[$formId])) {
            echo '<p><em>This form is not currently used on any published pages.</em></p>';
        } else {
            echo '<p>This form is currently used on the following pages:</p>';
            echo '<ul class="gf-pages-list">';
            $this->renderPagesList($formsPageCache[$formId], 'sidebar');
            echo '</ul>';
        }

        echo '</div>';
        $this->addSidebarStyles();
    }

    /**
     * Render the list of pages for a form
     *
     * @param array $pages Array of page data
     * @param string $context Either 'column' or 'sidebar'
     */
    private function renderPagesList(array $pages, string $context)
    {
        foreach ($pages as $index => $pageData) {
            if ($context === 'column' && $index > 0) {
                echo '<div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #ddd;"></div>';
            }

            $this->renderPageItem($pageData, $context);
        }
    }

    /**
     * Render a single page item
     *
     * @param array $pageData Page data array
     * @param string $context Either 'column' or 'sidebar'
     */
    private function renderPageItem(array $pageData, string $context)
    {
        $locationInfo = $this->getLocationDescription($pageData['location']);
        $links = $this->generatePageLinks($pageData['id']);

        $containerClass = $context === 'column' ? 'gf-page-item-column' : 'gf-page-item';
        $actionsClass = $context === 'column' ? 'gf-page-actions-column' : 'gf-page-actions';

        if ($context === 'sidebar') {
            echo '<li class="' . $containerClass . '">';
        } else {
            echo '<div class="' . $containerClass . '">';
        }

        echo '<strong><a href="' . esc_url($links['edit']) . '" target="_blank">' . esc_html($pageData['title']) . '</a></strong>';
        echo '<br><small>Used ' . esc_html($locationInfo) . '</small>';
        echo '<div class="' . $actionsClass . '">';
        echo '<a href="' . esc_url($links['edit']) . '" target="_blank" class="button button-small">Edit</a> ';
        echo '<a href="' . esc_url($links['view']) . '" target="_blank" class="button button-small">View</a>';
        echo '</div>';

        if ($context === 'sidebar') {
            echo '</li>';
        } else {
            echo '</div>';
        }
    }

    /**
     * Get location description for display
     *
     * @param string $location The location key
     * @return string Formatted location description
     */
    private function getLocationDescription(string $location): string
    {
        $locationMap = [
            'content' => 'in shortcode',
            'meta' => 'in custom field',
            'blocks' => 'in blocks',
            'form_dropdown' => 'in form dropdown',
            'package_option' => 'on the package option page'
        ];

        return $locationMap[$location] ?? 'unknown location';
    }

    /**
     * Generate edit and view links for a page
     *
     * @param mixed $pageId The page ID or special identifier
     * @return array Array with 'edit' and 'view' URLs
     */
    private function generatePageLinks($pageId): array
    {
        // Handle special package option page entries
        if (is_string($pageId) && strpos($pageId, 'package-option') === 0) {
            // For package option pages, link to the options page in admin
            return [
                'edit' => admin_url('admin.php?page=acf-options-packages'),
                'view' => home_url('/pakketten') // Assuming packages page is at /pakketten
            ];
        }

        return [
            'edit' => get_edit_post_link($pageId),
            'view' => get_permalink($pageId)
        ];
    }

    /**
     * Add CSS styles for the column display
     */
    private function addColumnStyles()
    {
        if (!self::$stylesAdded) {
            echo '<style>
                .gf-pages-column-content {
                    min-width: 200px;
                }
                .gf-page-item-column {
                    margin-bottom: 5px;
                }
                .gf-page-actions-column {
                    margin-top: 5px;
                }
                .gf-page-actions-column .button {
                    box-shadow: none;
                    height: auto;
                    margin-right: 5px;
                    font-size: 11px;
                    padding: 2px 8px;
                }
                .gf-inactive-form-warning {
                    animation: gf-warning-fade-in 0.3s ease-in-out;
                }
                @keyframes gf-warning-fade-in {
                    from { opacity: 0; transform: translateY(-10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>';
            self::$stylesAdded = true;
        }
    }

    /**
     * Add CSS styles for the sidebar display
     */
    private function addSidebarStyles()
    {
        echo '<style>
            .gf-pages-panel-content {
                padding: 10px;
            }
            .gf-pages-list {
                list-style: none;
                margin: 0;
                padding: 0;
            }
            .gf-page-item {
                margin-bottom: 15px;
                padding: 10px;
                background: #f9f9f9;
                border-radius: 4px;
            }
            .gf-page-actions {
                margin-top: 8px;
            }
            .gf-page-actions .button {
                height: auto;
                margin-right: 5px;
            }
            .gf-inactive-form-warning {
                animation: gf-warning-fade-in 0.3s ease-in-out;
            }
            @keyframes gf-warning-fade-in {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>';
    }

    /**
     * Search for a Gravity Form ID within the post content.
     *
     * @param string $content
     * @return int|null
     */
    private function findGravityFormId($content)
    {
        if (empty($content)) {
            return null;
        }

        // Decode Unicode characters
        $decodedContent = $this->decodeUnicodeCharacters($content);

        // Check for Gravity Forms shortcodes specifically
        if (preg_match('/\[gravityform[^]]*id=["\']?(\d+)["\']?/i', $decodedContent, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    /**
     * Decode Unicode characters in content
     *
     * @param string $content
     * @return string
     */
    private function decodeUnicodeCharacters(string $content): string
    {
        return preg_replace_callback(
            '/\\\\u([0-9a-fA-F]{4})/',
            function ($matches) {
                return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16');
            },
            $content
        );
    }

    /**
     * Search for a form ID within JSON-encoded blocks in the post content.
     *
     * @param string $content
     * @param string $fieldName Field name to search for (e.g., 'gravity_form_id')
     * @param int $formId The form ID to compare against
     * @return bool
     */
    private function findFormIdInJsonBlocks($content, $fieldName, $formId)
    {
        // Check for ACF blocks
        if (preg_match_all('/<!-- wp:acf\/[^ ]+ (.*?) \/-->/', $content, $blockMatches)) {
            foreach ($blockMatches[1] as $json_data) {
                if ($this->checkJsonDataForFormId($json_data, $fieldName, $formId)) {
                    return true;
                }
            }
        }

        // Check for other block types that might contain form IDs
        $block_patterns = [
            '/<!-- wp:[^ ]+ (.*?) \/-->/',
            '/<!-- wp:[^ ]+ (.*?) -->[\s\S]*?<!-- \/wp:[^ ]+ -->/'
        ];

        foreach ($block_patterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                foreach ($matches[1] as $json_str) {
                    $json_data = json_decode($json_str, true);
                    if (!$json_data) {
                        continue;
                    }

                    // Recursively search for the form ID in the JSON data
                    if ($this->searchArrayForFormId($json_data, $formId)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Check JSON data for form ID references
     *
     * @param string $json_data
     * @param string $fieldName
     * @param int $formId
     * @return bool
     */
    private function checkJsonDataForFormId(string $json_data, string $fieldName, int $formId): bool
    {
        $decodedJson = json_decode($json_data, true);

        if (!$decodedJson || !isset($decodedJson['data'])) {
            return false;
        }

        // Check for direct form ID reference
        if (isset($decodedJson['data'][$fieldName]) && (int)$decodedJson['data'][$fieldName] === $formId) {
            return true;
        }

        // Check nested data structures
        foreach ($decodedJson['data'] as $key => $value) {
            if ($this->checkNestedDataForFormId($key, $value, $formId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check nested data for form ID references
     *
     * @param string $key
     * @param mixed $value
     * @param int $formId
     * @return bool
     */
    private function checkNestedDataForFormId(string $key, $value, int $formId): bool
    {
        // Check for nested arrays that might contain form IDs
        if (is_array($value)) {
            foreach ($value as $subKey => $subValue) {
                if (($subKey === 'form_id' || $subKey === 'gravity_form_id') &&
                    (int)$subValue === $formId) {
                    return true;
                }
            }
        }

        // Check for form ID in serialized data
        if (is_string($value) && strpos($value, 'form_id') !== false && strpos($value, $formId) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Recursively search an array for a form ID
     *
     * @param array $array The array to search
     * @param int $formId The form ID to look for
     * @return bool
     */
    private function searchArrayForFormId($array, $formId)
    {
        if (!is_array($array)) {
            return false;
        }

        foreach ($array as $key => $value) {
            if (stripos($key, 'hubspot') !== false) {
                continue;
            }

            // Check if this key looks like a form ID field - must be exact match
            if ($this->isFormIdKey($key) && $this->isMatchingFormId($value, $formId)) {
                return true;
            }

            if (is_array($value) && $this->searchArrayForFormId($value, $formId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a key represents a form ID field
     *
     * @param string $key
     * @return bool
     */
    private function isFormIdKey(string $key): bool
    {
        $form_id_keys = ['form_id', 'fallback_form_id'];
        return in_array($key, $form_id_keys, true);
    }

    /**
     * Check if a value matches the given form ID
     *
     * @param mixed $value
     * @param int $formId
     * @return bool
     */
    private function isMatchingFormId($value, int $formId): bool
    {
        if (is_numeric($value) && (int)$value === $formId) {
            return true;
        }

        if (is_string($value) && trim($value) === (string)$formId) {
            return true;
        }

        return false;
    }

    /**
     * Check all post meta for references to the form ID
     *
     * @param int $postId
     * @param int $formId
     * @return bool
     */
    private function checkAllPostMetaForForm($postId, $formId)
    {
        // First check specific known meta keys
        if ($this->checkDirectMetaKeys($postId, $formId)) {
            return true;
        }

        // Get all meta values for this post to check for legitimate form references
        $allMeta = $this->getAllPostMeta($postId);

        foreach ($allMeta as $meta) {
            if ($this->checkMetaValueForForm($meta, $formId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check direct meta keys for form ID
     *
     * @param int $postId
     * @param int $formId
     * @return bool
     */
    private function checkDirectMetaKeys(int $postId, int $formId): bool
    {
        $keys = ['form_id', 'fallback_form_id'];

        foreach ($keys as $key) {
            $metaValue = get_post_meta($postId, $key, true);
            if ($metaValue && $this->isMatchingFormId($metaValue, $formId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all post meta for a given post ID
     *
     * @param int $postId
     * @return array
     */
    private function getAllPostMeta(int $postId): array
    {
        global $wpdb;

        $table = $wpdb->prefix . 'postmeta';
        $sql = "SELECT meta_key, meta_value FROM $table WHERE post_id = %d AND meta_value != ''";

        return $wpdb->get_results($wpdb->prepare($sql, $postId));
    }

    /**
     * Check a meta value for form references
     *
     * @param object $meta
     * @param int $formId
     * @return bool
     */
    private function checkMetaValueForForm(object $meta, int $formId): bool
    {
        $metaValue = $meta->meta_value;

        // Skip HubSpot form references - they are not Gravity Forms
        if (stripos($meta->meta_key, 'hubspot') !== false) {
            return false;
        }

        // Check for Gravity Forms shortcodes/blocks first
        $foundFormId = $this->findGravityFormId($metaValue);
        if ($foundFormId === $formId) {
            return true;
        }

        // Check for form ID in JSON blocks (like ACF blocks)
        if ($this->findFormIdInJsonBlocks($metaValue, 'gravity_form_id', $formId)) {
            return true;
        }

        // Check for legitimate JSON patterns with form_id keys
        if ($this->checkJsonPatterns($metaValue, $formId)) {
            return true;
        }

        // Check serialized data for form references
        if ($this->checkSerializedData($metaValue, $formId)) {
            return true;
        }

        return false;
    }

    /**
     * Check for form ID in JSON patterns with proper keys
     */
    private function checkJsonPatterns($content, $formId)
    {
        // Skip if this content contains HubSpot form references
        if (stripos($content, 'hubspot') !== false) {
            return false;
        }

        // Look for JSON-like patterns with form-related keys - use word boundaries for exact matches
        $patterns = [
            '/"form_id":\s*"?' . preg_quote($formId, '/') . '"?(?![0-9])/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check serialized data for form references
     */
    private function checkSerializedData($content, $formId)
    {
        // Check if content looks like serialized data
        if (strpos($content, 'a:') === 0 || strpos($content, 's:') === 0) {
            $unserialized = @unserialize($content);
            if ($unserialized !== false) {
                return $this->searchArrayForFormId($unserialized, $formId);
            }
        }

        return false;
    }

    /**
     * Clear the forms-pages cache when a page is updated
     *
     * @param int $post_id
     */
    public function clearFormsPageCache($postId)
    {
        $post = get_post($postId);

        if ($post && $post->post_type === 'page') {
            delete_transient('custom_gf_forms_pages_cache');
        }
    }

    /**
     * Build and cache all form-page relationships
     *
     * @return array Array of form_id => [pages data]
     */
    private function buildFormsPageCache()
    {
        global $wpdb;

        $cacheKey = 'custom_gf_forms_pages_cache';
        $cachedData = get_transient($cacheKey);

        if ($cachedData !== false) {
            return $cachedData;
        }

        $formsPages = [];

        // Get all published pages once
        $allPages = $this->getAllPublishedPages();

        // Get all active Gravity Forms
        if (class_exists('GFAPI')) {
            // Get all Gravity Forms (active and inactive)
            $allForms = \GFAPI::get_forms(null, false);

            foreach ($allForms as $form) {
                $formId = (int) $form['id'];
                $formsPages[$formId] = [];

                // Check package option page forms (eigennummer theme specific)
                $packageOptionUsage = $this->checkPackageOptionPageForms($formId);

                if ($packageOptionUsage) {
                    // packageOptionUsage is now an array of results
                    foreach ($packageOptionUsage as $usage) {
                        $formsPages[$formId][] = $usage;
                    }
                }

                foreach ($allPages as $page) {
                    $foundLocation = $this->findFormInPage($page, $formId);
                    if ($foundLocation) {
                        $formsPages[$formId][] = [
                            'id' => $page->ID,
                            'title' => $page->post_title,
                            'location' => $foundLocation
                        ];
                    }
                }
            }
        }

        // Cache for 24 hours
        set_transient($cacheKey, $formsPages, DAY_IN_SECONDS);

        return $formsPages;
    }

    /**
     * Get all published pages
     *
     * @return array
     */
    private function getAllPublishedPages(): array
    {
        global $wpdb;

        $pageQuery = "
            SELECT ID, post_title, post_content
            FROM {$wpdb->posts}
            WHERE post_type = 'page'
            AND post_status = 'publish'
        ";

        return $wpdb->get_results($pageQuery);
    }

    /**
     * Check if a form is used in a specific page and return where it was found
     *
     * @param object $page
     * @param int $form_id
     * @return string|false Location where found, or false if not found
     */
    private function findFormInPage($page, $formId)
    {
        // Check for Gravity Form in post content
        $contentFormId = $this->findGravityFormId($page->post_content);
        if ($contentFormId == $formId) {
            return 'content';
        }

        // Check for ACF GravityForms add-on field (eigennummer theme specific)
        if ($this->checkAcfGravityFormsField($page->ID, $formId)) {
            return 'form_dropdown';
        }

        // Check all post meta for form references
        if ($this->checkAllPostMetaForForm($page->ID, $formId)) {
            return 'meta';
        }

        // Check for form ID in JSON blocks (ACF blocks, etc.)
        if ($this->findFormIdInJsonBlocks($page->post_content, 'gravity_form_id', $formId)) {
            return 'blocks';
        }

        return false;
    }

    /**
     * Check for form usage in ACF GravityForms add-on fields (eigennummer theme specific)
     *
     * @param int $postId
     * @param int $formId
     * @return bool
     */
    private function checkAcfGravityFormsField(int $postId, int $formId): bool
    {
        // Get flexible content field data for page-content
        $flexibleContent = get_field('page-content', $postId);

        if (!$flexibleContent || !is_array($flexibleContent)) {
            return false;
        }

        foreach ($flexibleContent as $layout) {
            if (isset($layout['acf_fc_layout']) && $layout['acf_fc_layout'] === 'page-content__form') {
                if (isset($layout['page-content__form']) && (int)$layout['page-content__form'] === $formId) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check for package option page form usage
     *
     * @param int $formId
     * @return array|false
     */
    private function checkPackageOptionPageForms($formId)
    {
        $results = [];

        // Check if this form is set as the main package form ID
        $packageFormId = get_field('package-page__form-id', 'option');
        $fallbackFormId = get_field('package-page__fallback-form-id', 'option');

        $isMainForm = $packageFormId && (int)$packageFormId === $formId;
        $isFallbackForm = $fallbackFormId && (int)$fallbackFormId === $formId;

        if (!$isMainForm && !$isFallbackForm) {
            return false;
        }

        // Add the option page entry
        $formType = $isMainForm ? 'Main Form' : 'Fallback Form';
        $results[] = [
            'id' => $isMainForm ? 'package-option-main' : 'package-option-fallback',
            'title' => "Package Options ({$formType})",
            'location' => 'package_option'
        ];

        // Get all package pages that use this form
        $packagePages = $this->getPackagePages();
        foreach ($packagePages as $page) {
            $results[] = [
                'id' => $page->ID,
                'title' => $page->post_title . ' (Package)',
                'location' => 'package_option'
            ];
        }

        return $results;
    }

    /**
     * Get all published package pages
     *
     * @return array
     */
    private function getPackagePages(): array
    {
        // Get active packages from the options
        $activePackages = get_field('package-page__active-packages', 'option');

        if (!$activePackages || !is_array($activePackages)) {
            return [];
        }

        $packagePages = [];

        foreach ($activePackages as $package) {
            if (isset($package->ID) && $package->post_status === 'publish') {
                $packagePages[] = (object) [
                    'ID' => $package->ID,
                    'post_title' => $package->post_title,
                    'post_content' => $package->post_content ?? ''
                ];
            }
        }

        return $packagePages;
    }

    /**
     * Handle AJAX request to refresh the pages column content
     */
    public function handleAjaxRefreshPagesColumn()
    {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'custom_gf_refresh_pages_column')) {
            wp_send_json_error('Invalid nonce');
            return;
        }

        // Get form ID from the request
        $formId = intval($_POST['form_id'] ?? 0);
        if (!$formId) {
            wp_send_json_error('Invalid form ID');
            return;
        }

        // Get form object
        $form = \GFAPI::get_form($formId);
        if (!$form || is_wp_error($form)) {
            wp_send_json_error('Form not found');
            return;
        }

        // Clear cache first to ensure fresh data
        $this->clearFormsPageCache($formId);

        // Capture the rendered column output
        ob_start();
        $this->renderPagesColumn((object)$form);
        $columnContent = ob_get_clean();

        // Return the rendered content
        wp_send_json_success([
            'column_content' => $columnContent,
            'form_id' => $formId
        ]);
    }
}
