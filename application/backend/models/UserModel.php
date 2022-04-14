<?php
class UserModel extends Model
{
    private $_columns = ['username', 'password', 'group_id', 'status', 'email'];
    public function __construct()
    {
        parent::__construct();
        $this->setTable('users');
    }

    public function listItems($arrParam, $option = null)
    {
        $query = "SELECT `u`.`id`,`u`.`email`,`u`.`username`,`g`.`name` as `group_name`,`u`.`password`,`u`.`group_id`,`u`.`status`,`u`.`created`,`u`.`created_by`,`u`.`modified`,`u`.`modified_by`";
        $query .= " FROM `$this->table` as `u`";
        $query .= " LEFT JOIN `group` as `g` ON `g`.`id` = `u`.`group_id`";
        $query .= "WHERE `u`.`id` > 0";
        if (isset($arrParam['currentStatus']) && $arrParam['currentStatus'] != 'all') {
            $currentStatus = $arrParam['currentStatus'];
            $query .= " AND `u`.`status` = '$currentStatus'";
        }

        if (!empty(trim(@$arrParam['search_value']))) {
            $username = $arrParam['search_value'];
            $query .= " AND `u`.`username` LIKE '%$username%'";
        }

        $pagination         = $arrParam['pagination'];
        $totalItemsPerPage  = $pagination['totalItemsPerPage'];
        if ($totalItemsPerPage > 0) {
            $position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
            $query .= " LIMIT $position, $totalItemsPerPage";
        }

        $result = $this->listRecord($query);
        return $result;
    }

    public function groupValuesInSelectBox($arrParams, $option = null) {
        $query = "SELECT `id`, `name` FROM `group`";
        $result = $this->itemsInSelectBox($query);
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

        if ($option['task'] == 'multi-change-status-active') {
            $modified_by    = 'Minh Mẫn';
            $modified       = date('Y-m-d', time());
            $id             =    $this->createWhereUpdateSQL($arrParams['cid']);
    
            $status         =  'inactive';
            $query          = "UPDATE `$this->table` SET `status` = $status, `modified` = '$modified', `modified_by` = '$modified_by'  WHERE `id` IN ('" . $id . ',' . "')";
        }
        if ($option['task'] == 'multi-change-status-inactive') {
            $modified_by    = 'Minh Mẫn';
            $modified       = date('Y-m-d', time());
            $id             =    $this->createWhereUpdateSQL($arrParams['cid']);
    
            $status         =  'active';
            $query          = "UPDATE `$this->table` SET `status` = $status, `modified` = '$modified', `modified_by` = '$modified_by'  WHERE `id` = '" . $id . "'";
        }
        $this->query($query);
        $result =   [
            'id' => $id,
            'status' => $statusValue,
            'link' => URL::createLink('backend', 'group', 'ajaxStatus', ['id' => $id, 'status' => $statusValue])
        ];
        Session::set('success', 'Cập nhật trạng thái thành công');

        return $result;
    }

    public function changeGroup($arrParams)
    {
        $id = $arrParams['id'];
        $group_id = $arrParams['group_id'];
        $query = "UPDATE `$this->table` SET `group_id` = '$group_id'";
        $query .= " WHERE `id` = '$id'";
        $this->query($query);
    }

    public function changeGroupACP($arrParams)
    {
        $id = $arrParams['id'];
        $groupACPValue = ($arrParams['group_acp'] == 1) ? 0 : 1;
        $query = "UPDATE `$this->table` SET `group_acp` = '$groupACPValue' WHERE `id` = '$id'";
        $this->query($query);
        $result =   [
            'id' => $id,
            'group_acp' => $groupACPValue,
            'link' => URL::createLink('backend', 'group', 'index', ['id' => $id, 'status' => $groupACPValue])
        ];
        return $result;
    }

    public function countItems($arrParams, $option = null)
    {
        if ($option['type'] == 'status') {
            $query = "SELECT `status`,COUNT(*) as `count` FROM `$this->table`";
            if (isset($arrParams['search_value'])) {
                $name = $arrParams['search_value'];
                $query .= "WHERE `username` LIKE '%$name%'";
            }
            $query .= "GROUP BY `status`";
            $result = $this->listRecord($query);
            $result = array_combine(array_column($result, 'status'), array_column($result, 'count'));
            //$result['all'] = array_sum($result);
            $result = ['all' => array_sum($result)] + $result;
        }

        return $result;
    }

    public function saveItem($arrParams, $option = null)
    {
        $data = array_intersect_key($arrParams['form'], array_flip($this->_columns));
        Session::init();

        if (isset($arrParams['id'])) {
            $id = $arrParams['id'];
            $data['modified_by']    = 'Minh Mẫn';
            $data['modified']       = date('Y-m-d');
            if ($option['task'] == 'change-password') {
                $data['password'] = md5($arrParams['form']['password']);
            }
            $condition = [['id', "$id"]];

            $this->update($data, $condition);

            Session::set('success', 'Cập nhật thành công');
        } else {
            $data['created_by']     = 'Minh Mẫn';
            $data['created']        = date('Y-m-d');

            $this->insert($data);
            Session::set('success', 'Thêm thành công');
        }
    }

    public function infoItem($option = null)
    {
        $query = "SELECT * FROM `$this->table` WHERE `id` > 0 ";
        $result = '';
        if (!empty($option)) {
            foreach ($option as $key => $value) {
               $query .= "AND `$key` = '$value'";
            }
            $result = $this->singleRecord($query);
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
        $query = "DELETE FROM `$this->table` WHERE `id` IN ($ids)";
        $this->query($query);
    }

    public function login($arrParam)
    {
        $email = $arrParam['email'];
        $password = md5($arrParam['password']);
        $query = "SELECT `u`.`username`,`u`.`email`, `u`.`password`, `u`.`group_id`,`g`.`name` as `group_name` FROM `$this->table` as `u` ";
        $query .= "LEFT JOIN `group` as `g` on `g`.`id` = `u`.`group_id` ";
        $query .= "WHERE `email` = '$email' AND `password` = '$password' AND `g`.`name` = 'Admin'";
        $result = $this->singleRecord($query);
        return $result;
    }
}
