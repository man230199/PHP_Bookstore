<?php
class CategoryController extends Controller
{

    public function __construct($arrParams)
    {
        $this->_arrParam = array_merge($_POST, $_GET, $_FILES);
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('backend/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
    }

    public function indexAction()
    {
        $itemStatusCount        = $this->_model->countItems($this->_arrParam, ['type' => 'status']);
        $itemGroupACPCount      = $this->_model->countItems($this->_arrParam, ['type' => 'group_acp']);
        $this->_view->items     = $this->_model->listItems($this->_arrParam);
        $this->_view->itemStatusCount = $itemStatusCount;
        $totalItems             = $itemStatusCount['all'];

        if (isset($this->_arrParam['currentStatus'])) {
            $currentStatus = $this->_arrParam['currentStatus'];
            if ($currentStatus != 'all') $totalItems = $itemStatusCount[$currentStatus];
        }

        $configPagination   = ['totalItemsPerPage'    => 2, 'pageRange' => 3];
        $this->setPagination($configPagination);
        $this->_view->pagination    = new Pagination($totalItems, $this->_pagination);


        $this->_view->arrParams = $this->_arrParam;
        $this->_view->render($this->_arrParam['controller'] . '/index');
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

    public function changeOrderingAction()
    {
        $this->_model->changeOrdering($this->_arrParam, ['task' => 'change-ordering']);
        $linkOrdering = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'changeOrdering', ['id' => $this->_arrParam['id'], 'ordering' => $this->_arrParam['ordering']]);
        $item = HelperBackend::createInput('number', "form[ordering]", $this->_arrParam['ordering'],false,$linkOrdering);

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

    public function formAction()
    {
        if (!empty($_FILES))  $this->_arrParam['form']['picture'] = $_FILES['picture'];
        if (isset($this->_arrParam['token'])) {
            $validate = new Validate($this->_arrParam['form']);
            $query = "SELECT `id` FROM `category` WHERE `name` = '{$this->_arrParam['form']['name']}' ";
            if(isset($this->_arrParam['id'])) {
                $query .= "AND `id` <> '{$this->_arrParam['id']}'";
            }
            $validate->addRule('name', 'isExistRecord', ['database' => $this->_model, 'query' => $query])
                ->addRule('ordering', 'int', ['min' => 1, 'max' => 255])
                ->addRule('status', 'status', ['deny' => ['default']])
                ->addRule('picture','file',['min' => 1000,'max' => 1000000,'extension' => ['jpg','png']],false);
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

    public function deleteAction()
    {
        $item = $this->_model->infoItem(['id' => $this->_arrParam['delete_id']]);
        $this->_model->deleteItem($this->_arrParam, ['task' => 'delete']);
        $destination = UPLOAD_PATH . 'category' . DS . '60x90-';
        unlink($destination . $item['picture']);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
    }

    public function multiDeleteAction()
    {
        $this->_arrParam['task'] == 'multidelete';
        $this->_model->deleteItem($this->_arrParam, ['task' => 'multiDelete']);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
    }
}
