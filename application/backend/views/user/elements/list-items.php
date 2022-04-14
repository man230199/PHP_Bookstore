<form action="" id="table-form" method="POST">
    <div class="table-responsive">
        <table class="table align-middle text-center table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Group</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Modified</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php echo $listItems; ?>
            </tbody>
            <?php
            //echo $this->pagination->showPagination(URL::createLink($arrParams['module'], $arrParams['controller'], 'index'));
            ?>
        </table>
    </div>
</form>