<?php 
    $featureBooks = $this->featureBook;
    $featureBookXhtml = '';
    foreach($featureBooks as $item) {
        $cartLink = URL::createLink($this->arrParam['module'], 'user', 'ajaxChangeQuantity', ['book_id' => $item['id'],'quantity' => 1,'price' => $item['price']]);
        $quickviewLink = URL::createLink($this->arrParam['module'], 'index', 'ajaxShowContent', ['id' => $item['id']]);
        $featureBookXhtml .= HelperFrontend::showItemInfo($this->arrParam['module'],'book',$item,$this->_dirImg,'book',50,false,$cartLink,$quickviewLink);
    }
?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="product-4 product-m no-arrow">
                <?= $featureBookXhtml; ?>
            </div>
        </div>
    </div>
</div>