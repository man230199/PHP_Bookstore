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
}
