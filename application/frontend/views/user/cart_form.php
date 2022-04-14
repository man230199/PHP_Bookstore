<?= HelperFrontend::notification(@$this->message);?>
<form action="<?= $buyLink ?>" method="POST" name="admin-form" id="admin-form">
    <input type="hidden" name="module" value="frontend">
    <input type="hidden" name="controller" value="user">
    <input type="hidden" name="action" value="buy">
    <section class="cart-section section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table cart-table table-responsive-xs">
                        <thead>
                            <tr class="table-head">
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Tên sách</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Số Lượng</th>
                                <th scope="col"></th>
                                <th scope="col">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?= $cartItemsXhtml; ?>
                        </tbody>

                    </table>
                    <table class="table cart-table table-responsive-md">
                        <tfoot>
                            <tr>
                                <td>Tổng :</td>
                                <td>
                                    <h2 class="text-lowercase" id="total-price"><?= number_format($total) . ' đ' ?></h2>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row cart-buttons">
                <div class="col-6"><a href="<?= $continueLink ?>" class="btn btn-solid">Tiếp tục mua sắm</a></div>
                <div class="col-6">
                    <a href="" onclick="javascript:submitForm(<?= $buyLink ?>)"><button type="submit" class="btn btn-solid">Đặt hàng</button></a>
                </div>
            </div>


        </div>
    </section>
</form>