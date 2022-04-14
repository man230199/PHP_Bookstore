<?php 
    $loginError         = HelperBackend::notification(@$this->error,false);
    $inputEmail         = HelperBackend::createInput('form-control', 'email', "form[email]", '');
    $inputPassword      = HelperBackend::createInput('form-control', 'password', "form[password]", '');
    $inputToken         = HelperBackend::createInput('form-control', 'hidden', "form[token]", time());
    $submitBtn          = HelperBackend::createBtn('btn btn-solid', 'submit', 'Đăng nhập', 'Đăng nhập');
    
    $labelEmail         = HelperBackend::createLabel('required', 'Email', $inputEmail);
    $labelPassword      = HelperBackend::createLabel('required', 'Password', $inputPassword);
    $labelArray         = [$labelEmail, $labelPassword];
    $cardBody = '';
    foreach($labelArray as $item) {
        $cardBody .= '<div class="input-group mb-3">'.$item.'</div>';
    }
    $cardBody .= $inputToken . $submitBtn;
?>
<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html"><b>Admin</b>LTE</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <?= $loginError;?>
            <form action="" method="post">
                <div class="input-group mb-3">
                    <input type="email" name="form[email]" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="form[password]" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <input type="hidden" value="12345" name="form[token]">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1">
                <a href="forgot-password.html">I forgot my password</a>
            </p>
            <p class="mb-0">
                <a href="register.html" class="text-center">Register a new membership</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>