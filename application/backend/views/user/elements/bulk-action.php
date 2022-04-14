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