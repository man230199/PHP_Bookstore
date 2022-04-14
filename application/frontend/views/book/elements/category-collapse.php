<?php
$categoryList = $this->categoryList;
$arrParams = $this->arrParam;
$collapseCategory = '';
$index = 0;

foreach ($categoryList as $category) {
    $index++;
    $categoryID = $category['id'];
    $nameURL = URL::filterURL($category['name']);
    $categoryLink      = URL::createLink('frontend', 'book', 'list', ['category_id' => $category['id']],"$nameURL-$categoryID.html");
    $textClass = ($category['id'] == @$arrParams['category_id']) ? 'my-text-primary' : 'text-dark';
    $display = ($index > 5) ? false : true;
    $collapseCategory .= HelperFrontend::createCollapseItem($textClass, $categoryLink, $category['name'],$display);
}
?>

<div class="collection-collapse-block open">
    <h3 class="collapse-block-title">Danh mục</h3>
    <div class="collection-collapse-block-content">
        <div class="collection-brand-filter">
            <?= $collapseCategory ?>
            <div class="custom-control custom-checkbox collection-filter-checkbox pl-0 text-center">
                <span class="text-dark font-weight-bold" id="btn-view-more">Xem thêm</span>
            </div>
        </div>
    </div>
</div>