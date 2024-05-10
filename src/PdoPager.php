<?php
declare(strict_types=1);
/**
 * ISPager - построничная навигация
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @package ISPager
 * @subpackage ISPager\PdoPager
 */

namespace ISPager;

use PDO;

/**
 * Постраничная навигация для содержимого файла
 *
 * @author D.Kargapoltsev <kargapoltsev2016@gmail.com>
 */
class PdoPager extends Pager
{
    /**
     * @var PDO объект доступа к базе данных
     */
    protected PDO $pdo;
    /**
     * @var string название таблицы
     */
    protected string $tablename;
    /**
     * @var string where условие в sql запросе
     */
    protected string $where;
    /**
     * @var string параметры WHERE-условия
     */
    protected string $params;
    /**
     * @var string сортировка выборки
     */
    protected string $order;
    /**
     * @param View $view объект класса, осуществляющий вывод постраничной навигации
     * @param PDO $pdo объект Pdo для взаимодействия с базой данных
     * @param string $tablename название таблицы
     * @param string $where условие where в sql запросе
     * @param string $params параметры WHERE-условия
     * @param string $order сортировка выборки
     * @param int $items_per_page количество позиций на одной странице
     * @param int $links_count количество видимых ссылок слева и справа
     * @param int|null $parameters Get значение параметр для опрделения номера страницы
     * @param string $counter_param Get парметр имя
     */
    public function __construct(
        View $view,
        PDO $pdo,
        string $tablename,
        string $where = '',
        string $params = '',
        string $order = '',
        int $items_per_page = 10,
        int $links_count = 3,
        int $parameters = null,
        string $counter_param = 'page')
    {
        parent::__construct($view,
        $items_per_page,
        $links_count,
        $parameters,
        $counter_param);

        $this->pdo = $pdo;
        $this->tablename = $tablename;
        $this->where = $where;
        $this->params = $params;
        $this->order = $order;
    }
    /**
     * @return int возвращается количество записей
     */
    public function getItemsCount(): int
    {
        // формируем запрос на получение общего количества записей
        $stmt = $this->pdo->prepare("SELECT count(*) AS total FROM {$this->tablename} {$this->where}");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    /**
     * @return array|int возвращается массив с записями или ошибка в виде 0
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
        // Номер, начиная с которого следует
        // выбирать строки таблицы
        $first = ($current_page - 1) * $this->getItemsPerPage();
        // Формируем запрос на получение записей и выводим результат
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tablename} {$this->where} {$this->order} LIMIT :limit OFFSET :offset");
        $stmt->execute([
            'limit' => $this->items_per_page,
            'offset' => $first]);
        return $stmt->fetchAll();
    }
}
