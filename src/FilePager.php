<?php
declare(strict_types=1);
/**
 * ISPager - построничная навигация
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @package ISPager
 * @subpackage ISPager\FilePager
 */

namespace ISPager;

/**
 * Постраничная навигация для содержимого файла
 *
 * @author D.Kargapoltsev <kargapoltsev2016@gmail.com>
 */
class FilePager extends Pager
{
    /**
     * @var string путь к файлу
     */
    protected string $filename;

    /**
     * @param View $view объект класса, осуществляющий вывод постраничной навигации
     * @param string $filename путь к файлу
     * @param int $items_per_page количество позиций на одной странице
     * @param int $links_count количество видимых ссылок слева и справа
     * @param int|null $parameters  Get значение параметр для опрделения номера страницы
     * @param string $counter_param Get парметр имя
     */
    public function __construct(
        View $view,
        string $filename = '.',
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
        $this->filename = $filename;
    }

    /**
     * @return int
     */
    public function getItemsCount(): int
    {
        $countline = 0;
        // Открываем файл
        $fd = fopen($this->filename, 'r');
        if ($fd) {
            // Подсчитываем количество записей
            while (!feof($fd)) {
                fgets($fd);
                $countline++;
            }
        }
        // Закрываем файл
        return $countline;
    }

    /**
     * @return array|int
     */
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
        // Открываем файл
        if (($fd = fopen($this->filename, 'r')) === false) {
            return 0;
        }
        $i = -1;
        while (!feof($fd)) {
            $str = fgets($fd);
            // Увеличиваем счетчик
            $i++;
            // Пока не достигнут номер $first
            // досрочно заканчиваем итерацию
            if ($i < $first) continue;
            // Если достигнут конец выборки, досрочно покидаем цикл
            if ($i > $first + $this->getItemsPerPage() - 1) break;
            // Помещаем строки из файла в массив,
            // который будет возвращен методом
            $arr[] = $str;
        }
        fclose($fd);
        return $arr;
    }
}