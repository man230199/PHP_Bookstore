<?php
$listItems = $this->items;
$bookLink = URL::createLink($this->arrParam['module'], $this->arrParam['controller'], 'list')
?>

<section class="section-b-space j-box ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <!-- side-bar colleps block stat -->
                    <div class="collection-filter-block">
                        <!-- brand filter start -->
                        <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                        <?php include 'elements/category-collapse.php'; ?>
                    </div>


                    <?php include 'elements/feature-book.php'; ?>
                    <!-- silde-bar colleps block end here -->
                </div>
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn">
                                                    <span class="filter-btn btn btn-theme"><i class="fa fa-filter" aria-hidden="true"></i> Filter</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="product-filter-content">
                                                    <div class="collection-view">
                                                        <ul>
                                                            <li><i class="fa fa-th grid-layout-view"></i></li>
                                                            <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div class="collection-grid-view">
                                                        <ul>
                                                            <li class="my-layout-view" data-number="2">
                                                                <img src="<?= $this->_dirImg ?>/images/icon/2.png" alt="" class="product-2-layout-view">
                                                            </li>
                                                            <li class="my-layout-view" data-number="3">
                                                                <img src="images/icon/3.png" alt="" class="product-3-layout-view">
                                                            </li>
                                                            <li class="my-layout-view active" data-number="4">
                                                                <img src="images/icon/4.png" alt="" class="product-4-layout-view">
                                                            </li>
                                                            <li class="my-layout-view" data-number="6">
                                                                <img src="images/icon/6.png" alt="" class="product-6-layout-view">
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-page-filter">
                                                        <form action="" id="sort-form" method="GET">
                                                            <input type="hidden" name="module" value="frontend">
                                                            <input type="hidden" name="controller" value="book">
                                                            <input type="hidden" name="action" value="list">
                                                            <select id="sort" name="sort">
                                                                <option value="default" selected> - Sắp xếp - </option>
                                                                <option value="asc">Giá tăng dần</option>
                                                                <option value="desc">Giá giảm dần</option>
                                                                <!-- <option value="latest">Mới nhất</option> -->
                                                            </select>
                                                        </form>
                                                    </div>
                                                    <?php include 'elements/modal.php' ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-wrapper-grid" id="my-product-list">
                                        <div class="row margin-res">
                                            <?php include 'elements/book-item.php'; ?>
                                        </div>
                                    </div>
                                    <div class="product-pagination">
                                        <div class="theme-paggination-block">
                                            <div class="container-fluid p-0">
                                                <div class="row">
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <nav aria-label="Page navigation">
                                                            <nav>
                                                                <ul class="pagination">
                                                                    <?php
                                                                    echo $this->pagination->FrontendPagination(URL::createLink($arrParams['module'], $arrParams['controller'], 'index'));
                                                                    ?>
                                                                </ul>
                                                            </nav>
                                                        </nav>
                                                    </div>
                                                    <!-- <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <div class="product-search-count-bottom">
                                                            <h5>Showing Items 1-12 of 55 Result</h5>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>