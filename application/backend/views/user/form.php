<?php
$arrParam = $this->arrParam;

$data = @$this->arrParam['form'];


$statusValue = ['default' => '- Select status -','active' => 'Active','inactive' => 'Inactive'];
$groupValue  = ['default' => '- Select group name -',1 => 'Admin',2 => 'Manager',3 => 'Member',];
if(isset($this->arrParam['id'])) $disabled = true;
$inputName      = HelperBackend::createInput('text', "form[username]", @$data['username'],@$disabled);
$inputEmail     = HelperBackend::createInput('text', "form[email]", @$data['email'],@$disabled);
$inputGroup     = HelperBackend::cmsSelectboxNumeric("form[group_id]", $groupValue, @$data['group_id']);

$selectStatus   = HelperBackend::cmsSelectbox("form[status]", $statusValue , @$data['status']);

$labelName      = HelperBackend::createLabel('font-weight-bold', 'Name', $inputName);
$labelEmail     = HelperBackend::createLabel('font-weight-bold', 'Email', $inputEmail);
$labelStatus    = HelperBackend::createLabel('font-weight-bold', 'Status', $selectStatus);
$labelGroup     = HelperBackend::createLabel('font-weight-bold', 'Group', $inputGroup);

$cardBody       = $labelName . $labelEmail . $labelStatus . $labelGroup;

$tokenInput     = HelperBackend::createInput('hidden', 'token', time());
$submitBtn      = HelperBackend::createBtn('btn btn-success', 'submit', 'save', 'no');
$cancelLink     = URL::createLink('backend', $this->arrParam['controller'], 'index');
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