<?php
class GroupModel extends Model
{
    private $_columns = ['name', 'group_acp', 'status'];
    public function __construct()
    {
        parent::__construct();
        $this->setTable('group');
    }

    public function listItems($arrParam)
    {
        $query = "SELECT * FROM `$this->table` WHERE `id` > 0";
        if (isset($arrParam['currentStatus']) && $arrParam['currentStatus'] != 'all') {
            $currentStatus = $arrParam['currentStatus'];
            $query .= " AND `status` = '$currentStatus'";
        }

        if (!empty(trim(@$arrParam['search_value']))) {
            $name = $arrParam['search_value'];
            $query .= " AND `name` LIKE '%$name%'";
        }

        if (isset($arrParam['group_acp']) && $arrParam['group_acp'] != 'default') {
            $currentGroupACP = $arrParam['group_acp'];
            $query .= " AND `group_acp` = '$currentGroupACP'";
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

        return $result;
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

    public function saveItem($arrParams)
    {
        $data = array_intersect_key($arrParams['form'], array_flip($this->_columns));
        Session::init();
        if (isset($arrParams['id'])) {
            $id = $arrParams['id'];
            $data['modified_by']    = 'Minh Mẫn';
            $data['modified']       = date('Y-m-d', time());
            $condition = [['id', "$id"]];

            $this->update($data, $condition);

            Session::set('success', 'Cập nhật thành công');
        } else {
            $data['created_by']     = 'Minh Mẫn';
            $data['created']        = date('Y-m-d', time());
            $this->insert($data);
            Session::set('success', 'Thêm thành công');
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
}
