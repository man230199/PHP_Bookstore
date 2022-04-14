<?php
$featureBooks = $this->featureBooks;
$featureBooksXhtml = '';
$count = count($featureBooks);
$index = 0;
/* if ($index == 0 || $index == $count / 2)  $featureBooksXhtml .= '<div>';
if ($index == $count - 1   || $index == $count) $featureBooksXhtml .= '</div>'; */
foreach ($featureBooks as $key => $item) {
    if($key == 0) $featureBooksXhtml .= '<div>';  
    $featureBooksXhtml .= HelperFrontend::showFeaturedItem($this->arrParam, $item, $this->_dirImg, 'book');
    if($key == $count / 2 - 1) $featureBooksXhtml .= '</div><div>';
    
    if($key == $count - 1) $featureBooksXhtml .= '</div>';
    $index++;
}
?>
<div class="theme-card">
    <h5 class="title-border">Sách nổi bật</h5>
    <div class="offer-slider slide-1">
    
        <?= $featureBooksXhtml ?>


    </div>
</div>