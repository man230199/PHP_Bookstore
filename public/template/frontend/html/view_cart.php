<?php 
    $cart = Session::get('cart');
    $cartLink = URL::createLink('frontend','user','cart',null,'cart.html');
    $totalItems = 0;
    if(!empty($cart)) {
        if(isset($_GET['task']) && $_GET['task'] == 'removeItem') {
            unset($cart['quantity'][$_GET['book_id']]);
            unset($cart['price'][$_GET['book_id']]);
        }
       $totalItems = array_sum($cart['quantity']);
    }

?>

<li class="onhover-div mobile-cart">
    <div>
        <a href="<?=$cartLink?>" id="cart" class="position-relative">
            <img src="<?= $this->_dirImg ?>/cart.png" class="img-fluid blur-up lazyload" alt="cart">
            <i class="ti-shopping-cart"></i>
            <span class="badge badge-warning" id="total-quantity"><?=$totalItems?></span>
        </a>
    </div>
</li>