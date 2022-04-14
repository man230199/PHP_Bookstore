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

    foreach ($items as $item) {
        $editLink       = URL::createLink($arrParams['module'], $arrParams['controller'], 'form', ['id' => $item['id']]);
        $status = ($item['status'] == 'active') ? 'btn btn-success' : 'btn btn-danger';
        $linkStatus = URL::createLink($arrParams['module'], $arrParams['controller'], 'changeStatus', [
            'id'        => $item['id'],
            'status'    => $item['status']
        ]);
        $date = $item['date'];
        $linkDelete =  URL::createLink($arrParams['module'], $arrParams['controller'], 'delete', [
            'delete_id'    => $item['id'],
        ]);
        $username = $item['username'];
        if (isset($_GET['search_value'])) {
            $searchValue    = $_GET['search_value'];
            $username           = HelperBackend::highlight($searchValue, $item['username']);
        }
        $listItems .= ' 
            <tr>
                <td><input type="checkbox" value=' . $item['id'] . ' name="cid[]"></td>
                <td>' . $item['id'] . '</td>
                <td>' . $username . '</td>
                <td>' . $date . '</td>
                <td>
                    <a href="' . $linkStatus . '" class="' . $status . ' rounded-circle btn-sm"><i class="fas fa-check"></i></a>
                </td>
                <td>
                    <a href="' . $editLink . '" class="btn btn-info btn-sm rounded-circle"><i class="fas fa-pen"></i></a>
                    <a href="' . $linkDelete . '" class="btn btn-danger btn-sm rounded-circle btn-delete" ><i class="fas fa-trash "></i></a>
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
                                    <form method="get" name="group_acp_form" id="group_acp">
                                    
                                        <?php echo $btnStatusFilter; ?>
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
                                            <th>Username</th>
                                            <th>Date</th>
                                            <th>Status</th>
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