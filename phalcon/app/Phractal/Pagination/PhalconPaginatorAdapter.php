<?php

namespace Phasty\Phractal\Pagination;

use League\Fractal\Pagination\PaginatorInterface;

/**
 * A page adapter for phalcon page paginator.
 *
 */
class PhalconPaginatorAdapter implements PaginatorInterface
{
    /**
     * The page instance.
     *
     * @var \StdObject
     */
    protected $page;

    /**
     * Create a new illuminate pagination adapter.
     *
     * @param  $page
     */
    public function __construct($page)
    {
        $this->page = $page;
    }

    /**
     * Get the current page.
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->page->current;
    }

    /**
     * Get the last page.
     *
     * @return int
     */
    public function getLastPage()
    {
        return $this->page->last;
    }

    /**
     * Get the total.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->page->total_items;
    }

    /**
     * Get the count.
     *
     * @return int
     */
    public function getCount()
    {
        return count($this->page->items);
    }

    /**
     * Get the number per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->page->limit;
    }

    /**
     * Get the url for the given page.
     *
     * @param int $page
     *
     * @return string
     */
    public function getUrl($page)
    {
        return 'page=' . $this->page->current;
    }

    /**
     * Get the page instance.
     *
     * @return \StdObject
     */
    public function getPaginator()
    {
        return $this->page;
    }
}
