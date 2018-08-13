<?php

declare(strict_types=1);

namespace Peak\Common;

/**
 * PaginatorBuilder
 */
class PaginatorBuilder
{
    /**
     * Items per page
     * @var integer
     */
    public $items_per_page = 25;

    /**
     * Total item count
     * @var integer
     */
    public $items_count = 0;

    /**
     * Current page
     * @var integer
     */
    public $current_page = 1;

    /**
     * Pages ranges
     * @var integer
     */
    public $pages_range = null;

    /**
     * Set items per page
     *
     * @param  integer $n
     * @return $this
     */
    public function itemsPerPage(int $n): PaginatorBuilder
    {
        $this->items_per_page = $n;
        return $this;
    }

    /**
     * Set total items count
     *
     * @param  integer $n
     * @return $this
     */
    public function itemsCount(int $n): PaginatorBuilder
    {
        $this->items_count = $n;
        return $this;
    }

    /**
     * Set current page
     *
     * @param  integer $n
     * @return $this
     */
    public function currentPage(int $n): PaginatorBuilder
    {
        $this->current_page = $n;
        return $this;
    }

    /**
     * Set pages range
     *
     * @param  integer $n
     * @return $this
     */
    public function pagesRange(int $n): PaginatorBuilder
    {
        $this->pages_range = $n;
        return $this;
    }

    /**
     * Build Paginator
     *
     * @return Paginator
     */
    public function build(): Paginator
    {
        return new Paginator(
            $this->items_per_page,
            $this->items_count,
            $this->current_page,
            $this->pages_range
        );
    }
}
