<?php
use PHPUnit\Framework\TestCase;

use Peak\Paginator;

/**
 * @package    Peak\Paginator
 */
class PaginatorTest extends TestCase
{
	
	/**
	 * instanciate class for tests
	 */
	function setUp()
	{		
	}
		 
	/**
	 * Create object
	 */
	function testCreateObject()
	{
		$ipp   = 16;
		$total = 198;
		$pagin = new Paginator($ipp, $total, 3);
	}

	/**
	 * Test with normal valid infos
	 */
	function testNormalInfos()
	{
		$ipp          = 16;
		$total        = 198;
		$current_page = 3;

		$pagin = new Paginator($ipp, $total, $current_page);

		$this->assertTrue($pagin->current_page === $current_page);
		$this->assertTrue($pagin->items_count === $total);
		$this->assertTrue($pagin->items_per_page === $ipp);

		$this->assertTrue($pagin->next_page === ($current_page + 1));
		$this->assertTrue($pagin->prev_page === ($current_page - 1));
	}

	/**
	 * Test page change
	 */
	function testChanges()
	{
		$ipp          = 12;
		$total        = 198;
		$current_page = 3;

		$pagin = new Paginator($ipp, $total, $current_page);

		$this->assertTrue($pagin->current_page === $current_page);
		$this->assertTrue($pagin->items_count === $total);
		$this->assertTrue($pagin->items_per_page === $ipp);

		$pagin->setPage(2);
		$this->assertTrue($pagin->current_page === 2);
	}

	function testNonExistentPages()
	{
		// throw an warning normally but for the test we will turn it off temporary
		set_error_handler(function() { /* ignore errors */ });

		$ipp          = 12;
		$total        = 198;
		$current_page = 80;

		//the current page number is too high so we should get the last page instead with a warning
		$pagin = new Paginator($ipp, $total, $current_page);
		$this->assertTrue($pagin->current_page == 17);

		//this page number is too low so we should get the first page instead with a warning
		$pagin->setPage(-10);
		$this->assertTrue($pagin->current_page == 1);

		restore_error_handler();
	}

	function testPagesRanges()
	{
		$ipp          = 2;
		$total        = 198;
		$current_page = 15;

		//this page number is too high so we should get the last page instead with a warning
		$pagin = new Paginator($ipp, $total, $current_page);

		$pagin->setPagesRange(3);

		// print_r($pagin);

	}

}