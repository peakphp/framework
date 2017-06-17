<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Helper\Grid;

class GridTest extends TestCase
{

    public $gridData = [
        0 => [
            'id' => 1,
            'user' => 'bob',
            'email' => 'bob@superbob.com',
            'joindate' => '2017-01-09 21:15:01',
        ],
        1 => [
            'id' => 2,
            'user' => 'foo',
            'email' => 'foobar@gmail.com',
            'joindate' => '2017-04-09 21:15:01',
        ],
    ];

    function testGeneral()
    {
        ob_start();
        $grid = new Grid($this->gridData);

        $order = 'email';
        $by = 'asc';
        $grid
            ->setColumnSorting($order, $by)
            ->setColumnsUrl(
                'users/order/:column/by/:by',
                ['by' => ($by === 'asc') ? 'desc' : 'asc']
            )
            ->setTableClasses('table')
            ->setColumns([
                'id'       => '#',
                'user'     => 'User',
                'email'    => 'Email',
                'joindate' => 'Join date',
                ':edit'      => '',
            ])
            ->addRowDataAttr('data-id', 'id')
            ->addRowDataAttr('data-joindate', 'joindate')
            ->addHook(':edit', function($v, $p) {
                return '<a href="'.$p['url'].'">Edit</a>';
            }, [
                'url'    => 'user/edit/id/:id',
                'bind'   => [':id' => 'id']
            ])
            ->render();

        $content = ob_get_clean();
        $this->assertTrue(mb_strlen($content) > 1);
    }

}