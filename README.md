# ISPager

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

А library to split results into multiple pages

## Install

Via Composer

```bash
$ composer require daniil/pager 
```

## Usage

```php
$obj = new ISPager\DirPager(
    new ISPager\PagesList(),
    'photos',
    3,
    2
);
echo '<pre>';
print_r($obj->getItems());
echo '</pre>';
echo "<p>$obj</p>";
```

```php
$obj = new ISPager\FilePager(
    new ISPager\ItemsRange(),
    'largetextfile.txt');
echo '<pre>';
print_r($obj->getItems());
echo '</pre>';
echo "<p>$obj</p>";
```

```php
try {
    $pdo = new РDО(
        'pgsql:host=localhost;dbname=test',
        'root'
    );
    $obj = new ISPager\PdoPager(
        new ISPager\ItemsRange(),
        $pdo,
        'table_name');
    echo '<pre>';
    print_r($obj->getItems());
    echo '</pre>';
    echo "<p>$obj</p>";
}
catch (PDOException $е) {
    echo "Can't connect to database";
}
```

## License

The MIT License (MIT). Please see [License File](https://github.com/dnoegel/php-xdg-base-dir/blob/master/LICENSE) for more information. 