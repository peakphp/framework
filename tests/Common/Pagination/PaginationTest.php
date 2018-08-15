<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Pagination\Pagination;

class PaginationTest extends TestCase
{
	
	/**
	 * instantiate class for tests
	 */
	function setUp()
	{		
	}

    /**
     * @throws Exception
     */
	function testNormalInfos()
	{
		$ipp          = 16;
		$total        = 198;
		$current_page = 3;

		$pagin = new Pagination($ipp, $total, $current_page);

		$this->assertTrue($pagin->current_page === $current_page);
		$this->assertTrue($pagin->items_count === $total);
		$this->assertTrue($pagin->items_per_page === $ipp);

		$this->assertTrue($pagin->next_page === ($current_page + 1));
		$this->assertTrue($pagin->prev_page === ($current_page - 1));
	}

	/**
	 * Test page change
     * @throws Exception
	 */
	function testChanges()
	{
		$ipp          = 12;
		$total        = 198;
		$current_page = 3;

		$pagin = new Pagination($ipp, $total, $current_page);

		$this->assertTrue($pagin->current_page === $current_page);
		$this->assertTrue($pagin->items_count === $total);
		$this->assertTrue($pagin->items_per_page === $ipp);

		$pagin->setPage(2);
		$this->assertTrue($pagin->current_page === 2);
	}

	/**
	 * Out of range current_page
     * @throws Exception
	 */
	function testNonExistentPages()
	{
		try {
			$ipp          = 12;
			$total        = 198;
			$current_page = 80;
			$pagin = new Pagination($ipp, $total, $current_page);
		} catch(Exception $e) {
			$error1 = true;
		}

		$this->assertTrue(isset($error1));

		try {
			$ipp          = 12;
			$total        = 198;
			$current_page = -10;
			$pagin = new Pagination($ipp, $total, $current_page);
		} catch(Exception $e) {
			$error2 = true;
		}

		$this->assertTrue(isset($error2));
	}

    /**
     * Test set page range
     * @throws Exception
     */
	function testPagesRanges()
	{
		$ipp          = 2;
		$total        = 198;
		$current_page = 15;

		//this page number is too high so we should get the last page instead with a warning
		$pagin = new Pagination($ipp, $total, $current_page);

		$pagin->setPagesRange(3);

		// print_r($pagin);

	}

    /**
     * Test getIterator
     * @throws Exception
     */
	function testIterator()
    {
        $ipp          = 2;
        $total        = 10;
        $current_page = 1;

        $pagin = new Pagination($ipp, $total, $current_page);

        $i = 0;
        foreach ($pagin as $page) {
            ++$i;
        }

        $this->assertTrue($i == 5);
    }

    /**
     * @throws Exception
     */
    function testException()
    {
        $ipp          = 10;
        $total        = 0;
        $current_page = 1;

        try {
            $pagin = new Pagination($ipp, $total, $current_page);
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }

}