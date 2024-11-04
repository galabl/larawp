<?php

namespace Galabl\LaraWp;

class LaraWpInstaller
{
    public static function postCreateProject()
    {
        // Get the base directory (current working directory) for the project
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

        // Check if the framework path exists
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
                $content = str_replace(['LaraWp', 'lara_wp', 'lara-wp'], [$namespace, $underscoreName, $hyphenName], $content);
                file_put_contents($file, $content);
            }
        }

        echo "Plugin created successfully with hyphen name: $hyphenName\n";
    }
}