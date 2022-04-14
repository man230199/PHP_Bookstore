<?php
class CategoryModel extends Model
{
    private $_columns = ['name', 'picture', 'status','ordering'];
    public function __construct()
    {
        parent::__construct();
        $this->setTable('category');
    }

    public function listItems()
    {
        $query = "SELECT * FROM `$this->table` WHERE `id` > 0 AND `status` = 'active'";
        
        $result = $this->listRecord($query);
        return $result;
    }

    public function countItems($arrParams,$option = null) {
        if ($option['type'] == 'status') {
            $query = "SELECT `status`,COUNT(*) as `count` FROM `$this->table`";
            if (isset($arrParams['search_value'])) {
                $name = $arrParams['search_value'];
                $query .= "WHERE `name` LIKE '%$name%'";
            }
            $query .= "GROUP BY `status`";
            $result = $this->listRecord($query);
            $result = array_combine(array_column($result, 'status'), array_column($result, 'count'));
            $result = ['all' => array_sum($result)] + $result;
            return $result;
        }
        
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
}
