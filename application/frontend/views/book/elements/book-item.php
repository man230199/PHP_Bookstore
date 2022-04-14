<?php
$bookItem = '';
foreach ($listItems as $item) {
  

    $cartLink = URL::createLink($this->arrParam['module'], 'user', 'ajaxChangeQuantity', ['book_id' => $item['id'], 'quantity' => 1, 'price' => $item['price']]);
    $quickviewLink = URL::createLink($this->arrParam['module'], 'book', 'ajaxShowContent', ['id' => $item['id']]);
    $productBox = HelperFrontend::showItemInfo($this->arrParam['module'], $this->arrParam['controller'], $item, $this->_dirImg, 'book', 50, false, $cartLink, $quickviewLink);
    $bookItem .= sprintf('
        <div class="col-xl-3 col-6 col-grid-box">
            %s
        </div>',  $productBox);
}

echo $bookItem;
