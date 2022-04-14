<?php
$sliderItems = $this->sliderItems;
$sliderXhtml = '';
foreach ($sliderItems as $item) {
    $picture        = HelperFrontend::createSliderItem($bookLink, $this->_dirImg . DS . 'slider' . DS . $item['picture'], $item['name']);
    $sliderXhtml .= sprintf('<div>%s</div>', $picture);
}
?>

<section class="p-0 my-home-slider">
    <div class="slide-1 home-slider">
        <?= $sliderXhtml ?>
    </div>
</section>