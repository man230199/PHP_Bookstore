<?php
$loginError         = HelperBackend::notification(@$this->error, false);
$registerLink       = URL::createLink('frontend','user','register');
$registerBtn        = HelperBackend::createCRUDBtn($registerLink,'btn btn-solid','Đăng kí');
$inputEmail         = HelperFrontend::createInput('form-control','email', "form[email]", '');
$inputPassword      = HelperFrontend::createInput('form-control','password', "form[password]", '');
$inputToken         = HelperFrontend::createInput('form-control','hidden', "form[token]", time());
$submitBtn          = HelperBackend::createBtn('btn btn-solid', 'submit', 'Đăng nhập', 'Đăng nhập');

$labelEmail         = HelperBackend::createLabel('required', 'Email', $inputEmail);
$labelPassword      = HelperBackend::createLabel('required', 'Password', $inputPassword);
$labelArray         = [$labelEmail, $labelPassword];
$cardBody = '';
foreach ($labelArray as $item) {
    $cardBody .= '<div class="form-group">' . $item . '</div>';
}
$cardBody .= $inputToken . $submitBtn;


?>

<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">
                        Đăng nhập </h2>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="login-page section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">

                <h3>Đăng nhập</h3>
                <?= $loginError ?>
                <div class="theme-card">
                    <form action="" method="post" id="admin-form" class="theme-form">
                        <?= $cardBody; ?>

                    </form>
                </div>
            </div>
            <div class="col-lg-6 right-login">
                <h3>Khách hàng mới</h3>
                <div class="theme-card authentication-right">
                    <h6 class="title-font">Đăng ký tài khoản</h6>
                    <p>Sign up for a free account at our store. Registration is quick and easy. It allows you to be
                        able to order from our shop. To start shopping click register.</p>
                    <?= $registerBtn ?>
                </div>
            </div>
        </div>
    </div>
</section>