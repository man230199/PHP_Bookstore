<?php
class BookModel extends Model
{
    private $_columns = ['name', 'description', 'short_description', 'price', 'special', 'sale_off', 'picture', 'status', 'ordering', 'category_id'];
    public function __construct()
    {
        parent::__construct();
        $this->setTable('book');
    }

    public function listItems($arrParam)
    {
        $query = "SELECT `b`.`id`,`b`.`name`,`b`.`description`,`b`.`short_description`,`b`.`price`,`b`.`special`,`b`.`sale_off`, `b`.`picture`, `b`.`status`,`b`.`ordering`,`b`.`created`,`b`.`created_by`,`b`.`modified`,`b`.`modified_by`,`b`.`category_id`,`c`.`name` as `category_name` ";
        $query .= "FROM `$this->table` as `b` ";
        $query .= "LEFT JOIN `category` as `c` ON `c`.`id` = `b`.`category_id` ";
        $query .= "WHERE `b`.`id` > 0 ";
        if (isset($arrParam['currentStatus']) && $arrParam['currentStatus'] != 'all') {
            $currentStatus = $arrParam['currentStatus'];
            $query .= " AND `b`.`status` = '$currentStatus'";
        }

        if (!empty(trim(@$arrParam['search_value']))) {
            $name = $arrParam['search_value'];
            $query .= " AND `b`.`name` LIKE '%$name%'";
        }

        if (isset($arrParam['special']) && $arrParam['special'] != 'default') {
            $currentSpecial = $arrParam['special'];
            $query .= " AND `b`.`special` = '$currentSpecial'";
        }

        if (isset($arrParam['filter_category_id']) && $arrParam['filter_category_id'] != 'default') {
            $currentCategoryID = $arrParam['filter_category_id'];
            $query .= " AND `b`.`category_id` = '$currentCategoryID'";
        }


        $query .= " ORDER BY `b`.`id` DESC";
        $pagination         = $arrParam['pagination'];
        $totalItemsPerPage  = $pagination['totalItemsPerPage'];
        if ($totalItemsPerPage > 0) {
            $position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
            $query .= " LIMIT $position, $totalItemsPerPage";
        }
        $result = $this->listRecord($query);
        return $result;
    }

    public function categoryValuesInSelectBox($arrParams, $option = null)
    {
        $query = "SELECT `id`, `name` FROM `category`";
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

        $modified_by    = 'Minh Mẫn';
        $modified       = date('Y-m-d', time());



        if ($option['task'] == 'multi-change-status-active') {
            $id             =    $this->createWhereUpdateSQL($arrParams['cid']);
            $status         =  'inactive';
            $query          = "UPDATE `$this->table` SET `status` = $status, `modified` = '$modified', `modified_by` = '$modified_by'  WHERE `id` IN ('" . $id . ',' . "')";
        }
        if ($option['task'] == 'multi-change-status-inactive') {
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

    public function changeCategory($arrParams)
    {
        $id = $arrParams['id'];
        $category_id = $arrParams['category_id'];
        $query = "UPDATE `$this->table` SET `category_id` = '$category_id'";
        $query .= " WHERE `id` = '$id'";
        $this->query($query);
    }

    public function listCategories($arrParams)
    {
        $query = "SELECT `id`,`name` FROM `category`";
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

    public function changeSpecial($arrParams, $option = null)
    {
        if ($option['task'] == 'change-special') {
            $id = $arrParams['id'];
            $specialValue = ($arrParams['special'] == 1) ? 0 : 1;
            $query = "UPDATE `$this->table` SET `special` = '$specialValue' WHERE `id` = '$id'";
            $this->query($query);
        }

        $modified_by    = 'Minh Mẫn';
        $modified       = date('Y-m-d', time());

        if ($option['task'] == 'multi-change-special-active') {
            $id             =    $this->createWhereUpdateSQL($arrParams['cid']);
            $special         =  'inactive';
            $query          = "UPDATE `$this->table` SET `special` = $special, `modified` = '$modified', `modified_by` = '$modified_by'  WHERE `id` IN ('" . $id . ',' . "')";
        }
        if ($option['task'] == 'multi-change-special-inactive') {
            $id             =    $this->createWhereUpdateSQL($arrParams['cid']);
            $special         =  'active';
            $query          = "UPDATE `$this->table` SET `special` = $special, `modified` = '$modified', `modified_by` = '$modified_by'  WHERE `id` = '" . $id . "'";
        }
        $this->query($query);
        $result =   [
            'id' => $id,
            'special' => $specialValue,
            'link' => URL::createLink('backend', 'book', 'ajaxStatus', ['id' => $id, 'status' => $specialValue])
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
            $result = ['all' => array_sum($result)] + $result;
            return $result;
        }
        if ($option['type'] == 'special') {
            $query = "SELECT `special`,COUNT(*) as `count` FROM `$this->table`";
            if (isset($arrParams['search_value'])) {
                $name = $arrParams['search_value'];
                $query .= "WHERE `name` LIKE '%$name%'";
            }
            $query .= "GROUP BY `special`";
            $result = $this->listRecord($query);
            $result = array_combine(array_column($result, 'special'), array_column($result, 'count'));
            $result = ['all' => array_sum($result)] + $result;
            return $result;
        }
        if ($option['type'] == 'category') {
            $query = "SELECT `category_id`,COUNT(*) as `count` FROM `$this->table`";
            if (isset($arrParams['search_value'])) {
                $name = $arrParams['search_value'];
                $query .= "WHERE `name` LIKE '%$name%'";
            }
            $query .= "GROUP BY `category_id`";
            $result = $this->listRecord($query);
            $result = array_combine(array_column($result, 'category_id'), array_column($result, 'count'));
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
            if ($arrParams['form']['picture']['name'] == null) {
                unset($arrParams['form']['picture']);
            } else {
                $uploadObject->removeFile($this->table, $arrParams['form']['picture_hidden']);

                $arrParams['form']['picture']    = $uploadObject->uploadFile($arrParams['form']['picture'], $this->table);
            }
            $data = array_intersect_key($arrParams['form'], array_flip($this->_columns));
            $this->update($data, $condition);

            Session::set('success', 'Cập nhật thành công');
        } else {
            $data['created_by']     = 'Minh Mẫn';
            $data['created']        = date('Y-m-d', time());
            $arrParams['form']['picture']    = $uploadObject->uploadFile($arrParams['form']['picture'], $this->table);
            $data = array_intersect_key($arrParams['form'], array_flip($this->_columns));
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
        require_once LIBRARY_PATH . 'Upload.php';
        $uploadObject = new Upload();
        if ($option['task'] == 'delete') {
            $ids = $arrParams['delete_id'];
            $query = "SELECT `id`,`picture` FROM `$this->table` WHERE `id` IN ($ids)";
            $result = $this->listRecord($query);
            foreach ($result as $item) {
                $uploadObject->removeFile($this->table, $item['picture']);
            }
        }

        if ($option['task'] == 'multiDelete') {
            $ids = $this->createWhereDeleteSQL($arrParams['cid']);
            $query = "SELECT `id`,`picture` FROM `$this->table` WHERE `id` IN ($ids)";
            $result = $this->listRecord($query);
            foreach ($result as $item) {
                $uploadObject->removeFile($this->table, $item['picture']);
            }
        }
        $query = "DELETE FROM `$this->table` WHERE `id` IN ($ids)";
        $this->query($query);
    }
}
