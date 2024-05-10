<?php
declare(strict_types=1);

namespace ISPager;

abstract class Pager
{
    protected View $view;
    protected ?int $parameters;
    protected string $counter_param;
    protected int $links_count;
    protected int $items_per_page;

    public function __construct(
        View $view,
        int $items_per_page = 10,
        int $links_count = 3,
        int $parameters = null,
        string $counter_param = 'page'
    )
    {
        $this->view = $view;
        $this->parameters = $parameters;
        $this->counter_param = $counter_param;
        $this->items_per_page = $items_per_page;
        $this->links_count = $links_count;
    }

    abstract public function getItemsCount(): int;
    abstract public function getItems(): array|int;
    public function getVisibleLinkCount(): int
    {
        return $this->links_count;
    }
    public function getParameters(): ?int
    {
        return $this->parameters;
    }
    public function getCounterParam(): string
    {
        return $this->counter_param;
    }
    public function getItemsPerPage(): int
    {
        return $this->items_per_page;
    }
    public function getCurrentPagePath(): string
    {
        return $_SERVER['PHP_SELF'];
    }
    public function getCurrentPage(): int
    {
        if (isset($_GET[$this->getCounterParam()])) {
            return intval($_GET[$this->getCounterParam()]);
        } else {
            return 1;
        }
    }
    public function getPagesCount(): int
    {
        // Количество позиций
        $total = $this->getItemsCount();
        // Вычтсляем количество страниц
        $result = intval($total / $this->getItemsPerPage());
        if (floatval($total / $this->getItemsPerPage()) - $result != 0) {
            $result++;
        }
        return $result;
    }
    public function render(): string
    {
        return $this->view->render($this);
    }
    public function __toString(): string
    {
        return $this->render();
    }
}