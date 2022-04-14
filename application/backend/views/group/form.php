<?php
$arrParam = $this->arrParam;

$data = @$this->arrParam['form'];

$statusValue    = ['default' => '- Select status -','active' => 'Active','inactive' => 'Inactive'];
$groupACPValue  = ['default' => '- Select group acp -',0 => 'No',1 => 'Yes'];
$inputName      = HelperBackend::createInput('text', "form[name]", @$data['name']);
$inputGroupACP  = HelperBackend::cmsSelectboxNumeric("form[group_acp]", $groupACPValue, @$data['group_acp']);

$selectStatus   = HelperBackend::cmsSelectbox("form[status]", $statusValue , @$data['status']);


$labelName      = HelperBackend::createLabel('font-weight-bold', 'Name', $inputName);
$labelStatus    = HelperBackend::createLabel('font-weight-bold', 'Status', $selectStatus);
$labelGroupACP  = HelperBackend::createLabel('font-weight-bold', 'Group_ACP', $inputGroupACP);

$cardBody       = $labelName . $labelStatus . $labelGroupACP;

$tokenInput     = HelperBackend::createInput('hidden', 'token', time());
$submitBtn      = HelperBackend::createBtn('btn btn-success', 'submit', 'save', 'no');
$cancelLink     = URL::createLink('backend', 'group', 'index',null,"quan-ly-nhom-nguoi-dung.html");
$cancelBtn      = HelperBackend::createCRUDBtn($cancelLink, 'btn btn-danger', 'Cancel');

$cardFooter     = $tokenInput . $submitBtn . $cancelBtn;

$title = (isset($this->arrParam['id'])) ? 'Edit' : 'Add';
$title .= ' ' . $this->arrParam['controller'];
?>

<body style="background-color: #eee;">
    <div class="container pt-5">
        <form action="" method="post">
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