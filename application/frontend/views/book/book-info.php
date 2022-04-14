<?php
$infoItem = $this->item;
$cartLink = URL::createLink('frontend','user','ajaxChangeQuantity',['book_id' => $infoItem['id'],'price' => $infoItem['price'],'quantity' => 'new_quantity']);

?>

<section class="section-b-space">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-sm-12 col-xs-12">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="filter-main-btn mb-2"><span class="filter-btn"><i class="fa fa-filter" aria-hidden="true"></i> filter</span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-xl-4">
                                <div class="product-slick">
                                    <div><img src="<?php echo UPLOAD_URL . DS . 'book' . DS . $infoItem['picture'] ?>" alt="" class="img-fluid w-100 blur-up lazyload image_zoom_cls-0"></div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-xl-8 rtl-text">
                                <div class="product-right">
                                    <h2 class="mb-2"><?= $infoItem['name'] ?></h2>
                                    <h4><del><?=  number_format($infoItem['price']) ?> đ</del><span> -<?= $infoItem['name'] ?>%</span></h4>
                                    <h3><?= number_format($infoItem['price']) ?> đ</h3>
                                    <div class="product-description border-product">
                                        <h6 class="product-title">Số lượng</h6>
                                        <div class="qty-box">
                                            <div class="input-group">
                                                <span class="input-group-prepend">
                                                    <button type="button" class="btn quantity-left-minus" data-type="minus" data-field="">
                                                        <i class="ti-angle-left"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="quantity" class="form-control input-number" value="1" data-url="<?=$buyLink?>">
                                                <span class="input-group-prepend">
                                                    <button type="button" class="btn quantity-right-plus" data-type="plus" data-field="">
                                                        <i class="ti-angle-right"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-buttons">
                                        <a href="#" data-url="<?= $cartLink?>" id="put-to-cart" class="btn btn-solid ml-0"><i class="fa fa-cart-plus"></i> Chọn mua</a>
                                    </div>
                                    <div class="border-product">
                                        <?=substr_replace($infoItem['short_description'],'[...]',500) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="tab-product m-0">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12 col-lg-12">
                                    <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-toggle="tab" href="#top-home" role="tab" aria-selected="true">Mô tả sản phẩm</a>
                                            <div class="material-border"></div>
                                        </li>
                                    </ul>
                                    <div class="tab-content nav-material" id="top-tabContent" style="margin-top:20px">
                                        <div class="tab-pane fade show active ckeditor-content" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                                            <?= $infoItem['description']?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-sm-3 collection-filter">
                    <div class="collection-filter-block">
                        <div class="collection-mobile-back">
                            <span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span>
                        </div>
                        <?php include 'elements/service.php'?>
                    </div>
                    
                    <?php include 'elements/feature-in-bookinfo.php'?>

               
                </div>
            </div>
            <?php include 'elements/related-book.php'?>
        </div>
    </div>
</section>