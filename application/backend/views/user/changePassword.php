<?php
$arrParam = $this->arrParam;

$data = @$this->arrParam['form'];

$statusValue = ['default' => '- Select status -','active' => 'Active','inactive' => 'Inactive'];
$groupValue  = ['default' => '- Select group name -',1 => 'Admin',2 => 'Manager',3 => 'Member',];


if(isset($this->arrParam['id'])) $disabled = true;
$inputName      = HelperBackend::createInput('text', "form[username]", @$data['username'],@$disabled);
$inputEmail     = HelperBackend::createInput('text', "form[email]", @$data['email'],@$disabled);
$inputGroup     = HelperBackend::createInput('text', "form[group_id]", @$data['group_id'],@$disabled);
$inputStatus    = HelperBackend::createInput('text', "form[status]", @ucfirst($data['status']),@$disabled);
$inputPassword  = HelperBackend::createInput('text', "form[password]");

$labelName      = HelperBackend::createLabel('font-weight-bold', 'Name', $inputName);
$labelEmail     = HelperBackend::createLabel('font-weight-bold', 'Email', $inputEmail);
$labelStatus    = HelperBackend::createLabel('font-weight-bold', 'Status', $inputStatus);
$labelGroup     = HelperBackend::createLabel('font-weight-bold', 'Group', $inputGroup);
$labelPassword  = HelperBackend::createLabel('font-weight-bold', 'New Password', $inputPassword);

/* $generateBtn    = '<button href="'.$generateLink.'" class="btn btn-info"><i class="fas fa-key"> Generate</i></button>'; */
$generateBtn    = '<button type="button" data-input="form[password]" value="123" id="generate-password" class="btn btn-info"><i class="fas fa-key"> Generate</i></button>';
$cardBody       = $labelName . $labelEmail . $labelStatus . $labelGroup . $labelPassword . $generateBtn;

$tokenInput     = HelperBackend::createInput('hidden', 'token', time());
$submitBtn      = HelperBackend::createBtn('btn btn-success', 'submit', 'save', 'no');
$cancelLink     = URL::createLink('backend', $this->arrParam['controller'], 'index');

$cancelBtn      = HelperBackend::createCRUDBtn($cancelLink, 'btn btn-danger', 'Cancel');


$cardFooter     = $tokenInput . $submitBtn . $cancelBtn;
$title = (isset($this->arrParam['id'])) ? 'Add' : 'Edit';
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