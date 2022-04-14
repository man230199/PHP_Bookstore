<?php
class GroupController extends Controller{
	
	public function __construct($arrParams){
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate('backend/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

    public function indexAction() {
        $itemStatusCount    = $this->_model->countItems($this->_arrParam,['type' => 'status']);
        $itemGroupACPCount  = $this->_model->countItems($this->_arrParam,['type' => 'group_acp']);
        $this->_view->items = $this->_model->listItems($this->_arrParam);
        $this->_view->itemStatusCount = $itemStatusCount;
        $totalItems         = $itemStatusCount['all'];
        
        if(isset($this->_arrParam['currentStatus'])) {
            $currentStatus = $this->_arrParam['currentStatus'];
            if($currentStatus != 'all') $totalItems = $itemStatusCount[$currentStatus];    
        }

        if(isset($this->_arrParam['group_acp'])) {
            $GroupACP = $this->_arrParam['group_acp'];
            if($GroupACP != 'default') $totalItems = $itemGroupACPCount[$GroupACP];    
        }


        $configPagination   = ['totalItemsPerPage'	=> 2, 'pageRange' => 3];
		$this->setPagination($configPagination);
		$this->_view->pagination	= new Pagination($totalItems, $this->_pagination);

		
        $this->_view->arrParams = $this->_arrParam;
        $this->_view->render($this->_arrParam['controller'] .'/index');
    }

	
    public function changeStatusAction()
    {
        $this->_model->changeStatus($this->_arrParam,['task' => 'change-status']);
        $status = ($this->_arrParam['status'] == 'active') ? 'inactive' : 'active';
        $classStatus = ($status == 'active') ? 'btn btn-success' : 'btn btn-danger';
        $iconStatus = ($status == 'active') ? 'fa-check' : 'fa-minus';
        $linkStatus = URL::createLink($this->_arrParam['module'],$this->_arrParam['controller'],'changeStatus',['id' => $this->_arrParam['id'],'status' => $status]);
        $item = HelperBackend::createBtnAjaxAttr($linkStatus,$classStatus,$iconStatus);
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

        $this->_model->changeStatus($this->_arrParam,['task' => 'multi-change-status-active']);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
      
    }
	

	public function multiInactiveAction()
    {
        $this->_model->changeStatus($this->_arrParam,['task' => 'multi-change-status-inactive']);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
    }

	public function changeGroupACPAction()
    {
        $this->_model->changeGroupACP($this->_arrParam);
        $groupACP = ($this->_arrParam['group_acp'] == '1') ? '0' : '1';
        $classACP = ($groupACP == '1') ? 'btn btn-success' : 'btn btn-danger';
        $iconGroupACP = ($groupACP == '1') ? 'fa-check' : 'fa-minus';
        $linkACP = URL::createLink($this->_arrParam['module'],$this->_arrParam['controller'],'changeGroupACP',['id' => $this->_arrParam['id'],'group_acp' => $groupACP]);
        $item = HelperBackend::createBtnAjaxAttr($linkACP,$classACP,$iconGroupACP);
        $result = [
            'status' => 'success',
            'data'  => [
                'item' => $item,
            ]
        ];
        echo json_encode($result);
    }

    public function formAction()
    {
       
        if (isset($this->_arrParam['token'])) {
            $validate = new Validate($this->_arrParam['form']);
            $validate->addRule('name', 'string', ['min' => 5, 'max' => 255])
                    ->addRule('group_acp', 'status', ['deny' => ['default']])
                    ->addRule('status', 'status', ['deny' => ['default']]);
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

	public function deleteAction() {
		$this->_model->deleteItem($this->_arrParam,['task' => 'delete']);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
	}

	public function multiDeleteAction() {
        $this->_arrParam['task'] == 'multidelete';
		$this->_model->deleteItem($this->_arrParam,['task' => 'multiDelete']);
        URL::redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
	}

}