<?php

namespace Galabl\LaraWp;

class LaraWpInstaller
{
    public static function postCreateProject()
    {
        $baseDir = getcwd();
        $hyphenName = basename($baseDir); // The project directory name is the hyphen name

        // Derive the namespace and underscore name from the hyphen name
        $namespace = str_replace(" ", "", ucwords(str_replace("-", " ", $hyphenName)));
        $underscoreName = str_replace("-", "_", $hyphenName);

        echo "Hyphen name: $hyphenName\n";
        echo "Namespace: $namespace\n";
        echo "Underscore name: $underscoreName\n";

        // Define the framework path
        $frameworkPath = "$baseDir/vendor/galabl/larawp-framework";

        if (!is_dir($frameworkPath)) {
            echo "Error: 'vendor/galabl/larawp-framework' not found.\n";
            exit(1);
        }

        // Copy files from larawp-framework to the plugin directory
        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($frameworkPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            ) as $item
        ) {
            $targetPath = $baseDir . DIRECTORY_SEPARATOR . $iterator->getSubPathname();
            if ($item->isDir()) {
                mkdir($targetPath, 0755, true);
            } else {
                copy($item, $targetPath);
            }
        }

        // Rename main plugin file
        rename("$baseDir/plugin.php", "$baseDir/$hyphenName.php");

        // Replace placeholders in all PHP files within the project directory
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($baseDir));
        foreach ($iterator as $file) {
            if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $content = file_get_contents($file);
                $content = str_replace(['LaraWp', 'lara_wp', 'lara-wp', 'LARAWP'], [$namespace, $underscoreName, $hyphenName, strtoupper($namespace)], $content);
                file_put_contents($file, $content);
            }
        }

        // Update composer.json with new namespace and package name
        $composerFilePath = "$baseDir/composer.json";
        if (file_exists($composerFilePath)) {
            $composerData = json_decode(file_get_contents($composerFilePath), true);

            // Update package name and namespace
            $composerData['name'] = "vendor/$hyphenName";
            if (isset($composerData['autoload']['psr-4'])) {
                foreach ($composerData['autoload']['psr-4'] as $key => $value) {
                    if (strpos($key, 'LaraWp\\') === 0) {
                        $newKey = str_replace('LaraWp', $namespace, $key);
                        $composerData['autoload']['psr-4'][$newKey] = $value;
                        unset($composerData['autoload']['psr-4'][$key]);
                    }
                }
            }

            // Save the updated composer.json
            file_put_contents($composerFilePath, json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            echo "Updated composer.json with new namespace and package name.\n";
        } else {
            echo "composer.json not found in $baseDir.\n";
        }

        echo "Plugin created successfully with hyphen name: $hyphenName\n";
    }
}
