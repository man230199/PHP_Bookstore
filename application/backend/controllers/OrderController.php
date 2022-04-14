<?php
class OrderController extends Controller
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
        $itemStatusCount        = $this->_model->countItems($this->_arrParam, ['type' => 'status']);
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
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
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
