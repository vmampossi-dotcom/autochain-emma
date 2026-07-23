<?php
$base = __DIR__;
$patterns = ['chunk(', 'each('];
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base . '/vendor')) as $file) {
    if (!$file->isFile()) continue;
    if (strtolower($file->getExtension()) !== 'php') continue;
    $content = file_get_contents($file->getPathname());
    foreach ($patterns as $pattern) {
        if (strpos($content, $pattern) !== false) {
            echo $file->getPathname() . "\n";
            break;
        }
    }
}
