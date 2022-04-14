<?php
class UserModel extends Model
{
    private $_columns = ['username', 'password', 'fullname', 'email'];
    private $_columnsCart = [
        'id',
        'username',
        'email',
        'fullname',
        'password',
        'created',
        'created_by',
        'modified',
        'modified_by',
        'register_date',
        'register_ip',
        'status',
        'ordering',
        'group_id'
    ];
    private $_userInfo;
    public function __construct()
    {
        parent::__construct();
        $this->setTable('users');
        $userObj             = Session::get('user_info');
        $this->_userInfo     = @$userObj['info'];
    }

    public function listItems($arrParam, $option = null)
    {
        $result = [];
        if ($option['task'] == 'books-in-cart') {
            $cart    = Session::get('cart');
            if (!empty($cart)) {
                
                $ids    = "(";
                foreach ($cart['quantity'] as $key => $value) $ids .= "'$key', ";
                $ids    .= " '0')";
                $query[]    = "SELECT `id`, `name`, `picture`";
                $query[]    = "FROM `" . TBL_BOOK . "`";
                $query[]    = "WHERE `status`  = 'active' AND `id` IN $ids";
                $query[]    = "ORDER BY `ordering` ASC";

                $query        = implode(" ", $query);
                $result        = $this->listRecord($query);

                foreach ($result as $key => $value) {
                    $result[$key]['quantity']    = $cart['quantity'][$value['id']];
                    $result[$key]['totalprice']    = $cart['price'][$value['id']];
                    $result[$key]['price']        = $result[$key]['totalprice'] / $result[$key]['quantity'];
                }
            }
        }

        if ($option['task'] == 'history-cart') {
            $username       = $this->_userInfo['username'];
            $query[]    = "SELECT *";
            $query[]    = "FROM `" . TBL_CART . "`";
            $query[]    = "WHERE `username` = '$username' ";
            $query[]    = "ORDER BY `date` DESC";

            $query        = implode(" ", $query);
            $result        = $this->listRecord($query);
        }

        if ($option['task'] == 'list-users') {
            $query = "SELECT `email`,`username`,`password`,`fullname`";
            $query .= " FROM `$this->table`";
            $query .= " WHERE `id` > 0";

            $result = $this->listRecord($query);
        }

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

    public function infoItem($option = null)
    {
        $query = "SELECT * FROM `$this->table` ";
        $query = "WHERE `id` > 0 ";
        $result = '';
        if (!empty($option)) {
            foreach ($option as $key => $value) {
                $query .= "AND `$key` = '$value'";
            }
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

    public function saveItem($arrParam, $option = null)
    {

        if ($option['task'] == 'submit-cart') {
            $id             = $this->randomString(7);
            $username       = $this->_userInfo['username'];
            $books          = json_encode($arrParam['form']['book_id']);
            $prices         = json_encode($arrParam['form']['price']);
            $quantities     = json_encode($arrParam['form']['quantity']);
            $names          = json_encode($arrParam['form']['name'],JSON_UNESCAPED_UNICODE);
            $pictures       = json_encode($arrParam['form']['picture']);
            $date           = date('Y-m-d H:i:s', time());

            $query    = "INSERT INTO `" . TBL_CART . "`(`id`, `username`, `books`, `prices`, `quantities`, `names`, `pictures`, `status`, `date`)
					VALUES ('$id', '$username', '$books', '$prices', '$quantities', '$names', '$pictures', 'inactive', '$date')";
            $this->query($query);
            Session::delete('cart');
            Session::set('message', 'Đã đặt hàng thành công! Cám ơn bạn đã tin tưởng shop');
        }
    }

    private function randomString($length = 5)
    {

        $arrCharacter = array_merge(range('a', 'z'), range(0, 9), range('A', 'Z'));
        $arrCharacter = implode('', $arrCharacter);
        $arrCharacter = str_shuffle($arrCharacter);

        $result        = substr($arrCharacter, 0, $length);
        return $result;
    }
}
