<?php
$xhtml = '';
if (!empty($this->Items)) {
    foreach ($this->Items as $key => $value) {
        $cartId             = $value['id'];
        $date               = date("H:i d/m/Y", strtotime($value['date']));
        $arrBookID          = json_decode($value['books']);
        $arrPrice           = json_decode($value['prices']);
        $arrName            = json_decode($value['names']);
        $arrQuantity        = json_decode($value['quantities']);
        $arrPicture         = json_decode($value['pictures']);
        $tableContent       = '';
        $totalPrice         = 0;
        if($value['status'] == 'active') {
            $class = 'success';
            $icon  = 'fa-check';
            $status = 'Đã xác thực';
        }else {
            $class = 'danger';
            $icon  = 'fa-times';
            $status = 'Chưa xác thực';
        }
        $xhtml .= '
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <button style="text-transform: none;" class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#' . $cartId . '">Mã đơn hàng: ' . $cartId . '</button>&nbsp;&nbsp;Thời gian: ' . $date . '
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tình trạng: '.$status.'&nbsp;&nbsp
                    <a style="float:right;margin-right:50px;margin-top:10px" href="#" class="btn btn-'.$class.' btn-sm"><i class="fa '.$icon.'"></i></a>
                </h5>
            </div>
            <div id="' . $cartId . '" class="collapse" data-parent="#accordionExample" style="">
                <div class="card-body table-responsive">
                    <table class="table btn-table">
                        <thead>
                            <tr>
                                <td>Hình ảnh</td>
                                <td>Tên sách</td>
                                <td>Giá</td>
                                <td>Số lượng</td>
                                <td>Thành tiền</td>
                            </tr>
                        </thead>
                        <tbody>';
        foreach ($arrBookID as $keyB => $valueB) {

            $linkDetail        = URL::createLink('frontend', 'book', 'bookInfo', ['book_id' => $valueB]);
            $picturePath        = UPLOAD_URL . DS . 'book' . DS  . $arrPicture[$keyB];
            $totalPrice        += $arrQuantity[$keyB] * $arrPrice[$keyB];
            $xhtml .= '
            <tr>
                <td><a href="' . $linkDetail . '"><img src="' . $picturePath . '" alt="' . $arrName[$keyB] . '" style="width: 80px"></a></td>
                <td style="min-width: 200px">' . $arrName[$keyB] . '</td>
                <td style="min-width: 100px">' . number_format($arrPrice[$keyB]) . ' đ</td>
                <td>' . $arrQuantity[$keyB] . '</td>
                <td style="min-width: 150px">' . number_format($arrPrice[$keyB] * $arrQuantity[$keyB]) . ' đ</td>
            </tr>
            <tr></tr>';
        }
        $xhtml .= '</tbody>
                    <tfoot>
                        <tr class="my-text-primary font-weight-bold">
                            <td colspan="4" class="text-right">Tổng: </td>
                            <td>' . number_format($totalPrice) . ' đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>';
    }
} else {
    $xhtml = '<h3>Chưa có đơn hàng nào!</h3>';
}

?>

<section class="faq-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar">
                    <a class="popup-btn">Menu</a>
                </div>
                <h3 class="d-lg-none">Lịch sử mua hàng</h3>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> Ẩn</span></div>
                    <div class="block-content">
                        <ul>
                            <li class=""><a href="account-form.html">Thông tin tài khoản</a></li>
                            <li class=""><a href="change-password.html">Thay đổi mật khẩu</a></li>
                            <li class="active"><a href="order-history.html">Lịch sử mua hàng</a></li>
                            <li class=""><a href="index.html">Đăng xuất</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="accordion theme-accordion" id="accordionExample">
                    <div class="accordion theme-accordion" id="accordionExample">
                        <?= $xhtml ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>