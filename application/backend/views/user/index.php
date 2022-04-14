<?php
$items = $this->items;
$itemStatusCount = $this->itemStatusCount;
$arrParams = $this->arrParams;
$btnStatusFilter = HelperBackend::createStatusFilter($arrParams['module'], $arrParams['controller'], $itemStatusCount, @$arrParams['currentStatus'], @$arrParams['search_value']);

$listItems = '';
$searchBtn = HelperBackend::createButton('submit', 'btn btn-info', 'Search');

$applyLink = URL::createLink($arrParams['module'], $arrParams['controller'], 'new_action');

$indexLink = URL::createLink($arrParams['module'], $arrParams['controller'], 'index');
$addLink   = URL::createLink($arrParams['module'], $arrParams['controller'], 'form');
$groupValue  = [];

foreach ($items as $item) {
    $changeGroupLink = URL::createLink($arrParams['module'], $arrParams['controller'], 'changeGroup', ['id' => $item['id'], 'group_id' => 'new_value']);
    $inputGroup     = HelperBackend::cmsSelectbox('group_id', $this->groupValues, $item['group_id'], $changeGroupLink);
    $editLink       = URL::createLink($arrParams['module'], $arrParams['controller'], 'form', ['id' => $item['id']]);
    $changePasswordLink       = URL::createLink($arrParams['module'], $arrParams['controller'], 'changePassword', ['id' => $item['id']]);
    $status         = ($item['status'] == 'active') ? 'btn btn-success' : 'btn btn-danger';
    $iconStatus = ($item['status'] == 'active') ? 'fa-check' : 'fa-minus';
    $linkStatus     = URL::createLink($arrParams['module'], $arrParams['controller'], 'changeStatus', [
        'id'        => $item['id'],
        'status'    => $item['status']
    ]);
    $btnStatus = HelperBackend::createBtnAjaxAttr($linkStatus,$status,$iconStatus);

    $linkDelete =  URL::createLink($arrParams['module'], $arrParams['controller'], 'delete', [
        'delete_id'    => $item['id'],
    ]);

    $created        = HelperBackend::itemHistory('clock', $item['created']);
    $created_by     = HelperBackend::itemHistory('user', $item['created_by']);
    $modified       = HelperBackend::itemHistory('clock', $item['modified']);
    $modified_by    = HelperBackend::itemHistory('user', $item['modified_by']);
    $changePWButton = HelperBackend::actionButton($changePasswordLink, 'btn-secondary', 'fa-key', false);
    $editButton     = HelperBackend::actionButton($editLink, 'btn-info', 'fa-pen', false);
    $deleteButton   = HelperBackend::actionButton($linkDelete, 'btn-danger', 'fa-trash', true);
    $username       = $item['username'];
    if (isset($_GET['search_value'])) {
        $searchValue    = $_GET['search_value'];
        $username           = HelperBackend::highlight($searchValue, $item['username']);
    }
    $listItems .= ' 
            <tr>
                <td><input type="checkbox" value=' . $item['id'] . ' name="cid[]"></td>
                <td>' . $item['id'] . '</td>
                <td style="text-align:left;width:25%">
                    UserName: ' . $username . '<br>
                    Email: ' . $item['email'] . '<br>
                </td>
                
                <td class="position-relative">' . $inputGroup . '</td>
                <td class="position-relative">' . $btnStatus . '</td>
                <td>
                    ' . $created_by . ' 
                    ' . $created . '
                </td>
                <td>
                    ' . $modified_by . ' 
                    ' . $modified . '
                </td>
                <td>
                ' . $changePWButton . ' 
                ' . $editButton . '
                ' . $deleteButton . '
                </td>
            </tr>';
}


$selected = 'selected = "selected"';

?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Search & Filter -->
                <div class="card card-outline card-info">
                    <?php include 'elements/filter-search.php' ?>

                    <!-- /.card-body -->
                </div>
                <!-- List -->
                <div class="card card-outline card-info">

                    <div class="card-body">
                        <?php include 'elements/bulk-action.php' ?>
                        <?php include 'elements/list-items.php' ?>

                    </div>
                    <?php
                    echo $this->pagination->showPagination(URL::createLink($arrParams['module'], $arrParams['controller'], 'index'));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>