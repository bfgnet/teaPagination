<?php

error_reporting(E_ALL ^ E_NOTICE);

/**
 * teaPagination - Clase para paginación de datos SQL
 * 
 * @category  PAGINATION AND DATATABLE 
 * @package   teaPagination
 * @author    Basilio Fajardo <bfgnet@yahoo.es>
 * @copyright 2013 Basilio Fajardo Gálvez
 * @link      http://themeforest.net/user/bfgnet
 */
class teaPagination {

   /**
     * @var array of data to process
     * @access private
     */
    private $data = array();
   /**
     * @var string Sql statement to be processed
     * @access private
     */
    private $sql = null;
   /**
     * @var integer Maximum number of buttons to print 
     * @access private
     */
    private $PageNo;
   /**
     * @var string Design of the loop iteration.
     * @access private
     */
    private $design_item;
   /**
     * @var integer current page
     * @access private
     */
    public $currentPage;
   /**
     * @var integer total records 
     * @access private
     */
    public $TotalRecord;
   /**
     * @var integer Total number of pages 
     * @access private
     */
    public $TotalPage;
   /**
     * @var mixed Iteration callback function
     * @access private
     */
    private $callBack = null;
   /**
     * @var array Optional parameters to the callback function 
     * @access private
     */
    private $callArgs = array();
   /**
     * @var array List of default options
     * @access private
     */
    private $options = array(
        'buttons' => array(
            'btNext' => true,
            'btNextTitle' => 'Next &raquo;',
            'btPreviousTitle' => '&laquo; Previous',
            'btPrevious' => true,
            'btLastTitle' => 'Last',
            'btLast' => true,
            'btFirstTitle' => 'First',
            'btFirst' => true, 
            'class' => ''
        ),
        'nameVar' => 'page',
        'urlPage' => '', 
        'textNotFound' => 'Not found Record.',
        'maxButtons' => 5, 
        'itemsPage' => 5, 
        'beginLoop' => '',
        'ajax' => false,
        'endLoop' => '', 
        'beginTagItem' => '',
        'endTagItem' => '',
        'connect_db' => array(
            'user' => '',
            'database' => '',
            'password' => ''
        )
    );
   /**
     * @var object Instance to connect to the database
     * @access private
     */
    private static $PDOInstance = false;
    
    
    /**
    * The class constructor, set data and options
    *
    * @param mixed data
    * @param array options
    * @return void
    */
    function __construct($data, $options = array()) {
       if(is_array($data))$this->data = $data;
       else $this->sql = $data;
       if(!empty($options)){
            foreach($options as $key => $value){
                if(array_key_exists($key, $this->options)){
                    if(is_array($value)){
                        if(!empty($value)){
                            foreach($value as $subKey => $subValue){
                                if(array_key_exists($subKey, $value)){
                                    $this->options[$key][$subKey] = $subValue;
                                }
                            }
                        }
                    }else $this->options[$key] = $value;
                }
            }
        }
    }

    /**
    * instantiated for connecting to the database
    *
    * @return instance connection database
    */
    private function get_connection(){
        if (!self::$PDOInstance) {
            try {
                self::$PDOInstance = new PDO('mysql:host=localhost;dbname=' . $this->options['connect_db']['database'], $this->options['connect_db']['user'], $this->options['connect_db']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            } catch (PDOException $e) {
                die("PDO CONNECTION ERROR: " . $e->getMessage() . "<br/>");
            }
        }
        return self::$PDOInstance;
    }
    
    /**
    * The class constructor, set data and options
    *
    * @param mixed data
    * @param array options
    * @return void
    */
    public function loop($item_design, $callback=null, $callArgs=array()) {
        if(!is_null($callback))$this->callBack = $callback;
        if(!empty($callArgs))$this->callArgs = $callArgs;
        $this->design_item = utf8_encode($item_design);
        $page = 1;
        if(isset($_GET[$this->options['nameVar']]))
            $page = (isset($_GET[$this->options['nameVar']])) ? $_GET[$this->options['nameVar']] : 1;
        elseif(isset($_POST[$this->options['nameVar']]))
            $page = (isset($_POST[$this->options['nameVar']])) ? $_POST[$this->options['nameVar']] : 1;
        $this->PageNo = $page;
        $this->currentPage = $this->PageNo;
        $this->TotalRecord = (is_null($this->sql))? count($this->data) : $this->get_total_record();
        $this->TotalPage = ceil($this->TotalRecord / $this->options['itemsPage']);
    }

    /**
    * account all records sql statement
    *
    * @return integer count records
    */
    private function get_total_record() {
        $db = $this->get_connection();
        $fetch = $db->prepare($this->sql);
        $fetch->execute();
        return $fetch->rowCount();
    }

    /**
    * get data from the current page
    *
    * @param integer total records 
    * @param integer limit of records to show
    * @param integer number page
    * @return void
    */
    private function getPagerData($total_records, $limit, $page) {
        /** the number of pages * */
        $num_pages = ceil($total_records / $limit);
        if($num_pages < $page){
            $page = $num_pages;
            $this->PageNo = $page;
        }    
        //$page = max($page, 1);
        $page = min($page, $num_pages);
        /** calculate the offset * */
        $offset = ($page - 1) * $limit;
        /** a new instance of stdClass * */
        $ret = new stdClass;

        /** assign the variables to the return class object * */
        $ret->offset = $offset;
        $ret->limit = $limit;
        $ret->num_pages = $num_pages;
        $ret->page = $page;
        /** return the object * */
        return $ret;
    }

    /**
    * in this function is processed for output data
    *
    * @return string output list
    */
    private function write_loop() {
        $No = 0;
        $item_design = $this->design_item;
        $matches = array();

        if ($this->TotalRecord < 1) {
            $loop = '';
        } else {
            $loop = '';
            if (!$this->PageNo) {
                $this->PageNo = 1;
            }
            if(!is_null($this->sql)){
                $Pager = $this->getPagerData($this->TotalRecord, $this->options['itemsPage'], $this->PageNo);
                $offset = $Pager->offset;
                $limit = $Pager->limit;
                $this->TotalPage = $Pager->num_pages;
                $this->currentPage = $this->PageNo;

                $db = $this->get_connection();
                $fetch = $db->prepare($this->sql . ' LIMIT :limit OFFSET :offset');
                $fetch->bindParam(':limit', $limit, PDO::PARAM_INT);
                $fetch->bindParam(':offset', $offset, PDO::PARAM_INT);
                $fetch->execute();
                $res = $fetch->fetchAll(PDO::FETCH_ASSOC);
            }else{
                // start position in the $display_array
                // +1 is to account for total values.
                $start = ($this->PageNo - 1) * ($this->options['itemsPage']);
                $offset = $this->options['itemsPage'];
                $this->currentPage = $this->PageNo;
                $res = array_slice($this->data, $start, $offset);                
            }

            foreach ($res as $el) {
                if(!is_null($this->callBack)){
                    $loop .= $this->options['beginTagItem'].call_user_func($this->callBack, $el, $this->callArgs).$this->options['endTagItem'];
                }else{
                    if ($No == 0)
                        preg_match_all("/\{[a-zA-Z]*_*[a-zA-Z]*\}/", $item_design, $matches);
                    foreach ($matches as $val) {
                        $count = 0;
                        foreach ($val as $value) {
                            $key_field = substr($value, 1, -1);
                            $value_field = $el[$key_field];
                            if($count == 0)$item = $item_design;
                            $out_design = str_replace($value, $value_field, $item);
                            $item = $out_design;
                            $count++;
                        }
                    }
                    $loop .= $this->options['beginTagItem'].$out_design.$this->options['endTagItem'];
                    $No = $No + 1;
                }
            }
        }
        return $loop;
    }

    /**
    * output buttons
    *
    * @return string buttons
    */
    public function buttons() {
        $lastpage = $this->TotalPage;
        if ($lastpage >= 1) {
            $item_bt = (int) $this->options['maxButtons'];
            $frame = 0;
            if($item_bt != 0)
                $frame = ceil($this->PageNo / $item_bt);
            $init_pos = ($frame * $item_bt) - $item_bt + 1;
            $pagination = $this->renderButtons($init_pos);
            return $pagination;
        }
    }
    
    /**
    * The class constructor, set data and options
    *
    * @param integer set begin position page
    * @return string output buttons
    */
    private function renderButtons($beginPos) {
        $item_bt = $this->options['maxButtons'];
        $var = $this->options['nameVar'];
        $urlPage = $this->options['urlPage'];
        $prev = $this->PageNo - 1;
        $next = $this->PageNo + 1;
        $lastpage = $this->TotalPage;
        $pagination = "<ul class='_buttons " . $this->options['buttons']['class'] . "'>";
        //Imprimimos los botones de la izquierda << | < | ..
        //Si esta por encima de la página principal los botones estan activos
        if ($this->PageNo > 1) {
            if ($this->options['buttons']['btFirst']){
                $url = (empty($urlPage))? '?'.$var.'=1' : sprintf($urlPage, 1);
                $page = ($this->options['ajax'])? 'javascript:void(0)' : $url;
                $pagination.= "<li><a class='_first' href='".$page."' rel='_1'>".$this->options['buttons']['btFirstTitle']."</a></li>";
            }if ($this->options['buttons']['btPrevious']){
                $url = (empty($urlPage))? '?'.$var.'='.$prev : sprintf($urlPage, $prev);
                $page = ($this->options['ajax'])? 'javascript:void(0)' : $url;
                $pagination.= "<li><a class='_prev' href='".$page."' rel='_$prev'>".$this->options['buttons']['btPreviousTitle']."</a></li>";
            }    
        }else { //Si no deben desactivarse
            if ($this->options['buttons']['btFirst'])
                $pagination.= "<li class='disabled'><span>".$this->options['buttons']['btFirstTitle']."</span></li>";
            if ($this->options['buttons']['btPrevious'])
                $pagination.= "<li class='disabled'><span>".$this->options['buttons']['btPreviousTitle']."</span></li>";
        }
        //------------imprimir botones-------------	
        //Si existen menos páginas de las imprimibles
        if ($item_bt > $lastpage) {
            for ($i = 1; $i <= $lastpage; $i++) {
                if ($this->PageNo == $i)
                    $pagination.= "<li class='active'><span>$i</span></li>";
                else{
                    $url = ($urlPage == '')? '?'.$var.'='.$i : sprintf($urlPage, $i);
                    $page = ($this->options['ajax'])? 'javascript:void(0)' : $url;
                    $pagination.= "<li><a class='_page' href='".$page."' rel='_$i'>$i</a></li>";
                }    
            }
        }else { //Imprimimos todas las paginas
            $item = 1;
            for ($i = $beginPos; $item <= $item_bt; $i++) {
                if ($i <= $this->TotalPage) {
                    if ($this->PageNo == $i)
                        $pagination.= "<li class='active'><span>$i</span></li>";
                    else{
                        $url = (empty($urlPage))? '?'.$var.'='.$i : sprintf($urlPage, $i);
                        $page = ($this->options['ajax'])? 'javascript:void(0)' : $url;
                        $pagination.= "<li><a class='_page' href='".$page."' rel='$i'>$i</a></li>";
                    }    
                }
                $item++;
            }
        }
        if ($this->PageNo < $lastpage) {
            if ($this->options['buttons']['btNext']){
                $url = (empty($urlPage))? '?'.$var.'='.$next : sprintf($urlPage, $next);
                $page = ($this->options['ajax'])? 'javascript:void(0)' : $url;
                $pagination .= "<li><a class='_next' href='".$page."' rel='_$next'>".$this->options['buttons']['btNextTitle']."</a></li>";
            }if ($this->options['buttons']['btLast']){
                $url = (empty($urlPage))? '?'.$var.'='.$this->TotalPage : sprintf($urlPage, $this->TotalPage);
                $page = ($this->options['ajax'])? 'javascript:void(0)' : $url;
                $pagination .= "<li><a class='_last' href='".$page."' rel='_$this->TotalPage'>".$this->options['buttons']['btLastTitle']."</a></li>";
            }    
        }else {
            if ($this->options['buttons']['btNext'])
                $pagination .= "<li class='disabled'><span>".$this->options['buttons']['btNextTitle']."</span></li>";
            if ($this->options['buttons']['btLast'])
                $pagination .= "<li class='disabled'><span>".$this->options['buttons']['btLastTitle']."</span></li>";
        }
        $pagination.= "</ul>";
        return $pagination;
    }
    
    /**
    * show list
    *
    * @return string
    */
    public function render() {
        if ($this->TotalRecord >= 1)return $this->options['beginLoop']. $this->write_loop(). $this->options['endLoop'];
        else return $this->options['textNotFound'];
    }

}

