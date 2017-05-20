<?php

namespace Peak\Common;

use IteratorAggregate;
use ArrayIterator;

/**
 * Paginator model
 */
class Paginator implements IteratorAggregate
{

    public $items_per_page = 25;
    public $items_count    = 0;
    public $item_start     = 0;
    public $item_end       = 0;
    public $offset         = 0;
    public $pages_count    = 0;
    public $current_page   = 1;
    public $next_page      = null;
    public $prev_page      = null;
    public $first_page     = null;
    public $last_page      = null;
    public $pages          = [];
    public $pages_range    = null;
  
    /**
     * Constructor
     *
     * @param integer  $items_per_page
     * @param integer  $items_count
     * @param integer  $current_page
     * @param integer  $range
     */
    public function __construct($items_per_page, $items_count, $current_page = 1, $range = null)
    {
        $this->items_per_page = $items_per_page;
        $this->items_count = $items_count;
        $this->current_page = $current_page;

        $this->calculate();

        if (isset($range)) {
            $this->setPagesRange($range);
        }
    }

    /**
     * Get $pages array
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->pages);
    }

    /**
     * Set page and (re)calculate
     *
     * @param integer $page
     */
    public function setPage($page)
    {
        $this->current_page = $page;
        $this->calculate();
        return $this;
    }

    /**
     * Calculate stuff for pagination
     */
    public function calculate()
    {
        // calculate how many page
        if ($this->items_count > 0 && $this->items_per_page > 0) {
            $this->pages_count = ceil(($this->items_count / $this->items_per_page));
        } elseif ($this->items_count == 0) {
            $this->pages_count = 0;
        } else {
            $this->pages_count = 1;
        }
        
        // generate pages array
        $this->pages = [];
        if ($this->pages_count < 1) {
            $this->pages = [];
        } else {
            $this->pages = range(1, $this->pages_count);
        }

        $this->first_page = empty($this->pages) ? null : 1;
        $this->last_page = ($this->pages_count < 1) ? null : $this->pages_count;

        // check current page
        if (!$this->isPage($this->current_page)) {
            trigger_error(__CLASS__.': page '.$this->current_page.' doesn\'t exists', E_USER_NOTICE);
            if ($this->current_page > $this->pages_count) {
                $this->current_page = $this->pages_count;
            } elseif ($this->current_page < 1) {
                $this->current_page = 1;
            } else {
                $this->current_page = 1;
            }
        }
        
        // prev/next page
        $this->prev_page = ($this->current_page > 1) ? $this->current_page  - 1 : null;
        $this->next_page = (($this->current_page + 1) <= $this->pages_count) ? $this->current_page + 1 : null;
        
        // item start/end page
        $this->item_start = (($this->current_page - 1) * $this->items_per_page);
        $this->item_end = $this->item_start + $this->items_per_page;
        
        if (($this->items_count != 0)) {
            ++$this->item_start;
        }
        if ($this->item_end > $this->items_count) {
            $this->item_end = $this->items_count;
        }

        // item start offset
        $this->offset = $this->item_start - 1;

        return $this;
    }

    /**
     * Set a pages ranges list array
     * Range represent the number of page before and after the current page
     * Example:
     * If there is 100 pages, pages range of 5 and current page is 10,
     * pages array will be limited to: 5, 6, 7, 8, 9, 10, 11, 12, 13,14 ,15
     *
     * @param  integer
     * @return array
     */
    public function setPagesRange($range = null)
    {
        if (is_numeric($range) && ($range <= $this->pages_count) && is_array($this->pages)) {
            $pages_range = [];
            $diff = $range - $range - $range;
            for ($i = $diff; $i <= $range; ++$i) {
                if ($i < 0) {
                    $index = $this->current_page + $i;
                } elseif ($i == 0) {
                    $index = $this->current_page;
                } else {
                    $index = $this->current_page + $i;
                }
                
                if (!in_array($index, $this->pages)) {
                    continue;
                }
                $pages_range[] = $index;
            }

            $this->pages = $pages_range;
        }

        $this->pages_range = $range;

        return $this;
    }
    
    /**
     * Check if a page exists
     *
     * @param  integer $page_number
     * @return bool
     */
    public function isPage($number)
    {
        return (in_array($number, $this->pages)) ? true : false;
    }
}
