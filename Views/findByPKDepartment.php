<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 5:34 PM
 */
?>
<?php if (isset($message) && !empty($message)): ?>
    <div class="alert alert-light" role="alert">
        <?= $message ?>
    </div>
<?php endif; ?>
<?php if (isset($department) && !empty($department)): ?>
    <div class="card">
        <div class="card-body">
            <h5 class="text-muted">отдел</h5>
            <h4 class="card-title"><?= $department['departmentName'] ?></h4>
            <div class="btn-group" role="group">
                <a class="btn btn-primary" href="/CRUDv2/department/<?= $department['idDepartment'] ?>?action=ShowProgrammerUpdateFormByPK" role="button">Перевести
                    программиста в отдел</a>
                <a class="btn btn-warning"
                   href="/CRUDv2/department/<?= $department['idDepartment'] ?>?action=ShowCurrentRowUpdateFormByPK"
                   role="button">Изменить</a>
                <a class="btn btn-danger"
                   href="/CRUDv2/department/<?= $department['idDepartment'] ?>?action=DeleteByPK"
                   role="button">Удалить</a>
            </div>
        </div>
        <?php foreach ($department['fDataArray'] as $key => $value)
            \CRUD\Controllers\BasicController::constuctView($department['fDataArray'][$key]); ?>
    </div>
<?php endif; ?>






