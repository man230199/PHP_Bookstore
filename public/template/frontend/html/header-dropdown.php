<?php 

    $loginLink      = URL::createLink('frontend','user','login',null,'login.html');
    $registerLink   = URL::createLink('frontend','user','register',null,'register.html');
    $logoutLink     = URL::createLink('frontend','user','logout');
   
   // Session::init();
    if(!empty(Session::get('user_info'))) {
        $userInfo = Session::get('user_info');
        $adminLink = '#';
        $historyLink = URL::createLink('frontend','user','history');
        if($userInfo['info']['group_id'] == 1) {
            $adminLink = URL::createLink('backend','dashboard','index',null,'danh-muc-quan-ly.html');
            $adminItem = sprintf('<li><a href="#">%s</a></li>',$adminLink,'Trang quản lí');
        }
       
        $username       = Session::get('user_info')['info']['username'];
        $dropdownHtml   = sprintf('
            <li><a href="%s">%s</a></li>
            <li><a href="%s">%s</a></li>
            <li><a href="%s">%s</a></li>',$adminLink,$username,$historyLink,'Lịch sử',$logoutLink,'Đăng xuất');
    } else {
        $dropdownHtml = sprintf('
            <li><a href="%s">Đăng nhập</a></li>
            <li><a href="%s">Đăng ký</a></li>',$loginLink, $registerLink);
    }
?>
<ul class="header-dropdown">
    <li class="onhover-dropdown mobile-account">
        <img src="<?= $this->_dirImg ?>/avatar.png" alt="avatar">
        <ul class="onhover-show-div">

            <?= $dropdownHtml ?>
        </ul>
    </li>
</ul>