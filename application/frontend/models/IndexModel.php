<?php
class IndexModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->setTable('book');
    }

    public function listItems($arrParam, $option = null)
    {
        $query = "SELECT `email`,`username`,`password`,`fullname`";
        $query .= " FROM `$this->table`";
        $query .= " WHERE `id` > 0";

        $result = $this->listRecord($query);
        return $result;
    }

    public function listCategories($arrParams, $options = null)
    {
        $query = "SELECT * FROM `category` WHERE `status` = 'active' ";
        if ($options['task'] == 'feature-item') {
            $query .= "ORDER BY `ordering` ASC ";
            $query .= "LIMIT 0,3 ";
        }
        $result = $this->listRecord($query);
        return $result;
    }

    public function register($arrParams, $option = null)
    {
        $data = array_intersect_key($arrParams['form'], array_flip($this->_columns));
        Session::init();
        $data['created_by']     = $data['fullname'];
        $data['created']        = date('Y-m-d');
        $data['group_id']       = '3';
        $data['status']         = 'active';
        $data['password']       = md5($data['password']);

        $this->insert($data);
        Session::set('message', 'Đăng kí tài khoản thành công');
    }

    public function infoItem($arrParam, $option = null)
    {
        $result = '';
        if (isset($arrParam['id'])) {
            $query[] = "SELECT `b`.`id`,`b`.`name`,`b`.`description`,`b`.`short_description`, `b`.`price`,`b`.`sale_off`,`b`.`picture`,`b`.`status`,`b`.`ordering`,`b`.`category_id`,`c`.`name` as `category_name`";
            $query[] = "FROM `$this->table` as `b`,`category` as `c`";
            $query[] = "WHERE `b`.`category_id` = `c`.`id`";
            $id = $arrParam['id'];
            $query[] = "AND `b`.`id` = '$id'";
            $query = implode(" ", $query);
            $result = $this->singleRecord($query);
        }
        return $result;
    }

    public function login($arrParam)
    {
        if (isset($arrParam['email']) && isset($arrParam['password'])) {
            $email = $arrParam['email'];
            $password = md5($arrParam['password']);
            $query = "SELECT `u`.`username`,`u`.`email`, `u`.`password`,`u`.`group_id`,`g`.`name` as `group_name` ";
            $query .= "FROM `$this->table` as `u` ";
            $query .= "LEFT JOIN `group` as `g` on `g`.`id` = `u`.`group_id`";
            $query .= "WHERE `u`.`email` = '$email' AND `u`.`password` = '$password'";
            $result = $this->singleRecord($query);
            return $result;
        }
    }

    public function listSpecialBooks($arrParams, $option = null)
    {
        $query[] = "SELECT `b`.`id`,`b`.`name`,`b`.`description`,`b`.`short_description`, `b`.`price`,`b`.`sale_off`,`b`.`picture`,`b`.`status`,`b`.`ordering`,`b`.`category_id`,`c`.`name` as `category_name`";
        $query[] = "FROM `$this->table` as `b`,`category` as `c`";
        $query[] = "WHERE `b`.`category_id` = `c`.`id`";
        $query[] = "AND `b`.`special` = '1'";
        $query = implode(" ", $query);
        $result = $this->listRecord($query);
        return $result;
    }


    public function listSliders($arrParams, $option = null)
    {
        $query[] = "SELECT * FROM `slider` WHERE `id` > '0' ";
        if ($option['task'] == 'home-list-items') {
            $query[] = "AND `status` = 'active'";
            $query[] = "ORDER BY `ordering`";
            $query[] = "LIMIT 0,3";
            $query = implode("", $query);
        }
        $result = $this->listRecord($query);
        return $result;
    }

    public function listBooks($arrParams, $option = null)
    {
        $query[] = "SELECT `b`.`id`,`b`.`name`,`b`.`description`,`b`.`short_description`, `b`.`price`,`b`.`sale_off`,`b`.`picture`,`b`.`status`,`b`.`ordering`,`b`.`category_id`,`c`.`name` as `category_name`";
        $query[] = "FROM `$this->table` as `b`,`category` as `c`";
        $query[] = "WHERE `b`.`category_id` = `c`.`id`";
        $query[] = "AND `b`.`id` > '0'";

        $ids = '';
        if ($option['task'] == 'list-items-in-category') {
            foreach ($arrParams as $param) {
                $ids .= "{$param['id']},";
            }
            $ids = substr_replace($ids, '', -1);
            $query[] = "AND `b`.`category_id` IN ($ids) ";
        }

        $query = implode("", $query);
        $result = $this->listRecord($query);
        return $result;
    }


    public function listFeatureCategories($arrParams)
    {
        $query = "SELECT * FROM `category` WHERE `status` = 'active'";
        $result = $this->listRecord($query);
        return $result;
    }
}
