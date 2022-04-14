<?php
class BookModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->setTable('book');
    }

    public function listItems($arrParam, $options = null)
    {
        $query[] = "SELECT `b`.`id`,`b`.`name`,`b`.`description`,`b`.`short_description`, `b`.`price`,`b`.`sale_off`,`b`.`picture`,`b`.`status`,`b`.`ordering`,`b`.`category_id`,`c`.`name` as `category_name` FROM `$this->table` as `b`";
        
        $query[] = "LEFT JOIN `category` as `c` ON `b`.`category_id` = `c`.`id`";
        $query[] = "WHERE `b`.`id` > 0 ";
        if($options['task'] == 'list-items') {
            $query[] = "AND `b`.`status` = 'active' ";
            if(isset($arrParam['category_id'])) {
                $query[] = "AND `b`.`category_id` = '{$arrParam['category_id']}'";
            }
        }
        if(!empty(trim(@$arrParam['search_value']))) {
            $name = $arrParam['search_value'];
            $query [] = " AND `b`.`name` LIKE '%$name%'";
        }

        if(!empty($arrParam['sort'])) {
            $query[] = "ORDER BY `b`.`price` {$arrParam['sort']}";
        }else {
            $query[] = " ORDER BY `b`.`id` DESC";
        }
        $pagination         = $arrParam['pagination'];
        $totalItemsPerPage  = $pagination['totalItemsPerPage'];
        if($totalItemsPerPage > 0){
            $position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
            $query [] = " LIMIT $position, $totalItemsPerPage";
        }
 
        $query = implode(" ",$query);
        $result = $this->listRecord($query);
        return $result;
    }

    public function listBooks($arrParams, $option = null)
    {
        $query[] = "SELECT `b`.`id`,`b`.`name`,`b`.`description`,`b`.`short_description`, `b`.`price`,`b`.`sale_off`,`b`.`picture`,`b`.`status`,`b`.`ordering`,`b`.`category_id`,`c`.`name` as `category_name` FROM `$this->table` as `b`";;
        $query[] = "LEFT JOIN `category` as `c` ON `b`.`category_id` = `c`.`id`";
        $query[] = "WHERE `b`.`id` > 0 ";
        $ids = '';
        if ($option['task'] == 'list-items-in-category') {
            foreach ($arrParams as $param) {
                $ids .= "{$param['id']},";
            }
            $ids = substr_replace($ids,'',-1);
            $query[] = "AND `b`.`category_id` IN ($ids) ";
            $query[] = "LIMIT 0,6"; 
        }     
        if ($option['task'] == 'list-relate-items') {
            $query[] = "AND `b`.`category_id` = '{$arrParams['category_id']}'";
            $query[] = "LIMIT 0,6";
        }     
        if ($option['task'] == 'list-feature-items') {
            $query[] = "AND `b`.`special` = '1'";
            $query[] = "LIMIT 0,8";
        }    
        $query = implode("",$query);
        $result = $this->listRecord($query);
        return $result;
    }

    public function listCategories($arrParams)
    {
        $query[] = "SELECT * FROM `category` WHERE `id` > 0 AND `status` = 'active'";
        $query[] = "ORDER BY `ordering` asc";
        $query = implode(" ",$query);
        $result = $this->listRecord($query);
        return $result;
    }

    public function countItems($arrParams,$option = null) {
        if($option['type'] == 'book') {
            $query = "SELECT `id`,COUNT(*) as `count` FROM `$this->table` ";
            if (isset($arrParams['category_id'])) {
                $category_id = $arrParams['category_id'];
                $query .= "WHERE `category_id` LIKE '%$category_id%'";
            }
            $query .= "GROUP BY `id`";
            $result = $this->listRecord($query);
            $result = array_combine(array_column($result, 'id'), array_column($result, 'count'));
            $result = ['all' => array_sum($result)] + $result;
        }
        return $result;
    }

    public function infoItem($option = null)
    {
        $query = "SELECT * FROM `$this->table` ";
        $result = '';
        if (!empty($option)) {
            foreach ($option as $key => $value) {
                $query .= "WHERE `$key` = '$value'";
            }
            $result = $this->singleRecord($query);
        }

        return $result;
    }

    public function bookInfo($arrParam,$option = null)
    {
        if(isset($arrParam['id'])) {
            $query = "SELECT * FROM `$this->table` ";
            $id = $arrParam['id'];
            $query .= "WHERE `id` = '$id'";
            $result = $this->singleRecord($query);
        }
        return $result;
    }
}
