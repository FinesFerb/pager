<?php
declare(strict_types=1);
/**
 * ISPager - постраничная навигация
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @package ISPager
 * @subpackage ISPager\PagesList
 */

namespace ISPager;

/**
 * Класс представления постраничной навигации в виде списка страниц
 *
 * @author D.Kargapoltsev <kargapoltsev2016@gmail.com>
 */
class PagesList extends View
{
    /**
     * Формируем строку постраничной навигации
     *
     * @param Pager $pager объект постраничной навигации
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

        // Ссылка на первую страницу
        $return_page .= $this->link('&lt;&lt;', 1).' ... ';
        // Выводим ссылку "Назад", если это не первая страница
        if ($current_page != 1) {
            $return_page .= $this->link('&lt;', $current_page - 1).' ... ';
        }

        // Выводим предыдущие элементы
        if ($current_page > $this->pager->getVisibleLinkCount() + 1) {
            $init = $current_page - $this->pager->getVisibleLinkCount();
            for ($i = $init; $i < $current_page; $i++) {
                $return_page .= $this->link(strval($i), $i).' ';
            }
        } else {
            for ($i = 1; $i < $current_page; $i++) {
                $return_page .= $this->link(strval($i), $i).' ';
            }
        }
        // Выводим текущий элемент
        $return_page .= "$i ";
        // Выводим следующие элементы
        if ($current_page + $this->pager->getVisibleLinkCount() < $total_pages) {
            $cond = $current_page + $this->pager->getVisibleLinkCount();
            for ($i = $current_page + 1; $i <= $cond; $i++) {
                $return_page .= $this->link(strval($i), $i). ' ';
            }
        } else {
            for ($i = $current_page + 1; $i <= $total_pages; $i++) {
                $return_page .= $this->link(strval($i), $i). ' ';
            }
        }

        //  Выводим ссылку вперед, если это не последняя страница
        if ($current_page != $total_pages) {
            $return_page .= ' ... '. $this->link('&gt;', $current_page + 1);
        }
        // Ссылка на последнию страницу
        $return_page .= ' ... '.$this->link('&gt;&gt;', $total_pages);
        return $return_page;
    }
}