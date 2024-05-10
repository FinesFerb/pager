<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $class = strstr($class, '/');
    require_once("./src$class.php");
});

$obj = new \ISPager\DirPager(
    new \ISPager\PagesList(),
    'photos',
    3,
    2
);

// Содержимое текущей страницы
foreach ($obj->getItems() as $img) {
    echo "<img src='$img' alt=''/> ";
}
// Постраничная навигация
echo "<p>$obj</p>";