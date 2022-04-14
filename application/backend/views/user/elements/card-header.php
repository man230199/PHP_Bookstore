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