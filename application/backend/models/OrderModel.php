<?php
class OrderModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->setTable('cart');
    }

    public function listItems($arrParam)
    {
        $query = "SELECT * FROM `$this->table` WHERE `id` <> ''";
        if(isset($arrParam['currentStatus']) && $arrParam['currentStatus'] != 'all') {
            $currentStatus = $arrParam['currentStatus'];
            $query .= " AND `status` = '$currentStatus'";
        }

        if(!empty(trim(@$arrParam['search_value']))) {
            $name = $arrParam['search_value'];
            $query .= " AND `name` LIKE '%$name%'";
        }

        $pagination         = $arrParam['pagination'];
        $totalItemsPerPage  = $pagination['totalItemsPerPage'];
        if($totalItemsPerPage > 0){
            $position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
            $query .= " LIMIT $position, $totalItemsPerPage";
        }
        $result = $this->listRecord($query);
        return $result;
    }

    public function changeStatus($arrParams, $option = null)
    {
        if ($option['task'] == 'change-status') {
            $id = $arrParams['id'];
            $statusValue = ($arrParams['status'] == 'active') ? 'inactive' : 'active';
            $query = "UPDATE `$this->table` SET `status` = '$statusValue' WHERE `id` = '$id'";
            $this->query($query);
        }

        $modified_by    = 'Minh Mẫn';
        $modified       = date('Y-m-d', time());
        $id             =    $this->createWhereUpdateSQL($arrParams['cid']);


        if ($option['task'] == 'multi-change-status-active') {
            $status         =  'inactive';
            $query          = "UPDATE `$this->table` SET `status` = $status, `modified` = '$modified', `modified_by` = '$modified_by'  WHERE `id` IN ('" . $id . ','."')";
        }
        if ($option['task'] == 'multi-change-status-inactive') {
            $status         =  'active';
            $query          = "UPDATE `$this->table` SET `status` = $status, `modified` = '$modified', `modified_by` = '$modified_by'  WHERE `id` = '" . $id . "'";
        }
        $this->query($query);
        $result =   [
            'id' => $id,
            'status' => $statusValue,
            'link' => URL::createLink('backend', 'group', 'ajaxStatus', ['id' => $id, 'status' => $statusValue])
        ];
        return $result;

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
            //$result['all'] = array_sum($result);
            $result = ['all' => array_sum($result)] + $result;
        }
        if ($option['type'] == 'group_acp') {
            $query = "SELECT `group_acp`,COUNT(*) as `count` FROM `$this->table`";
            if (isset($arrParams['group_acp'])) {
                $group_acp = $arrParams['group_acp'];
                $query .= "WHERE `group_acp` LIKE '%$group_acp%'";
            }
            $query .= "GROUP BY `group_acp`";
            $result = $this->listRecord($query);
            $result = array_combine(array_column($result, 'group_acp'), array_column($result, 'count'));
            
            $result = ['all' => array_sum($result)] + $result;
        }
        return $result;
    }

    public function deleteItem($arrParams, $option = null)
    {
        if ($option['task'] == 'delete') {
            $ids = $arrParams['delete_id'];
        }

        if ($option['task'] == 'multiDelete') {
            $ids = $this->createWhereDeleteSQL($arrParams['cid']);
        }
        echo $query = "DELETE FROM `$this->table` WHERE `id` IN ('$ids')";
        $this->query($query);
    }
}
