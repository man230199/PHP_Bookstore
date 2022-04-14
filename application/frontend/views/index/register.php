<?php
    $data = @$this->arrParam['form'];
    $inputUserName      = HelperBackend::createInput('form-control', 'text', "form[username]", @$data['username']);
    $inputEmail         = HelperBackend::createInput('form-control', 'text', "form[email]", @$data['email']);
    $inputFullName     = HelperBackend::createInput('form-control', 'text', "form[fullname]", @$data['fullname']);
    $inputPassword     = HelperBackend::createInput('form-control', 'password', "form[password]", @$data['password']);

    $labelUserName      = HelperBackend::createLabel('required', 'Tên Tài Khoản', $inputUserName);
    $labelEmail         = HelperBackend::createLabel('required', 'Email', $inputEmail);
    $labelFullName      = HelperBackend::createLabel('required', 'Họ Và Tên', $inputFullName);
    $labelPassword      = HelperBackend::createLabel('required', 'Mật Khẩu', $inputPassword);
    $labelArray         = [$labelUserName, $labelEmail, $labelFullName, $labelPassword];
    $cardBody = '';
    foreach($labelArray as $item) {
        $cardBody .= '<div class="col-md-6">'.$item.'</div>';
    }

    $tokenInput     = HelperBackend::createInput('', 'hidden', 'form[token]', time());
    $submitBtn      = HelperBackend::createBtn('btn btn-solid', 'submit', 'tạo tài khoản', 'no');

    $cardFooter     = $tokenInput . $submitBtn;
  //  $notification   = HelperBackend::notification();

?>  

<section class="register-page section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?= @$this->errors;  ?>
                <?= HelperBackend::notification('message') ?>
                <h3>Đăng ký tài khoản</h3>
                <div class="theme-card">
                    <form action="" method="post" id="admin-form" class="theme-form">
                        <div class="form-row">

                            <?= $cardBody?>
                            
                        </div>
                            <?= $cardFooter; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>