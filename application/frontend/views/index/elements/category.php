<?php
$featureCategories = $this->featureCategory;
$bookByFeatureCategory = $this->bookByFeatureCategory;
$featureCategoriesXhtml = '';
$bookByCategoriesXhtml = '';
$count = 0;

foreach ($featureCategories as $key => $category) {
    $class = ($key == 0)  ? 'current' : '';
    $activeTab = ($key == 0)  ? 'active default' : '';
    $featureCategoriesXhtml .= sprintf('
        <li class="%s" id="tab-category">
            <a href="tab-category-%s" name="tab-category" class="my-product-tab" data-category="%s">
           %s</a>
        </li>', $class, $category['id'], $category['id'], $category['name']);
    $bookByCategoriesXhtml .= sprintf('<div id="tab-category-%s" class="tab-content %s">
        <div class="no-slider row tab-content-inside">', $category['id'], $activeTab);

    include 'book_by_category.php';

    $categoryID = $category['id'];
    $cateNameURL = URL::filterURL($category['name']);
    $seeAllLink = URL::createLink('frontend', 'book', 'list', ['category_id' => $categoryID], "$cateNameURL-$categoryID.html");
    $bookByCategoriesXhtml .= sprintf(
        '</div>
        <div class="text-center"><a href="%s" class="btn btn-solid">Xem tất cả</a></div>
    </div>',
        $seeAllLink
    );
}


?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="theme-tab">
                <ul class="tabs tab-title">
                    <?= $featureCategoriesXhtml ?>

                </ul>
                <div class="tab-content-cls">
                    <?= $bookByCategoriesXhtml; ?>

                </div>
            </div>
        </div>
    </div>
</div>