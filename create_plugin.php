<?php

$plugin_name = readline('Enter the name of your plugin(example: User Roles): ');

$namespace = str_replace(" ", "", ucwords($plugin_name));
$underscore_name = strtolower( str_replace( " ", "_", $plugin_name ) );
$hyphen_name = strtolower( str_replace( " ", "-", $plugin_name ) );

echo "Namespace: $namespace\n";
echo "Underscore name: $underscore_name\n";
echo "Hyphen name: $hyphen_name\n";

echo "Building the plugin...\n";
mkdir($hyphen_name, 0755);
foreach (
    $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator('boilerplate', \RecursiveDirectoryIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::SELF_FIRST) as $item
) {
    if ($item->isDir()) {
        mkdir($hyphen_name . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
    } else {
        copy($item, $hyphen_name . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
    }
}

$dir = __DIR__ . "/$hyphen_name";

echo "Generated $dir\n";

rename("$dir/plugin.php", "$dir/$hyphen_name.php" );

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $content = file_get_contents($file);
        $content = preg_replace('/LaraWp/', $namespace, $content);
        $content = preg_replace('/lara_wp/', $underscore_name, $content);
        $content = preg_replace('/lara-wp/', $hyphen_name, $content);
        file_put_contents($file, $content);
    }
}