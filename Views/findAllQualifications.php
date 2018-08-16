<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 2:53 PM
 */
?>
<?php if (isset($message) && !empty($message)): ?>
    <div class="alert alert-light" role="alert">
        <?= $message ?>
    </div>
<?php endif; ?>
<table class="table table-striped table-hover">
    <div class="container-fluid">
        <thead class="thead-dark px-4 py-3">
        <tr>
            <th scope="col">Навык</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <?php if (isset($qualifications)): ?>
            <tbody class="px-4">
            <?php foreach ($qualifications as $element): ?>
                <tr>
                    <td><?= $element['qualificationName'] ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <a class="btn btn-secondary"
                               href="/CRUDv2/qualification/<?= $element['idQualification'] ?>?action=FindByPK"
                               role="button">Просмотреть</a>
                            <a class="btn btn-warning"
                               href="/CRUDv2/qualification/<?= $element['idQualification'] ?>?action=ShowRowUpdateFormByPK"
                               role="button">Изменить</a>
                            <a class="btn btn-danger"
                               href="/CRUDv2/qualification/<?= $element['idQualification'] ?>?action=DeleteByPK"
                               role="button">Удалить</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        <?php endif; ?>
    </div>
</table>

