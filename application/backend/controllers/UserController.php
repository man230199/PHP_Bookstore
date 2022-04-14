<?php
class UserController extends Controller
{

    public function __construct($arrParams)
    {
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('backend/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
    }

    public function indexAction()
    {
        $itemStatusCount    = $this->_model->countItems($this->_arrParam, ['type' => 'status']);
        $totalItems         = $itemStatusCount[$this->_arrParam['currentStatus'] ?? 'all'];

        $configPagination   = ['totalItemsPerPage'    => 2, 'pageRange' => 3];
        $this->setPagination($configPagination);
        $this->_view->pagination    = new Pagination($totalItems, $this->_pagination);

        $this->_view->groupValues = $this->_model->groupValuesInSelectBox($this->_arrParam);
        $this->_view->items = $this->_model->listItems($this->_arrParam, ['task' => 'list-users']);
        $this->_view->itemStatusCount = $this->_model->countItems($this->_arrParam, ['type' => 'status']);;
        if (empty(Session::get('user_info'))) {
            URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'login'));
        } else {
            if (Session::get('user_info')['info']['group_id'] != '1') {
                URL::redirect(URL::createLink('frontend', 'index', 'index'));
            }
        }
        $this->_view->arrParams = $this->_arrParam;
        $this->_view->render($this->_arrParam['controller'] . '/index');
    }

    public function loginAction()
    {
        $this->_templateObj->setFolderTemplate('backend/');
        $this->_templateObj->setFileTemplate('login.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();

        $userInfo = Session::get('user_info');
        if (@$userInfo['login'] == 'true') {
            URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
        }
        if (isset($this->_arrParam['form']['token'])) {
            $result = $this->_model->login($this->_arrParam['form']);
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
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'login'));
    }


    public function changeStatusAction()
    {
        $this->_model->changeStatus($this->_arrParam, ['task' => 'change-status']);
        $status = ($this->_arrParam['status'] == 'active') ? 'inactive' : 'active';
        $classStatus = ($status == 'active') ? 'btn btn-success' : 'btn btn-danger';
        $iconStatus = ($status == 'active') ? 'fa-check' : 'fa-minus';
        $linkStatus = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'changeStatus', ['id' => $this->_arrParam['id'], 'status' => $status]);
        $item = HelperBackend::createBtnAjaxAttr($linkStatus, $classStatus, $iconStatus);
        $result = [
            'status' => 'success',
            'data'  => [
                'item' => $item,
            ]
        ];
        echo json_encode($result);
    }

    public function changeGroupAction()
    {
        $this->_model->changeGroup($this->_arrParam);
        $changeGroupLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'changeGroup', ['id' => $this->_arrParam['id'], 'group_id' => $this->_arrParam['group_id']]);
        $groupValues = $this->_model->groupValuesInSelectBox($this->_arrParam);
        $item = HelperBackend::cmsSelectbox('group_id', $groupValues, $this->_arrParam['group_id'], $changeGroupLink);
        $result = [
            'status' => 'success',
            'data'  => [
                'item' => $item,
            ]
        ];
        echo json_encode($result);
    }

    public function multiActiveAction()
    {

        $this->_model->changeStatus($this->_arrParam, ['task' => 'multi-change-status-active']);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
    }


    public function multiInactiveAction()
    {
        $this->_model->changeStatus($this->_arrParam, ['task' => 'multi-change-status-inactive']);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
    }

    public function changeGroupACPAction()
    {
        $this->_model->changeGroupACP($this->_arrParam);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
    }

    public function formAction()
    {
        if (isset($this->_arrParam['token'])) {
            $validate = new Validate($this->_arrParam['form']);
            if (isset($this->_arrParam['id'])) {
                $validate->addRule('status', 'status', ['deny' => ['default']])
                    ->addRule('group_id', 'group', ['deny' => ['default']]);
            } else {
                //username, email khong trung`
                $validate->addRule('username', 'string', ['min' => 5, 'max' => 255])
                    ->addRule('email', 'string', ['min' => 5, 'max' => 255])
                    ->addRule('group_id', 'group', ['deny' => ['default']])
                    ->addRule('status', 'status', ['deny' => ['default']]);
            }
            $validate->run();
            $this->_arrParam['form'] = $validate->getResult();

            if ($validate->isValid() == false) {
                $this->_view->errors = $validate->showErrors();
            } else {
                $this->_model->saveItem($this->_arrParam);
                URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
            }
        }

        if (isset($this->_arrParam['id']) && !isset($this->_arrParam['token'])) {
            $this->_arrParam['form'] = $this->_model->infoItem(['id' => $this->_arrParam['id']]);
        }


        $this->_view->arrParam = $this->_arrParam;
        $this->_view->render($this->_arrParam['controller'] . '/form');
    }

    public function changePasswordAction()
    {

        if (isset($this->_arrParam['token'])) {
            $validate = new Validate($this->_arrParam['form']);
            $validate->addRule('password', 'password', ['action' => ['edit']]);
            $validate->run();
            $this->_arrParam['form'] = $validate->getResult();

            if ($validate->isValid() == false) {
                $this->_view->errors = $validate->showErrors();
            } else {
                $this->_model->saveItem($this->_arrParam, ['task' => 'change-password']);
                URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
            }
        }

        if (isset($this->_arrParam['id']) && !isset($this->_arrParam['token'])) {
            $this->_arrParam['form'] = $this->_model->infoItem(['id' => $this->_arrParam['id']]);
        }

        $this->_view->arrParam = $this->_arrParam;
        $this->_view->render($this->_arrParam['controller'] . '/changePassword');
    }

    public function deleteAction()
    {
        $this->_model->deleteItem($this->_arrParam, ['task' => 'delete']);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
    }

    public function multiDeleteAction()
    {
        $this->_arrParam['task'] == 'multidelete';
        $this->_model->deleteItem($this->_arrParam, ['task' => 'multiDelete']);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
    }
}
