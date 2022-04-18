<?php
$items = $this->Items;
$cartItemsXhtml = '';
$total    = 0;
$butButton = HelperFrontend::createBtn('btn btn-solid', 'submit', 'Đặt hàng');
$buyLink   = URL::createLink($this->arrParam['module'], $this->arrParam['controller'], 'buy');
$continueLink = URL::createLink($this->arrParam['module'], 'book', 'list',null,'book-list.html');

foreach ($items as $item) {
    $bookLink = URL::createLink($this->arrParam['module'], 'book', 'bookInfo', ['book_id' => $item['id']]);
    $quantityLink = URL::createLink($this->arrParam['module'], 'user', 'ajaxChangeQuantity', ['book_id' => $item['id'],'quantity' => 'new_value','price' => $item['price']]);
    $name     = $item['name'];
    $price    = $item['price'];
    $quantity = $item['quantity'];
    $totalPrice = number_format($item['totalprice']);
    $total      += $item['totalprice'];
    $picture  = HelperFrontend::showCartImage($bookLink, UPLOAD_URL . DS . 'book' . DS . $item['picture'], $name);
    $quantityInput = HelperFrontend::createInput('form-control ajax-input-number', 'number', 'quantity', $quantity,$quantityLink,null,null,1);
    // $removeItemLink = URL::createLink($this->arrParam['module'],$this->arrParam['controller'],'cart',['book_id' => $item['id'],'task' => 'removeItem']);
    $removeItemLink = URL::createLink($this->arrParam['module'],$this->arrParam['controller'],'removeCartItem',['book_id' => $item['id']]);
    $hiddenID       = HelperFrontend::createInput('', 'hidden', 'form[book_id][]', $item['id']);
    $hiddenName     = HelperFrontend::createInput('', 'hidden', 'form[name][]', $name);
    $hiddenPrice    = HelperFrontend::createInput('', 'hidden', 'form[price][]', $price,'','data-price=' .$item['id']);
    $hiddenQuantity = HelperFrontend::createInput('', 'hidden', 'form[quantity][]', $quantity,'','data-quantity=' . $item['id']);
    $hiddenPicture  = HelperFrontend::createInput('', 'hidden', 'form[picture][]', $item['picture']);
    $hiddenInputs   = $hiddenID . $hiddenName . $hiddenPrice . $hiddenQuantity . $hiddenPicture;
    $cartItemsXhtml .= sprintf('
        <tr>
            <td>
                %s
            </td>
            <td><a href="%s">%s</a>
                <div class="mobile-cart-content row">
                    <div class="col-xs-3">
                        <div class="qty-box">
                            <div class="input-group">
                            %s
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <h2 class="td-color text-lowercase">%s đ</h2>
                    </div>
                    <div class="col-xs-3">
                        <h2 class="td-color text-lowercase">
                            <a href="#" class="icon"><i class="ti-close"></i></a>
                        </h2>
                    </div>
                </div>
            </td>
            <td>
                <h2 class="text-lowercase">%s đ</h2>
            </td>
            <td>
                <div class="qty-box">
                    <div class="input-group position-relative" >
                        %s
                    </div>
                </div>
            </td>
            <td><a href="" class="icon"><i class="ti-close" id="remove-item" data-url="%s"></i></a></td>
            <td>
                <h2 class="td-color text-lowercase" id="item_price_%s">%s đ</h2>
            </td>
        </tr>
        %s
        ', $picture, $bookLink, $name, $quantityInput, number_format($price), number_format($price), $quantityInput,$removeItemLink,$item['id'], $totalPrice, $hiddenInputs);
}
?>

<?php 
    $cart = Session::get('cart');
    if(empty(Session::get('cart')) || (empty($_SESSION['cart']['quantity']) && empty($_SESSION['cart']['price']))) {
        echo '<h3 style="text-align:center">Không có sản phẩm trong giỏ hàng</h3>';
        
    }else {
        include 'cart_form.php';
    }
   
?>
