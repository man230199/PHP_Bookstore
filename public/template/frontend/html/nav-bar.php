<?php 
    require 'application/frontend/models/CategoryModel.php';

    $categoryModel = new CategoryModel();
    $categoryList = $categoryModel->listItems();
    $homeLink = URL::createLink('frontend','index','index',null,'index.html');
    $bookLink = URL::createLink('frontend','book','list',null,'book-list.html');
    $homeMenu = HelperFrontend::createMenuItem($homeLink,'Trang chủ');
    $bookMenu = HelperFrontend::createMenuItem($bookLink,'Sách'); 
    $dropdownCategory = '<ul>';
    foreach($categoryList as $category) {

        $categoryID = $category['id'];
        $nameURL = URL::filterURL($category['name']);
        $categoryLink      = URL::createLink('frontend','book','list',['category_id' => $categoryID],"$nameURL-$categoryID.html");
        $dropdownCategory .= HelperFrontend::createMenuItem($categoryLink,$category['name']);
    }
    $dropdownCategory .= '</ul>';
    $categoryMenu = HelperFrontend::createMenuItem('#','Danh mục',$dropdownCategory);
    
?>

<nav id="main-nav">
    <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
    <ul id="main-menu" class="sm pixelstrap sm-horizontal">
        <li>
            <div class="mobile-back text-right">Back<i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
        </li>
        <?=$homeMenu . $bookMenu; ?>
        <?=$categoryMenu; ?>
    </ul>
</nav>