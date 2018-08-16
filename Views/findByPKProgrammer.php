<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 5:36 PM
 */
?>
<?php if (isset($message) && !empty($message)): ?>
    <div class="alert alert-light" role="alert">
        <?= $message ?>
    </div>
<?php endif; ?>
<?php if (isset($programmer) && !empty($programmer)): ?>
    <div class="card">
        <div class="card-body">
            <h5 class="text-muted">программист</h5>
            <h4 class="card-title"><?= $programmer['programmerSurname'] ?> <?= $programmer['programmerName'] ?> <?= $programmer['programmerPatronymic'] ?></h4>
            <h5 class="card-text">с индивидуальным номером <?= $programmer['programmerToken'] ?></h5>
            <?php if ($programmer['fIdDepartment']): ?>
                <h5 class="card-link">числится в отделе "<a
                            href="/CRUDv2/department/<?= $programmer['fIdDepartment'] ?>?action=FindByPK">
                        <ins><?= $programmer['departmentName'] ?></ins>
                    </a>"
                </h5>
            <?php endif; ?>
            <div class="btn-group" role="group">
                <a class="btn btn-primary"
                   href="/CRUDv2/programmer/<?= $programmer['idProgrammer'] ?>?action=ShowAccountingAddFormByPK"
                   role="button">Учесть навык программиста</a>
                <a class="btn btn-warning"
                   href="/CRUDv2/programmer/<?= $programmer['idProgrammer'] ?>?action=ShowCurrentRowUpdateFormByPK"
                   role="button">Изменить</a>
                <a class="btn btn-danger"
                   href="/CRUDv2/programmer/<?= $programmer['idProgrammer'] ?>?action=DeleteByPK"
                   role="button">Удалить</a>
            </div>
        </div>
        <?php foreach ($programmer['fDataArray'] as $key => $value)
            \CRUD\Controllers\BasicController::constuctView($programmer['fDataArray'][$key]); ?>
    </div>
<?php endif; ?>


