<?php
$items = $this->items;
$itemStatusCount = $this->itemStatusCount;
$arrParams = $this->arrParams;
$btnStatusFilter = HelperBackend::createStatusFilter($arrParams['module'], $arrParams['controller'], $itemStatusCount, @$arrParams['currentStatus'], @$arrParams['search_value']);

$listItems = '';
$searchBtn = HelperBackend::createButton('submit', 'btn btn-info', 'Search');

$applyLink = URL::createLink($arrParams['module'], $arrParams['controller'], 'new_action');
$indexLink = URL::createLink($arrParams['module'], $arrParams['controller'], 'index');
$addLink   = URL::createLink($arrParams['module'], $arrParams['controller'], 'form',null,"them-moi-nguoi-dung.html");

$specialValue       = ['default' => '- Select special -', 0 => 'No', 1 => 'Yes'];
$selectSpecial      =  HelperBackend::cmsSelectboxNumeric("special", $specialValue, @$arrParams['special'], 'margin-left:5px;width:170px');

$listCategories = $this->listCategories;
$categoryArray = ['default' => '- Select Category -'];
foreach ($listCategories as $category) {
    $categoryArray[$category['id']] = $category['name'];
}

$selectCategory   = HelperBackend::cmsSelectbox("filter_category_id", $categoryArray, @$arrParams['filter_category_id'], '', 'margin-left:5px;width:170px');

foreach ($items as $item) {
    $id = $item['id'];
    $editLink       = URL::createLink($arrParams['module'], $arrParams['controller'], 'form', ['id' => $item['id']],"chinh-sua-nguoi-dung/$id.html");
    $status = ($item['status'] == 'active') ? 'btn btn-success' : 'btn btn-danger';
    $iconStatus = ($item['status'] == 'active') ? 'fa-check' : 'fa-minus';
    $special = ($item['special'] == 1) ? 'btn btn-success' : 'btn btn-danger';
    $iconSpecial = ($item['special'] == 1) ? 'fa-check' : 'fa-minus';
    $name = $item['name'];
    $picture = UPLOAD_URL . 'book' . DS . $item['picture'];

    $changeCategoryLink = URL::createLink($arrParams['module'], $arrParams['controller'], 'changeCategory', ['id' => $item['id'], 'category_id' => 'new_value']);

    $inputCategory     = HelperBackend::cmsSelectbox('category_id', $this->categoryValues, $item['category_id'], $changeCategoryLink);

    $orderingLink      = URL::createLink($arrParams['module'], $arrParams['controller'], 'changeOrdering', ['id' => $item['id'], 'ordering' => 'new_value']);
    $inputOrdering     = HelperBackend::createInput('number', "form[ordering]", $item['ordering'], false, $orderingLink);

    $linkSpecial = URL::createLink($arrParams['module'], $arrParams['controller'], 'changeSpecial', [
        'id'        => $item['id'],
        'special'   => $item['special']
    ]);
    $linkStatus = URL::createLink($arrParams['module'], $arrParams['controller'], 'changeStatus', [
        'id'        => $item['id'],
        'status'    => $item['status']
    ]);
    $linkDelete =  URL::createLink($arrParams['module'], $arrParams['controller'], 'delete', [
        'delete_id'    => $item['id'],
    ]);

    $btnStatus = HelperBackend::createBtnAjaxAttr($linkStatus, $status, $iconStatus);
    $btnSpecial = HelperBackend::createBtnAjaxAttr($linkSpecial, $special, $iconSpecial);

    $created        = HelperBackend::itemHistory('clock', $item['created']);
    $created_by     = HelperBackend::itemHistory('user', $item['created_by']);
    $modified       = HelperBackend::itemHistory('clock', $item['modified']);
    $modified_by    = HelperBackend::itemHistory('user', $item['modified_by']);
    $editButton     = HelperBackend::actionButton($editLink, 'btn-info', 'fa-pen', false);
    $deleteButton   = HelperBackend::actionButton($linkDelete, 'btn-danger', 'fa-trash', true);
    if (isset($_GET['search_value'])) {
        $searchValue    = $_GET['search_value'];
        $name           = HelperBackend::highlight($searchValue, $item['name']);
    }
    $listItems .= ' 
            <tr>
                <td><input type="checkbox" value=' . $item['id'] . ' name="cid[]"></td>
                <td>' . $item['id'] . '</td>
                <td>' . $name . '</td>
                <td><img src="' . $picture . '" heigth="150px" width="150px"/></td>
                <td>' . $item['price'] . '</td>
                <td>' . $item['sale_off'] . '</td>
                <td class="position-relative">' . $btnSpecial . '</td>
                <td width="10%" class="position-relative">' . $inputOrdering . ' </td>
                <td  class="position-relative">' . $inputCategory . ' </td>
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
                    <div class="card-header">
                        <h3 class="card-title">Search & Filter</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row justify-content-between align-items-center">
                                <div class="area-filter-status mb-2">
                                    <form method="get" name="filter_search" id="filter_search">
                                        <input type="hidden" name="module" value="<?= $arrParams['module'] ?>">
                                        <input type="hidden" name="controller" value="<?= $arrParams['controller'] ?>">
                                        <input type="hidden" name="action" value="index">

                                        <?php echo $btnStatusFilter; ?>
                                        <?= $selectSpecial ?>

                                        <?= $selectCategory; ?>

                                    </form>
                                </div>
                                <div class="area-search mb-2">
                                    <form action="" method="GET">
                                        <div class="input-group">
                                            <input type="hidden" name="module" value="<?= $arrParams['module'] ?>">
                                            <input type="hidden" name="controller" value="<?= $arrParams['controller'] ?>">
                                            <input type="hidden" name="action" value="index">
                                            <input type="text" name="search_value" class="form-control" value="<?php echo @$arrParams['search_value'] ?>">
                                            <span class="input-group-append">
                                                <?php echo $searchBtn; ?>
                                                <a href="<?= $indexLink ?>" class="btn btn-danger">Clear</a>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- List -->
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">List</h3>

                        <?php
                        $message = Session::get('success');
                        $notify = HelperBackend::notification($message);
                        echo $notify;
                        ?>

                        <div class="card-tools">
                            <a href="#" class="btn btn-tool" data-card-widget="refresh">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row align-items-center justify-content-between mb-2">
                                <div>
                                    <div class="input-group">
                                        <select class="form-control custom-select" id="action-select">
                                            <option value="">Bulk Action</option>
                                            <option value="multiDelete">Delete</option>
                                            <option value="multiActive">Active</option>
                                            <option value="multiInactive">Inactive</option>
                                        </select>
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-info" data-url="<?= $applyLink ?>" id="apply-button">Apply</button>
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <a href="<?php echo $addLink; ?>" class="btn btn-info"><i class="fas fa-plus"></i> Add New</a>
                                </div>
                            </div>
                        </div>
                        <form action="" id="table-form" method="POST">
                            <div class="table-responsive">
                                <table class="table align-middle text-center table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox"></th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Picture</th>
                                            <th>Price</th>
                                            <th>Sale Off</th>
                                            <th>Special</th>
                                            <th>Ordering</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Modified</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php echo $listItems; ?>
                                    </tbody>

                                </table>
                                <div>
                                    <input type="hidden" name="filter_page" value="1">
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    echo $this->pagination->showPagination(URL::createLink($arrParams['module'], $arrParams['controller'], 'index'));
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>