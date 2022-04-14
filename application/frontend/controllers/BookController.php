<?php
class BookController extends Controller
{

    public function __construct($arrParams)
    {
        $this->_arrParam = array_merge($_POST, $_GET, $_FILES);
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('frontend/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
    }

    public function listAction()
    {
        $this->_view->_title = 'Danh sách sản phẩm';
        $itemStatusCount    = $this->_model->countItems($this->_arrParam, ['type' => 'book']);
        $totalItems         = $itemStatusCount['all'];
        $configPagination   = ['totalItemsPerPage'    => 12, 'pageRange' => 3];
        $this->setPagination($configPagination);
        $this->_view->pagination    = new Pagination($totalItems, $this->_pagination);

        $this->_view->featureBooks          = $this->_model->listBooks($this->_arrParam, ['task' => 'list-feature-items']);
        $this->_view->categoryList = $this->_model->listCategories($this->_arrParam);
        $this->_view->items     = $this->_model->listItems($this->_arrParam, ['task' => 'list-items']);
        $this->_view->arrParam = $this->_arrParam;
        $this->_view->render($this->_arrParam['controller'] . '/list');
    }


    public function bookInfoAction()
    {
        $this->_view->_title = 'Thông tin sản phẩm';
        $this->_view->categoryList = $this->_model->listCategories($this->_arrParam);
        $this->_view->item     = $this->_model->infoItem(['id' => $this->_arrParam['book_id']]);

        $this->_view->relatedBook = $this->_model->listBooks($this->_view->item, ['task' => 'list-relate-items']);

        $this->_view->featureBooks          = $this->_model->listBooks($this->_arrParam, ['task' => 'list-feature-items']);
        $this->_view->arrParam = $this->_arrParam;
        $this->_view->render($this->_arrParam['controller'] . '/book-info');
    }

    public function ajaxShowContentAction()
    {
        $itemInfo           = $this->_model->bookInfo($this->_arrParam);
        $picture = UPLOAD_URL . DS . 'book' . DS . $itemInfo['picture'];
        $bookLink = URL::createLink($this->_arrParam['module'], 'book', 'bookInfo', ['book_id' => $this->_arrParam['id']]);
        $cartLink = URL::createLink($this->_arrParam['module'], 'user', 'order', ['book_id' => $itemInfo['id'], 'price' => $itemInfo['price']]);
        $result = [
            'status'    => 'success',
            'item'      => $itemInfo,
            'picture'   => $picture,
            'detailLink' => $bookLink,
            'cartLink'  => $cartLink
        ];
        echo json_encode($result);
    }
}
