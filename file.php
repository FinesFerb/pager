<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $class = strstr($class, '/');
    require_once("./src$class.php");
});

$obj = new \ISPager\FilePager(
    new \ISPager\PagesList(),
    'text.txt',
    5
);

foreach ($obj->getItems() as $line) {
    echo htmlspecialchars($line). "<br/>";
}

echo "<p>$obj</p>";
