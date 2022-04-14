<?php
class UserController extends Controller
{

    public function __construct($arrParams)
    {
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('frontend/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
    }

    public function cartAction()
    {
        $this->_view->_title        = 'Giỏ hàng của tôi';
        $this->_view->Items         = $this->_model->listItems($this->_arrParam, ['task' => 'books-in-cart']);
        $this->_view->arrParam = $this->_arrParam;
        $this->_view->render($this->_arrParam['controller'] . '/cart');
    }

    public function orderAction()
    {
        $cart       = Session::get('cart');
        $bookID     = $this->_arrParam['book_id'];
        $price      = $this->_arrParam['price'];

        if (empty($cart)) {
            $cart['quantity'][$bookID]      = $this->_arrParam['quantity'];
            $cart['price'][$bookID]         = $price * $cart['quantity'][$bookID];
        } else {
            if (key_exists($bookID, $cart['quantity'])) {
                $cart['quantity'][$bookID]    += $this->_arrParam['quantity'];
                $cart['price'][$bookID]         = $price * $cart['quantity'][$bookID];
            } else {
                $cart['quantity'][$bookID]      = $this->_arrParam['quantity'];
                $cart['price'][$bookID]         = $price * $cart['quantity'][$bookID];
            }
        }
        Session::set('cart', $cart);

        URL::redirect(URL::createLink($this->_arrParam['module'], 'user', 'cart', ['book_id' => $bookID],"cart.html"));
    }

    public function ajaxChangeQuantityAction()
    {
        $cart       = Session::get('cart');

        $bookID     = $this->_arrParam['book_id'];
        $price      = $this->_arrParam['price'];

        if (empty($cart)) {
            $cart['quantity'][$bookID]      = $this->_arrParam['quantity'];
            $cart['price'][$bookID]         = $price * $cart['quantity'][$bookID];
        } else {
            if (key_exists($bookID, $cart['quantity'])) {
                $cart['quantity'][$bookID]    += $this->_arrParam['quantity'];
                $cart['price'][$bookID]         = $price * $cart['quantity'][$bookID];
            } else {
                $cart['quantity'][$bookID]      = $this->_arrParam['quantity'];
                $cart['price'][$bookID]         = $price * $cart['quantity'][$bookID];
            }
        }
        Session::set('cart', $cart);
        $quantityLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'order', ['book_id' => $this->_arrParam['book_id'], 'quantity' => $this->_arrParam['quantity']]);

        $item = HelperFrontend::createInput('form-control ajax-input-number', 'number', 'quantity', $this->_arrParam['quantity'], $quantityLink);
        $result = [
            'status' => 'success',
            'item' => $item,
            'cart'  => $cart,
            'param' => $this->_arrParam
        ];

        echo json_encode($result);
    }

    public function historyAction()
    {
        $this->_view->_title        = 'Lịch sử mua hàng';
        $this->_view->Items         = $this->_model->listItems($this->_arrParam, ['task' => 'history-cart']);
        $this->_view->render($this->_arrParam['controller'] . '/history');
    }

    public function buyAction()
    {

        $user = Session::get('user_info');
        if (empty($user)) {
            URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'login'));
        } else {
            $this->_model->saveItem($this->_arrParam, ['task' => 'submit-cart']);
            URL::redirect(URL::createLink($this->_arrParam['module'], 'user', 'cart',null,'login.html'));
        }
    }

    public function removeCartItemAction()
    {
        // die(123);
        unset($_SESSION['cart']['quantity'][$this->_arrParam['book_id']]);
        unset($_SESSION['cart']['price'][$this->_arrParam['book_id']]);

        if (empty($_SESSION['cart']['quantity']) && empty($_SESSION['cart']['price'])) {
            URL::redirect(URL::createLink('frontend', 'index', 'index',null,'index.html'));
            Session::delete('cart');
        }
        URL::redirect(URL::createLink('frontend', 'user', 'cart'));
    }

    public function loginAction()
    {
        $this->_view->_title = 'Login';
        $userInfo = Session::get('user_info');
        if (@$userInfo['login'] == 'true') {
            URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
        }
        if (isset($this->_arrParam['form']['token'])) {
            $result = $this->_model->login(@$this->_arrParam['form']);
            if (!empty($result)) {
                $arrSession = [
                    'login' => 'true',
                    'info'  => $result
                ];
                Session::set('user_info', $arrSession);
                URL::redirect(URL::createLink($this->_arrParam['module'], 'dashboard', 'index',null,'danh-muc-quan-ly.html'));
            } else {
                $this->_view->error = 'Thông tin đăng nhập không chính xác';
            }
        }
        $this->_view->render($this->_arrParam['controller'] . '/login');
    }

    public function logoutAction()
    {
        Session::delete('user_info');
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'login',null,'login.html'));
    }

    public function registerAction()
    {
        $this->_view->_title = 'Đăng kí';
        $listUsers = $this->_model->listItems($this->_arrParam, ['task' => 'list-users']);
        if (isset($this->_arrParam['form']['token'])) {
            $validate       = new Validate($this->_arrParam['form']);
            $userNameList   = [];
            $userEmailList  = [];
            foreach ($listUsers as $user) {
                $userNameList[]  = $user['username'];
                $userEmailList[] = $user['email'];
            }
            $validate->addRule('username', 'user', ['deny' => $userNameList])
                ->addRule('email', 'user', ['deny' => $userEmailList])
                ->addRule('fullname', 'string', ['min' => 5, 'max' => 100])
                ->addRule('password', 'string', ['min' => 5, 'max' => 30]);
            $validate->run();
            $this->_arrParam['form'] = $validate->getResult();
            if ($validate->isValid() == false) {
                $this->_view->errors = $validate->showErrors();
            } else {
                $this->_model->register($this->_arrParam);
                URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'register'));
            }
        }
        $this->_view->arrParam = $this->_arrParam;
        $this->_view->render($this->_arrParam['controller'] . '/register');
    }
}
