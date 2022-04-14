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
    <?php
        if ($this->_fileView != 'index/index') include 'html/breadcrumb.php'
    ?>
    <!-- header start -->
    <?php include 'html/header.php' ?>
    <!-- header end -->

    <?php
        include APPLICATION_PATH . $this->_moduleName . DS . 'views' . DS . $this->_fileView . '.php';
    ?>

    <?php include 'html/phone-ring.php' ?>

    <?php include 'html/footer.php' ?>

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