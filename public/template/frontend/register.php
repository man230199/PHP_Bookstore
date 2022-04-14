<!DOCTYPE html>
<html lang="en">

<head>
   <?php include 'html/head.php' ?> 
</head>

<body>
    <div class="loader_skeleton">
        <div class="typography_section">
            <div class="typography-box">
                <div class="typo-content loader-typo">
                    <div class="pre-loader"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- header start -->
    <?php include 'html/header.php' ?> 
    <!-- header end -->

    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-title">
                        <h2 class="py-2">Đăng ký tài khoản</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
        include APPLICATION_PATH . $this->_moduleName . DS . 'views' . DS . $this->_fileView . '.php';
    ?>

    <?php include 'html/phone-ring.php' ?> 

    <?php include 'html/fotter.php' ?> 

    <!-- tap to top -->
    <div class="tap-top top-cls">
        <div>
            <i class="fa fa-angle-double-up"></i>
        </div>
    </div>
    <!-- tap to top end -->

    <?php include 'html/script.php' ?> 
</body>

</html>