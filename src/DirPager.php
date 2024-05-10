<?php
declare(strict_types=1);

namespace ISPager;

class DirPager extends Pager
{
    protected string $dirname;
    public function __construct(
        View $view,
        $dirname = '.',
        int $items_per_page = 10,
        int $links_count = 3,
        int $parameters = null,
        string $counter_param = 'page')
    {
        parent::__construct(
            $view,
            $items_per_page,
            $links_count,
            $parameters,
            $counter_param);
        // Удаляем последний символ /, если он имеется
        $this->dirname = ltrim($dirname, '/');
    }
    public function getItemsCount(): int
    {
        $countline = 0;
        // Открываем каталог
        if (($dir = opendir($this->dirname)) !== false) {
            while (($file = readdir($dir)) !== false) {
                // Если текущая позиция является файлом,
                // подсчитываем ее
                if (is_file($this->dirname.'/'.$file)) {
                    $countline++;
                }
            }
            // Закрываем каталог
            closedir($dir);
        }
        return $countline;
    }
    public function getItems(): array|int
    {
        // Текущая страница
        $current_page = $this->getCurrentPage();
        // Общее количество страниц
        $total_pages = $this->getPagesCount();
        // Проверяем, попадает ли запрашиваемый номер
        //страницы в интервал от минимального до максимального
        if ($current_page <= 0 || $current_page > $total_pages) {
            return 0;
        }
        // Извляекаем позиции текущей страницы
        $arr = [];
        // Номер, начиная с которого следует
        // выбирать строки файла
        $first = ($current_page - 1) * $this->getItemsPerPage();
        // Открываем каталог
        if (($dir = opendir($this->dirname)) === false) {
            return 0;
        }
        $i = -1;
        while (($file = readdir($dir)) !== false) {
            // Если текущая позиция является файлом
            if (is_file($this->dirname.'/'.$file)) {
                // Увеличиваем счетчик
                $i++;
                // Пока не достигнут номер $first
                // дострочно заканчиваем итерацию
                if ($i < $first) continue;
                // Если достигнут конец выборки, досрочно покидаем цикл
                if ($i > $first + $this->getItemsPerPage() - 1) break;
                // Помещаем пути к файлам в массив,
                // который будет возвращен методом
                $arr[] = $this->dirname.'/'.$file;
            }
        }
        // Закрываем каталог
        closedir($dir);
        return $arr;
    }
}