<?php
/**
* this class extends teaPagination 
* @see teaPagination
*/
require_once('setting/setting.php');
class PaginateCustomize extends teaPagination{
    
    public $sql;
    public $defaultOptions = array(
        'beginLoop' => '<div class="row">',
        'endLoop' => '</div>',
        'maxButtons' => 5,
        'itemsPage' => 9,
        'ajax' => true,
        'buttons' => array('class' => 'pagination','btFirst' => false,'btLast' => false),
        'connect_db' => array('user' => USER, 'database' => DATABASE, 'password' => PASSWORD)
    );
    private $extendsOptions = array(
        'sort' => 'short_name',
        'order' => 'ASC',
        'typeList' => 'list',
        'search' => null,
        'fieldsSearch' => array()
    );
    
    /**
    * The class constructor
    */
    function __construct($sql, $options, $type) {
        if (session_id() == '') {
            session_start();
        }
        if($_POST['init'])unset($_SESSION['paginate']);
        foreach($options as $key => $value){
            if(array_key_exists($key, $this->extendsOptions)){
                if(!is_null($value))$_SESSION['paginate'][$key] = $value; 
            }    
        }
        parent::__construct($this->createSelect($_SESSION, $sql), $this->defaultOptions);
        $type = (isset($_SESSION['paginate']['typeList'])) ? $_SESSION['paginate']['typeList'] : $this->extendsOptions['typeList'];
        if($type == 'list')$this->loop('', array($this,'create_list_data'));
        elseif($type == 'grid') $this->loop('', array($this,'create_grid_data'));
        else $this->loop('', array($this,'create_list_data'));
    }
    
    /**
    * This function return sql
    *
    * @param mixed session data
    * @param string sql query
    * @return final sql
    */
    private function createSelect($session, $sql){
        $sort = (isset($session['paginate']['sort'])) ? ' ORDER BY '.$session['paginate']['sort'] : ' ORDER BY '.$this->extendsOptions['sort'];
        $order = (isset($session['paginate']['order'])) ? $session['paginate']['order'] : $this->extendsOptions['order'];
        if(isset($session['paginate']['search'])){
          if(!empty($session['paginate']['fieldsSearch'])){
              $search = ' ';
              foreach($session['paginate']['fieldsSearch'] as $value){
                  if(end($session['paginate']['fieldsSearch']) === $value)
                      $search .= $value.' LIKE "%'.$session['paginate']['search'].'%"';
                  else
                      $search .= $value.' LIKE "%'.$session['paginate']['search'].'%" OR ';
              }
          }else $search = '';   
        }else $search = '';
        $sql .= $search.$sort.' '.$order;
        return $sql;
    }
    
    /**
    * This function print each iteration
    *
    * @param mixed data associated with the sql query
    * @return each iteration in the list
    */
    protected function create_list_data($data) {
        return '<pre><a href="javascript:void(0)" onClick="alert(\'Country: ' . $data['short_name'] . '\')">' . $data['short_name'] . '</a> - Country code: '.$data['iso2'].'<br/> <small>' . $data['long_name'] . '</small></pre>';
    }

    /**
    * This function print each iteration
    *
    * @param mixed data associated with the sql query
    * @return each iteration in the grid
    */
    protected function create_grid_data($data) {
        return '<div class="col-xs-4"><div class="well" style="min-height:120px;"><a href="javascript:void(0)" onClick="alert(\'Country: ' . $data['short_name'] . '\')">' . $data['short_name'] . '</a> - Country code: '.$data['iso2'].'<br/> <small>' . $data['long_name'] . '</small></div></div>';
    }

    /**
    * This function show total list
    *
    * @return list
    */
    function ShowList(){
        return $this->render();
    }
    
    /**
    * This function show buttons
    *
    * @return buttons
    */
     function ShowButtons(){
        return $this->buttons();
    }
}

?>
