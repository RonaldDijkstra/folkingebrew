<?php 

namespace Custom\Setup\Helpers;

class FileLocator
{
    public static function getViteAssetUrl(string $asset): string
    {
        $theme_path = get_template_directory();
        $theme_uri = get_template_directory_uri();
        $manifest_path = $theme_path . '/public/build/manifest.json';
        
        // Fallback to direct path if manifest doesn't exist
        if (!file_exists($manifest_path)) {
            return $theme_uri . '/' . $asset;
        }
        
        $manifest = json_decode(file_get_contents($manifest_path), true);
        
        // Check if asset exists in manifest
        if (isset($manifest[$asset]['file'])) {
            return $theme_uri . '/public/build/' . $manifest[$asset]['file'];
        }
        
        // Fallback to direct path
        return $theme_uri . '/' . $asset;
    }
}
