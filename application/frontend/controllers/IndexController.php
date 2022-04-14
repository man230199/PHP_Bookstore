<?php
class IndexController extends Controller
{

    public function __construct($arrParams)
    {
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('frontend/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
    }

    public function indexAction()
    {
        $this->_view->_title = 'Trang chủ';
        $this->_view->sliderItems           = $this->_model->listSliders($this->_arrParam, ['task' => 'home-list-items']);
        $this->_view->featureCategory       = $this->_model->listCategories($this->_arrParam, ['task' => 'feature-item']);

        $this->_view->bookByFeatureCategory = $this->_model->listBooks($this->_view->featureCategory, ['task' => 'list-items-in-category']);
        $this->_view->featureBook   = $this->_model->listSpecialBooks($this->_arrParam);
        $this->_view->arrParam      = $this->_arrParam;
        $this->_view->render($this->_arrParam['controller'] . '/index');
    }

    public function ajaxShowContentAction()
    {
        $itemInfo           = $this->_model->infoItem($this->_arrParam);
        $picture = UPLOAD_URL . DS . 'book' . DS . $itemInfo['picture'];

        $cateNameURL = URL::filterURL($itemInfo['category_name']);
        $cateIDURL  = $itemInfo['category_id'];
        $bookNameURL =  $itemInfo['name'];
        $bookIDURL = $itemInfo['id'];
        $bookLink = URL::createLink($this->_arrParam['module'], 'book', 'bookInfo', ['book_id' => $this->_arrParam['id']],"$cateNameURL/$bookNameURL-$cateIDURL-$bookIDURL.html");
        $cartLink = URL::createLink($this->_arrParam['module'],'user','order',['book_id' => $itemInfo['id'],'price' => $itemInfo['price']]);
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
