<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Paginator;

class PaginatorTest extends TestCase
{
	
	/**
	 * instantiate class for tests
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

	/**
	 * Out of range current_page
	 */
	function testNonExistentPages()
	{
		try {
			$ipp          = 12;
			$total        = 198;
			$current_page = 80;
			$pagin = new Paginator($ipp, $total, $current_page);
		} catch(Exception $e) {
			$error1 = true;
		}

		$this->assertTrue(isset($error1));

		try {
			$ipp          = 12;
			$total        = 198;
			$current_page = -10;
			$pagin = new Paginator($ipp, $total, $current_page);
		} catch(Exception $e) {
			$error2 = true;
		}

		$this->assertTrue(isset($error2));
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