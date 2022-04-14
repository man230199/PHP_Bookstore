<?php 
foreach ($bookByFeatureCategory as $book) {

    if ($book['category_id'] == $category['id']) {

        $cartLink = URL::createLink($this->arrParam['module'], 'user', 'ajaxChangeQuantity', ['book_id' => $book['id'],'quantity' => 1,'price' => $book['price']]);
        $quickviewLink = URL::createLink($this->arrParam['module'], 'index', 'ajaxShowContent', ['id' => $book['id']]);
        $bookByCategoriesXhtml .= HelperFrontend::showItemInfo($this->arrParam['module'],'book',$book,$this->_dirImg,'book',50,false,$cartLink,$quickviewLink);
    }
}