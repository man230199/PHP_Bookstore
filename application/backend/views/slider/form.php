<?php
$arrParam = $this->arrParam;

$data = @$this->arrParam['form'];
$destination = UPLOAD_URL . 'category' . DS;

$picture = $destination . 'default.jpg';
if(isset($this->arrParam['id'])) {
    if(is_array($data['picture'])) {
        $picture = $destination . $data['picture']['name'];
    }else{
        $picture = $destination . $data['picture'];
    }
}

$statusValue    = ['default' => '- Select status -', 'active' => 'Active', 'inactive' => 'Inactive'];
$inputName      = HelperBackend::createInput('form-control', 'text', "form[name]", @$data['name']);
$inputOrdering  = HelperBackend::createInput('form-control', 'text', "form[ordering]", @$data['ordering']);
$inputPicture   = HelperBackend::createInput('form-control', 'file', "picture", @$data['picture']);
$inputPreviewPicture = '<img src="' . $picture . '" width="500px" height="200px" id="preview_picpure" name="preview_picture""/>';
$inputHiddenPicture = HelperBackend::createInput('form-control', 'hidden', "form[picture_hidden]", @$data['picture']);

$selectStatus   = HelperBackend::cmsSelectbox("form[status]", 'form-control custom-select', $statusValue, @$data['status']);


$labelName      = HelperBackend::createLabel('font-weight-bold', 'Name', $inputName);
$labelStatus    = HelperBackend::createLabel('font-weight-bold', 'Status', $selectStatus);
$labelOrdering  = HelperBackend::createLabel('font-weight-bold', 'Ordering', $inputOrdering);
$labelPicture   = HelperBackend::createLabel('font-weight-bold', 'Picture', $inputPicture);
$cardBody       = $labelName . $labelStatus . $labelOrdering . $labelPicture . $inputPreviewPicture;

$tokenInput     = HelperBackend::createInput('form-control', 'hidden', 'token', time());
$submitBtn      = HelperBackend::createBtn('btn btn-success', 'submit', 'save', 'no');
$cancelLink     = URL::createLink($this->arrParam['module'], $this->arrParam['controller'], 'index');
$cancelBtn      = HelperBackend::createCRUDBtn($cancelLink, 'btn btn-danger', 'Cancel');

$cardFooter     = $tokenInput . $inputHiddenPicture . $submitBtn . $cancelBtn;

$title = (isset($this->arrParam['id'])) ? 'Edit' : 'Add';
$title .= ' ' . $this->arrParam['controller'];
?>

<div class="container pt-5">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="m-0"><?= $title ?></h4>
            </div>
            <div class="card-body">
                <?php echo @$this->errors ?>
                <?php echo $cardBody; ?>
            </div>
            <div class="card-footer">
                <?php echo $cardFooter; ?>
            </div>
        </div>
    </form>
</div>