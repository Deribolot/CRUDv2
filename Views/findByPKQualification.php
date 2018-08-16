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
<?php if (isset($qualification) && !empty($qualification)): ?>
    <div class="card">
        <div class="card-body">
            <h5 class="text-muted">навык</h5>
            <h4 class="card-title"><?= $qualification['qualificationName'] ?></h4>
            <div class="btn-group" role="group">
                <a class="btn btn-primary" role="button"
                   href="/CRUDv2/qualification/<?= $qualification['idQualification'] ?>?action=ShowAccountingAddFormByPK">Учесть
                    навык программисту</a>
                <a class="btn btn-warning"
                   href="/CRUDv2/qualification/<?= $qualification['idQualification'] ?>?action=ShowCurrentRowUpdateFormByPK"
                   role="button">Изменить</a>
                <a class="btn btn-danger"
                   href="/CRUDv2/qualification/<?= $qualification['idQualification'] ?>?action=DeleteByPK"
                   role="button">Удалить</a>
            </div>
        </div>
        <?php foreach ($qualification['fDataArray'] as $key => $value)
            \CRUD\Controllers\BasicController::constuctView($qualification['fDataArray'][$key]); ?>
    </div>
<?php endif; ?>


