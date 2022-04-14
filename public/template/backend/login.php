
<!DOCTYPE html>
<html lang="en">
<head>
  

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <?php include 'html/head.php' ?>

</head>
<body class="hold-transition login-page">
    <?php
        include APPLICATION_PATH . $this->_moduleName . DS . 'views' . DS . $this->_fileView . '.php';
    ?>
<!-- /.login-box -->

<!-- jQuery -->
    <?php include 'html/script.php' ?>
</body>
</html>
