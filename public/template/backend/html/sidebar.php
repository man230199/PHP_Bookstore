<?php 
    $dashboardLink  = URL::createLink('backend','dashboard','index',null,'danh-muc-quan-ly.html');
    $groupLink      = URL::createLink('backend','group','index',null,'quan-ly-nhom-nguoi-dung.html');
    $groupAddLink   = URL::createLink('backend','group','form',null,'them-moi-nhom-nguoi-dung.html');
    $userLink       = URL::createLink('backend','user','index',null,'quan-ly-nguoi-dung.html');
    $userAddLink    = URL::createLink('backend','user','form',null,'them-moi-nguoi-dung.html');
    $categoryLink   = URL::createLink('backend','category','index',null,'quan-ly-danh-muc-sach.html');
    $categoryAddLink = URL::createLink('backend','category','form',null,'them-moi-danh-muc-sach.html');
    $bookLink       = URL::createLink('backend','book','index',null,'quan-ly-sach.html');
    $bookAddLink    = URL::createLink('backend','book','form',null,'them-moi-sach.html');
    $orderLink      = URL::createLink('backend','order','index',null,'quan-ly-don-hang.html');
    $dashboard      = HelperBackend::createNavItem($dashboardLink,'tachometer-alt','Dashboard');

    $sidebarItems = [
        'Group' => [
            'list'  => ['name' => 'List','link' => $groupLink],
            'form'  => ['name' => 'Add','link' => $groupAddLink],
            'icon'  => 'fa-users',
        ],
        'User' => [
            'list'  => ['name' => 'List','link' => $userLink],
            'form'  => ['name' => 'Add','link' => $userAddLink],
            'icon'  => 'fa-user',
        ],
        'Category' => [
            'list'  => ['name' => 'List','link' => $categoryLink],
            'form'  => ['name' => 'Add','link' => $categoryAddLink],
            'icon'  => 'fa-thumbtack',
        ],
        'Book' => [
            'list'  => ['name' => 'List','link' => $bookLink],
            'form'  => ['name' => 'Add','link' => $bookAddLink],
            'icon'  => 'fa-book-open',
        ],
        'Order' => [
            'list'  => ['name' => 'List','link' => $orderLink],
            'form'  => ['name' => 'Add','link' => '#'],
            'icon'  => 'fa-book-open',
        ],
    ];
    $sidebarXhtml = HelperBackend::createSideBarItem($sidebarItems);
?>
<aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="<?php echo $this->_dirImg ?>/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" />
        <span class="brand-text font-weight-light">Admin Control Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo $this->_dirImg ?>/avatar.jpg" class="img-circle elevation-2" alt="User Image" />
            </div>
            <div class="info">
                <a href="#" class="d-block">ZendVN</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
               <?= $dashboard?>
              <?= $sidebarXhtml?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>