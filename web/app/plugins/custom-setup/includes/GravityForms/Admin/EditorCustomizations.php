<?php
namespace Custom\Setup\GravityForms\Admin;

class EditorCustomizations
{
    /**
     * Remove Post and Pricing fields from the field buttons in the editor.
     *
     * @param array $field_buttons
     * @return array
     */
    public function removeFieldButtons($field_buttons)
    {
        foreach($field_buttons as &$field_group) {
            if(is_array($field_group) && array_key_exists('fields', $field_group)) {
                $field_group['fields'] = array_filter( $field_group['fields'], function( $field ) {
                    return !in_array( $field['data-type'], [
                        'post_title',
                        'post_content',
                        'post_excerpt',
                        'post_tags',
                        'post_category',
                        'post_image',
                        'post_custom_field',
                        'product',
                        'quantity',
                        'option',
                        'shipping',
                        'total',
                        'multiselect',
                        'image_choice'
                    ]);
                } );

            }

        }

        return $field_buttons;
    }

    /**
     * Remove Post and Pricing field groups from the form editor.
     *
     * @param array $field_groups
     * @return array
     */
    public function removeFieldGroups($field_groups)
    {
        unset($field_groups['post_fields']);
        unset($field_groups['pricing_fields']);

        return $field_groups;
    }
}
