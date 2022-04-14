<?php
$arrParam = $this->arrParam;

$data = @$this->arrParam['form'];

$destination = UPLOAD_URL . 'book' . DS;
$picture = $destination . 'default.jpg';
if (isset($this->arrParam['id'])) {

    if (is_array($data['picture'])) {
        $picture = $destination . $data['picture']['name'];
    } else {
        $picture = $destination . $data['picture'];
    }
}

$listCategories = $this->listCategories;
$categoryArray = ['default' => '- Select Category -'];
foreach ($listCategories as $category) {
    $categoryArray[$category['id']] = $category['name'];
}

$statusValue        = ['default' => '- Select status -', 'active' => 'Active', 'inactive' => 'Inactive'];
$specialValue       = ['default' => '- Select special -', 0 => 'No', 1 => 'Yes'];
$inputName          = HelperBackend::createInput('text', "form[name]", @$data['name']);
$inputDescription   = HelperBackend::createTextArea(@$data['description'], "form[description]", 30, 10);
$inputShortDescription   = HelperBackend::createTextArea(@$data['short_description'], "form[short_description]", 30, 10);
/* $inputDescription   = HelperBackend::createInput('form-control', 'text', "form[description]", @$data['description']); */
$inputPrice         = HelperBackend::createInput('text', "form[price]", @$data['price']);
$inputSaleOff       = HelperBackend::createInput('number', "form[sale_off]", @$data['sale_off']);
$inputSpecial       =  HelperBackend::cmsSelectboxNumeric("form[special]", $specialValue, @$data['special']);

$inputOrdering  = HelperBackend::createInput('text', "form[ordering]", @$data['ordering']);
$inputPicture   = HelperBackend::createInput('file', "picture", @$data['picture']);
$inputPreviewPicture = '<img src="' . $picture . '" width="500px" height="200px" id="preview_picpure" name="preview_picture""/>';
$inputHiddenPicture = HelperBackend::createInput('hidden', "form[picture_hidden]", @$data['picture']);

$selectCategory   = HelperBackend::cmsSelectbox("form[category_id]", $categoryArray, @$data['category_id']);
$selectStatus   = HelperBackend::cmsSelectbox("form[status]", $statusValue, @$data['status']);


$labelName      = HelperBackend::createLabel('font-weight-bold', 'Name', $inputName);
$labelShortDescription      = HelperBackend::createLabel('font-weight-bold', 'Short Description', $inputShortDescription);
$labelDescription      = HelperBackend::createLabel('font-weight-bold', 'Description', $inputDescription);
$labelPrice     = HelperBackend::createLabel('font-weight-bold', 'Price', $inputPrice);
$labelSaleOff   = HelperBackend::createLabel('font-weight-bold', 'SaleOff', $inputSaleOff);
$labelSpecial   = HelperBackend::createLabel('font-weight-bold', 'Special', $inputSpecial);
$labelCategory  = HelperBackend::createLabel('font-weight-bold', 'Category', $selectCategory);
$labelStatus    = HelperBackend::createLabel('font-weight-bold', 'Status', $selectStatus);
$labelOrdering  = HelperBackend::createLabel('font-weight-bold', 'Ordering', $inputOrdering);
$labelPicture   = HelperBackend::createLabel('font-weight-bold', 'Picture', $inputPicture);

$cardBody       = $labelName . $labelShortDescription .  $labelDescription . $labelPrice . $labelSaleOff . $labelSpecial .  $labelCategory . $labelStatus . $labelOrdering . $labelPicture . $inputPreviewPicture;

$tokenInput     = HelperBackend::createInput('hidden', 'token', time());
$submitBtn      = HelperBackend::createBtn('btn btn-success', 'submit', 'save', 'no');
$cancelLink     = URL::createLink($this->arrParam['module'], $this->arrParam['controller'], 'index',null,"quan-ly-sach.html");
$cancelBtn      = HelperBackend::createCRUDBtn($cancelLink, 'btn btn-danger', 'Cancel');

$cardFooter     = $tokenInput . $inputHiddenPicture . $submitBtn . $cancelBtn;

$title = (isset($this->arrParam['id'])) ? 'Edit' : 'Add';
$title .= ' ' . $this->arrParam['controller'];
?>

<body style="background-color: #eee;">
    <div class="container pt-5">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="m-0"><?= $title ?></h4>
                </div>
                <div class="card-body">
                    <p>
                        <?php echo @$this->errors ?>
                    </p>
                    <?php echo $cardBody; ?>
                </div>
                <div class="card-footer">
                    <?php echo $cardFooter; ?>
                </div>
            </div>
        </form>
    </div>