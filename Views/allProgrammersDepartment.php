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
        <th scope="col">Индивидуальный номер программиста</th>
        <th scope="col">Фамилия</th>
        <th scope="col">Имя</th>
        <th scope="col">Отчество</th>
        <th scope="col">Навыки (с уровням)</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($dataArray)): ?>
        <?php $currentProgrammer = '' ?>
        <?php foreach ($dataArray as $element): ?>
            <?php if (strcmp($currentProgrammer, $element['fIdProgrammer']) !== 0): ?>
                <?php $currentProgrammer = $element['fIdProgrammer'] ?>
                <tr>
                    <td>
                        <a href="/CRUDv2/programmer/<?= $element['idProgrammer'] ?>?action=FindByPK">
                            <ins><?= $element['programmerToken'] ?></ins>
                        </a>
                    </td>
                    <td><?= $element['programmerSurname'] ?></td>
                    <td><?= $element['programmerName'] ?></td>
                    <td><?= $element['programmerPatronymic'] ?></td>
                    <td>
                        <div class="btn-group">
                            <?php foreach ($dataArray as $element2): ?>
                                <?php if (strcmp($currentProgrammer, $element2['fIdProgrammer']) === 0): ?>
                                    <?php $currentQualificationLevel = $element2['fLevelQualificationLevel'];
                                    $acceptProgress = settype($currentQualificationLevel, 'float');
                                    if ($acceptProgress) $currentQualificationLevel = $currentQualificationLevel * 100 / 3;
                                    ?>
                                    <div class="btn">
                                        <div class="col-6">
                                            <a href="/CRUDv2/qualification/<?= $element2['fIdQualification'] ?>?action=FindByPK">
                                                <ins><?= $element2['qualificationName'] ?></ins>
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <small><?= $element2['qualificationLevelName'] ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a class="btn btn-warning"
                               href="/CRUDv2/programmer/<?= $element['idProgrammer'] ?>?action=ShowCurrentDepartmentRowUpdateFormByPK"
                               role="button">Изменить</a>
                            <a class="btn btn-danger"
                               href="/CRUDv2/department/<?= $element['idProgrammer'] ?>?action=DeleteAccountingByPK"
                               role="button">Удалить программиста</a>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

