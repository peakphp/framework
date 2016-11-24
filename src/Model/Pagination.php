<?php
/**
 * Pagination model for Peak_Model_Zendatable
 *
 * @author  Francois Lajoie
 * @version $Id: Pagination.php 694 2014-03-03 21:16:42Z snake386@hotmail.com $
 */
class Peak_Model_Pagination
{
    protected $_it_perpage = 25;
    protected $_it_start;
    protected $_it_end;

    protected $_total = null;
    protected $_pcount;

    protected $_curpage;
    protected $_nextpage;
    protected $_prevpage;
    protected $_pages = array();
    protected $_pages_range = null;

    protected $_db_object;

    // query params
    protected $_params = array();
    
    private $_is_calculated = false;
    
    /**
     * Create object, add db object if specified
     *
     * @param mixed $db_object
     */
    public function __construct($db_object = null)
    {
        $this->_db_object = $db_object;
    }

    /**
     * Set number items per page
     *
     * @param  integer $ipp
     * @return object $this
     */
    public function setItemsPerPage($ipp)
    {
        if(is_numeric($ipp)) $this->_it_perpage = $ipp;
        return $this;
    }

    /**
     * Get items per page
     *
     * @return integer
     */
    public function getItemsPerPage()
    {
        return $this->_it_perpage;
    }

    /**
     * Set the total number of items of all pages
     * by DEFAULT, this value is the table total number of primary key items
     *
     * @param  integer $total
     * @return object  $this
     */
    public function setTotal($total)
    {
        $this->_total = $total;
        return $this;
    }

    /**
     * Get total number of items
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->_total;
    }

    /**
     * Set query params
     *
     * @see    Peak_Model_Zendatable::_get() for params
     * 
     * @param  array  $p
     * @return object $this
     */
    public function setQueryParams($p)
    {
        $this->_params = $p;
        return $this;
    }

    /**
     * Calculate stuff for pagination
     */
    public function calculate()
    {
        //check if we need to count how many total number _db_object of items we have
        if(!isset($this->_total)) {

            $count_params           = $this->_params;
            $count_params['fields'] = array('count('.$this->_db_object->getPrimaryKey().')' => 'count');

            if(isset($count_params['limit'])) unset($count_params['limit']);

            $row                    = $this->_db_object->get($count_params);
            $this->_total           = $row['count'];
        }
        
        //calculate how many page
        if($this->_total > 0 && $this->_it_perpage > 0) {
            $this->_pcount = ceil(($this->_total / $this->_it_perpage));
        }
        elseif($this->_total == 0) $this->_pcount = 0;
        else $this->_pcount = 1;
        
        //generate pages
        $this->_pages = array();
        if($this->_pcount < 1) $this->_pages = array();
        else {
            for($i = 1;$i <= $this->_pcount;++$i) $this->_pages[$i] = $i;
        }
        
        $this->_is_calculated = true;
        
        return $this;
    }

    /**
     * Calculate all the pagination stuff and return a pages items result
     *
     * @params string $pn page number
     * @return mixed
     */
    public function getPage($pn)
    {
        if($this->_is_calculated == false) $this->calculate();
        
        //set current page
        if((isset($pn)) && (is_numeric($pn)) && ($pn <= $this->_pcount)) {
            $this->_curpage = $pn;
        }
        else $this->_curpage = 1;
        
        //prev/next page
        $this->_prevpage = ($this->_curpage > 1) ? $this->_curpage  - 1 : null;
        $this->_nextpage = (($this->_curpage + 1) <= $this->_pcount) ? $this->_curpage + 1 : null;
        
        //item start/end page
        $this->_it_start = (($this->_curpage - 1) * $this->_it_perpage);
        $this->_it_end = $this->_it_start + $this->_it_perpage;
        
        if(($this->_total != 0)) ++$this->_it_start;
        if($this->_it_end > $this->_total) $this->_it_end = $this->_total;
        
        return $this->_getData();
    }

    /**
     * Build the page db request and retreive result
     *
     * @param mixed
     */
    protected function _getData()
    {       
        $p = $this->_params;

        //set the limit
        $limit = $this->_it_start - 1;
        if($limit < 0) $limit = 0;
        $p['limit'] = $limit.','.$this->_it_perpage;

        return $this->_db_object->getAll($p);
    }

    /**
     * Get page list array
     *
     * @return array
     */
    public function getPages()
    {
        return $this->_pages;
    }
    
    /**
     * Set a pages ranges list array
     *
     * @param  integer
     * @return array
     */
    public function setPagesRange($range = null)
    {
        if(is_numeric($range) && ($range <= $this->_pcount) && is_array($this->_pages)) {
            
            $this->_pages_range = array();
 
            $diff = $range - $range - $range;
              
            for($i = $diff;$i <= $range;++$i) {
                if($i < 0) $index = $this->_curpage + $i;
                elseif($i == 0) $index = $this->_curpage;
                else $index = $this->_curpage + $i;
                
                if(!isset($this->_pages[$index])) continue;
                $this->_pages_range[$index] = $index;
            }
        }
        return $this;
    }

    /**
     * Get pages range list array
     *
     * @return array
     */
    public function getPagesRange()
    {
        return $this->_pages_range;
    }

    /**
     * Return usefull pagination variables
     *
     * @return object ArrayObject
     */
    public function getInfos()
    {
        $array = array('total'          => $this->_total,
                       'items_per_page' => $this->_it_perpage,
                       'item_start'     => $this->_it_start,
                       'item_end'       => $this->_it_end,
                       'curpage'        => $this->_curpage,
                       'nextpage'       => $this->_nextpage,
                       'prevpage'       => $this->_prevpage,
                       'pages'          => $this->_pages,
                       'pages_range'    => $this->_pages_range,
                       'pages_count'    => $this->_pcount,
                     );
        
        $arrobj = new ArrayObject($array);
        $arrobj->setFlags(ArrayObject::ARRAY_AS_PROPS);
        return $arrobj;
    }
    
    /**
     * Check if a page exists
     *
     * @param  integer $page_number
     * @return bool
     */
    public function isPage($page_number)
    {
        return (isset($this->_pages[$page_number])) ? true : false;
    }
}