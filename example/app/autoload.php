<?php

include __DIR__ . '/../vendor/autoload.php';

// TODO this could probably be handled better. Probably ...
spl_autoload_register(function ($class) {
    $namespaceParts = explode('\\', $class);
    $className = array_pop($namespaceParts);
    $namespaceParts = array_map('strtolower', $namespaceParts);

    $vendorName = array_shift($namespaceParts);

    if ($vendorName == "example") {
        $namespacePath = join('/', $namespaceParts);
        include 'src/' . $namespacePath . '/' . $className . '.php';
    }

});
