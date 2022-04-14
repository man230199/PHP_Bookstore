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
                    <input type="hidden" name="module" value="<?= $arrParams['module'] ?>">
                    <input type="hidden" name="controller" value="<?= $arrParams['controller'] ?>">
                    <input type="hidden" name="action" value="index">

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