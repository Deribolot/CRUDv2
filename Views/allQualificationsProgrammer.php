<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/31/18
 * Time: 2:53 PM
 */
?>
<table class="table table-striped table-hover">
    <thead class="thead-dark p-2">
    <tr>
        <th scope="col">Навык программиста</th>
        <th scope="col">Уровень навыка</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($dataArray)): ?>
        <?php foreach ($dataArray as $element): ?>
            <tr>
                <td><a href="/CRUDv2/qualification/<?= $element['idQualification'] ?>?action=FindByPK">
                        <ins><?= $element['qualificationName'] ?></ins>
                    </a>
                </td>
                <?php $currentQualificationLevel = $element['fLevelQualificationLevel'];
                $acceptProgress = settype($currentQualificationLevel, 'float');
                if ($acceptProgress) $currentQualificationLevel = $currentQualificationLevel * 100 / 3;
                ?>
                <td>
                    <div class="row">
                        <div class="col-6"><?= $element['qualificationLevelName'] ?></div>
                        <div class="col-6">
                            <?php if ($acceptProgress): ?>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: <?= $currentQualificationLevel . '%' ?>"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a class="btn btn-warning"
                           href="/CRUDv2/accounting/<?= $element['idQualificationsAccounting']  ?>?action=ShowProgrammerRowUpdateFormByPK"
                           role="button">Изменить</a>
                        <a class="btn btn-danger"
                           href="/CRUDv2/programmer/<?= $element['idQualificationsAccounting']  ?>?action=DeleteAccountingByPK"
                           role="button">Забыть о навыке программиста</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

