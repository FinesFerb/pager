<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $class = strstr($class, '/');
    require_once("./src$class.php");
});

try {
    $pdo = new PDO('pgsql:host=localhost;dbname=catalog', 'Daniil', 'admin');

    $obj = new \ISPager\PdoPager(
        new \ISPager\ItemsRange(),
        $pdo,
        'languages',
        items_per_page: 5
    );
    // содержимое текущей страницы
    foreach ($obj->getItems() as $item) {
        echo htmlspecialchars($item['name']) . '<br/>';
    }
    // постраничная навигация
    echo "<p>$obj</p>";
} catch (PDOException $e) {
    echo $e->getMessage();
}
