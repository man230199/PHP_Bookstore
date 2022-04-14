<?php
class SliderModel extends Model
{
    private $_columns = ['name', 'picture', 'status','ordering','created','created_by','modified','modified_by'];
    public function __construct()
    {
        parent::__construct();
        $this->setTable('slider');
    }

    public function listItems($arrParam)
    {
        $query = "SELECT * FROM `$this->table` WHERE `id` > 0";
        if(isset($arrParam['currentStatus']) && $arrParam['currentStatus'] != 'all') {
            $currentStatus = $arrParam['currentStatus'];
            $query .= " AND `status` = '$currentStatus'";
        }

        if(!empty(trim(@$arrParam['search_value']))) {
            $name = $arrParam['search_value'];
            $query .= " AND `name` LIKE '%$name%'";
        }

        $query .= " ORDER BY `id` DESC";
        $pagination         = $arrParam['pagination'];
        $totalItemsPerPage  = $pagination['totalItemsPerPage'];
        if($totalItemsPerPage > 0){
            $position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
            $query .= " LIMIT $position, $totalItemsPerPage";
        }
        $result = $this->listRecord($query);
        return $result;
    }

    public function changeOrdering($arrParams, $option = null)
    {
        if ($option['task'] == 'change-ordering') {
            $id = $arrParams['id'];
            $orderingValue = $arrParams['ordering'];
            $query = "UPDATE `$this->table` SET `ordering` = '$orderingValue' WHERE `id` = '$id'";
            $this->query($query);
        }
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
    
    public function saveItem($arrParams)
    {
        require_once LIBRARY_PATH . 'Upload.php';
        $uploadObject = new Upload();
       
        Session::init();
        if (isset($arrParams['id'])) {
            $id = $arrParams['id'];
            $data['modified_by']    = 'Minh Mẫn';
            $data['modified']       = date('Y-m-d', time());
            $condition = [['id', "$id"]];
            if($arrParams['form']['picture']['name']==null){
				unset($arrParams['form']['picture']);
			}else{
				$uploadObject->removeFile($this->table, $arrParams['form']['picture_hidden']);
				
				$arrParams['form']['picture']	= $uploadObject->uploadFile($arrParams['form']['picture'], $this->table);
			}
            $data = array_intersect_key($arrParams['form'], array_flip($this->_columns));
            $this->update($data, $condition);
            
            Session::set('success','Cập nhật thành công');
        } else {
            $data['created_by']     = 'Minh Mẫn';
            $data['created']        = date('Y-m-d', time());
            $arrParams['form']['picture']	= $uploadObject->uploadFile($arrParams['form']['picture'], $this->table);
            $data = array_intersect_key($arrParams['form'], array_flip($this->_columns));
            $this->insert($data);
            Session::set('success','Thêm thành công');
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
        require_once LIBRARY_PATH . 'Upload.php';
        $uploadObject = new Upload();
        if ($option['task'] == 'delete') {
            $ids = $arrParams['delete_id'];
            $query = "SELECT `id`,`picture` FROM `$this->table` WHERE `id` IN ($ids)";
            $result = $this->listRecord($query);
            foreach($result as $item) {
                $uploadObject->removeFile($this->table, $item['picture']);
            }
        }

        if ($option['task'] == 'multiDelete') {
            $ids = $this->createWhereDeleteSQL($arrParams['cid']);
            $query = "SELECT `id`,`picture` FROM `$this->table` WHERE `id` IN ($ids)";
            $result = $this->listRecord($query);
            foreach($result as $item) {
                $uploadObject->removeFile($this->table, $item['picture']);
            }
        }
        $query = "DELETE FROM `$this->table` WHERE `id` IN ($ids)";
        $this->query($query);
    }
}
