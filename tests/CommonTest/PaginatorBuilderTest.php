<?php
use PHPUnit\Framework\TestCase;

use Peak\Common\Paginator;
use Peak\Common\PaginatorBuilder;

class PaginatorBuilderTest extends TestCase
{
 
    /**
     * Create object
     */
    function testCreateObject()
    {
        $paginator = (new PaginatorBuilder())
            ->itemsPerPage(10)
            ->itemsCount(280)
            ->currentPage(15)
            ->pagesRange(3)
            ->build();

        $this->assertInstanceof(Paginator::class, $paginator);
        // $this->assertTrue($paginator->items_per_page == 10);
        // $this->assertTrue($paginator->items_count == 280);
        // $this->assertTrue($paginator->current_page == 1);

        print_r($paginator);
    }

    

}