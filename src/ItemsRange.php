<?php
declare(strict_types=1);
/**
 * ISPager - постраничная навигация
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @package ISPager
 * @subpackage ISPager\ItemsRange
 */

namespace ISPager;

/**
 * Класс представления постраничной навигации в виде диапозона элементов
 *
 * @author D.Kargapoltsev <kargapoltsev2016@gmail.com>
 */
class ItemsRange extends View
{
    /**
     * @param $first
     * @param $last
     * @return string
     */
    public function range($first, $last): string
    {
        return "[{$first}-{$last}]";
    }

    /**
     * @param Pager $pager
     * @return string
     */
    public function render(Pager $pager): string
    {
        // Объект постраничной навигации
        $this->pager = $pager;
        // Строка для возвращаемого результата
        $return_page = "";
        // Текущий номер страницы
        $current_page = $this->pager->getCurrentPage();
        // Общее количество страниц
        $total_pages = $this->pager->getPagesCount();

        // ссылки слева
        if ($current_page - $this->pager->getVisibleLinkCount() > 1) {
            // первая ссылка и потом ...
            $range = $this->range(1, $this->pager->getItemsPerPage());
            $return_page .= $this->link($range).' ... ';

            $init = $current_page - $this->pager->getVisibleLinkCount();
            for ($i = $init; $i < $current_page; $i++) {
                $range = $this->range(($i - 1) * $this->pager->getItemsPerPage() + 1,
                    $i * $this->pager->getItemsPerPage());
                $return_page .= $this->link($range, $i).' ';
            }
        } else {
            for ($i = 1; $i < $current_page; $i++) {
                $range = $this->range(($i - 1) * $this->pager->getItemsPerPage() + 1,
                    $i * $this->pager->getItemsPerPage());
                $return_page .= $this->link($range, $i).' ';
            }
        }

        // текущая
        $return_page .= $this->range(($current_page - 1) * $this->pager->getItemsPerPage() + 1,
                $current_page * $this->pager->getItemsPerPage()).' ';

        // ссылки справа
        if ($current_page + $this->pager->getVisibleLinkCount() < $total_pages) {
            $init = $current_page + 1;
            $cond = $current_page + $this->pager->getVisibleLinkCount();
            for ($i = $init; $i <= $cond; $i++) {
                $range = $this->range(($i - 1) * $this->pager->getItemsPerPage() + 1,
                    $i * $this->pager->getItemsPerPage());
                $return_page .= $this->link($range, $i).' ';
            }
            // последняя ссылка через ...
            $range = $this->range(($total_pages - 1) * $this->pager->getItemsPerPage() + 1,
                $this->pager->getItemsCount());
            $return_page .= ' ... '.$this->link($range, $total_pages);
        } else {
            $init = $current_page + 1;
            for ($i = $init; $i <= $total_pages; $i++) {
                $range = $this->range(($i - 1) * $this->pager->getItemsPerPage() + 1,
                    $i * $this->pager->getItemsPerPage());
                $return_page .= $this->link($range, $i).' ';
            }
        }
        return $return_page;
    }
}