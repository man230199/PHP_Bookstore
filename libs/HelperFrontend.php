<?php
class HelperFrontend
{
    public static function modalContent() {
        $xhtml = '';
        return $xhtml;
    }

    public static function createInput($class, $type, $name, $value = null, $dataURL = null,$data = null, $dataValue = null, $min = null, $max = null,  $disabled = false)
    {
        $disabled = ($disabled == true) ? 'disabled' : '';
        $xhtml = '<input class="' . $class . '" data-url="' . $dataURL . '" '.$data.' data-value="' . $dataValue . '" type="' . $type . '" name="' . $name . '" value="' . $value . '" placeholder="' . $value . '" ' . $disabled . ' min="' . $min . '" max="' . $max . '" >';
        return $xhtml;
    }

    public static function createBtn($class, $type, $name, $value = null)
    {
        $btnName = ucfirst($name);
        $xhtml = '<button class="' . $class . '" type="' . $type . '" name="' . $name . '" value="' . $value . '">' . $btnName . '</button> ';
        return $xhtml;
    }
    public static function showCartImage($link, $picture, $alt)
    {
        $xhtml = sprintf('<a href="%s"><img src="%s" alt="%s"></a>', $link, $picture, $alt);
        return $xhtml;
    }

    public static function showFeaturedItem($arrParam, $item, $dirImg, $dirImgName)
    {
        $xhtml = '';
        $cateNameURL = URL::filterURL($item['category_name']);
        $cateIDURL  = $item['category_id'];
        $bookNameURL =  URL::filterURL($item['name']);
        $bookIDURL = $item['id'];

        $bookLink       = URL::createLink($arrParam['module'], $arrParam['controller'], 'bookInfo', ['book_id' => $item['id']],"$cateNameURL/$bookNameURL-$cateIDURL-$bookIDURL.html");
        $picture        = self::createImageFeature($bookLink, UPLOAD_URL . DS . $dirImgName . DS . $item['picture'], $item['name']);
        $title          = self::createTitle($bookLink, $item['name']);
        $price          = number_format($item['price']);
        $xhtml .= sprintf('
        <div class="media">
           %s 
            <div class="media-body align-self-center">
                <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                %s
                <h4 class="text-lowercase">%s đ</h4>
            </div>
        </div>', $picture, $title, $price);
        return $xhtml;
    }
    public static function showItemInfo($module, $controller, $item, $dirImg, $dirImgName, $character = 50, $showDescription = true, $cartURL = null, $quickviewURL = null)
    {
        $xhtml = '';
        //URL params for users
        $categoryID = $item['category_id'];
        $bookID     = $item['id'];
        $cateNameURL = URL::filterURL(@$item['category_name']);
        $bookNameURL = URL::filterURL($item['name']);

        $bookLink       = URL::createLink($module, $controller, 'bookInfo', ['book_id' => $item['id'],'category_id' => $categoryID],"$cateNameURL/$bookNameURL-$categoryID-$bookID.html");
        
        $bookName       = $item['name'];
        $saleOff        = ($item['sale_off'] > 0) ? self::createSpanItem('lable4 badge badge-danger', '-' . $item['sale_off'] . '%') : null;
        $picture        = self::createImageItem($bookLink, UPLOAD_URL . DS . $dirImgName . DS . $item['picture'], $item['name']);
        $shoppingCart   = self::createIconItem('javaScript:void(0)', 'Add to cart', 'ti-shopping-cart', $cartURL);
        $quickView      = self::createIconItem('javaScript:void(0)', 'Quick View', 'ti-search', $quickviewURL, 'modal', '#quick-view');
        $title          = self::createTitle($bookLink, $item['name'], $character);
        $description    = ($showDescription == true) ? substr_replace($item['description'], '...', 100) : '';
        $price          = number_format($item['price']);
        $salePrice      = $price;
        $xhtml .= sprintf('
            <div class="product-box">
                <div class="img-wrapper">
                    <div class="lable-block">
                        %s
                    </div>
                    <div class="front">
                        %s
                    </div>
                    <div class="cart-info cart-wrap">
                       %s %s
                    </div>
                </div>
                <div class="product-detail">
                    <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    %s
                    <p>%s</p>
                    <h4 class="text-lowercase">%s đ <del>%s đ</del></h4>
                </div>
            </div>', $saleOff, $picture, $shoppingCart, $quickView, $title, $description, $salePrice, $price);

        return $xhtml;
    }
    public static function createMenuItem($link, $name, $child = null)
    {
        $xhtml = sprintf('<li><a href="%s">%s</a>%s</li>', $link, $name, $child);
        return $xhtml;
    }

    public static function createCollapseItem($textClass, $link, $name, $display = true)
    {
        $display = ($display == false) ? 'display:none' : '';
        $xhtml = sprintf(' 
        <div style="%s" class="custom-control custom-checkbox collection-filter-checkbox pl-0 category-item">
            <a class="%s" href="%s">%s</a>
        </div>', $display, $textClass, $link, $name);
        return $xhtml;
    }

    public static function createSpanItem($class, $name)
    {
        $xhtml = sprintf('<span class="%s"> %s</span>', $class, $name);
        return $xhtml;
    }

    public static function createImageFeature($link, $image, $alt)
    {
        $xhtml = sprintf(
            '<a href="%s">
                <img src="%s" class="img-fluid blur-up lazyload" alt="%s">
            </a>',
            $link,
            $image,
            $alt
        );
        return $xhtml;
    }

    public static function createImageItem($link, $image, $alt, $class = null)
    {
        $class = (!$class) ? $class : '';
        $xhtml = sprintf(
            '<a href="%s" class="%s">
                <img src="%s" class="img-fluid blur-up lazyload bg-img" alt="%s">
            </a>',
            $link,
            $class,
            $image,
            $alt
        );
        return $xhtml;
    }

    public static function createSliderItem($link, $image, $alt)
    {
        $xhtml = sprintf(
            '<a href="%s" class="home text-center">
                <img src="%s" alt="%s" class="bg-img blur-up lazyload">
            </a>',
            $link,
            $image,
            $alt
        );
        return $xhtml;
    }

    public static function createIconItem($link, $title, $icon, $dataURL = null, $dataToggle = null, $dataTarget = null)
    {
        $xhtml = sprintf(
            '<a href="%s" title="%s">
                <i class="%s" data-toggle="%s" data-target="%s" data-url="%s" id="item-icon-action"></i>
            </a>',
            $link,
            $title,
            $icon,
            $dataToggle,
            $dataTarget,
            $dataURL
        );
        return $xhtml;
    }

    public static function createTitle($link, $title, $character = 50)
    {
        if (strlen($title) > $character) {
            $title = substr_replace($title, '...', $character);
        }

        $xhtml = sprintf(
            '<a href="%s" title="%s">
                <h6>%s</h6>
            </a>',
            $link,
            $title,
            $title
        );
        return $xhtml;
    }

    public static function notification($message, $type = true)
    {
        $xhtml = '';
        $message = @Session::get('message');
        $class = ($type) ? 'success' : 'danger';
        if ($message) {
            $xhtml .= '
                <div class="alert alert-' . $class . ' alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    ' . $message . '      
                </div>';
            Session::delete('message');
        }
        return $xhtml;
    }
}
