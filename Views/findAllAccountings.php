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
<table class="table table-hover">
    <div class="container-fluid">
        <thead class="thead-dark px-4 py-3">
        <tr>
            <th scope="col">Индивидуальный номер</th>
            <th scope="col">Фамилия</th>
            <th scope="col">Имя</th>
            <th scope="col">Отчество</th>
            <th scope="col">Отдел</th>
            <th scope="col">Навыки (с уровням)</th>
        </tr>
        </thead>
        <?php if (isset($accountings)): ?>
            <?php $currentProgrammer = '' ?>
            <tbody class="px-4">
            <?php foreach ($accountings as $element): ?>
                <?php if (strcmp($currentProgrammer, $element['fIdProgrammer']) !== 0): ?>
                    <?php $currentProgrammer = $element['fIdProgrammer'] ?>
                    <tr>
                        <td>
                            <a href="/CRUDv2/programmer/<?= $element['fIdProgrammer'] ?>?action=FindByPK">
                                <ins><?= $element['programmerToken'] ?></ins>
                            </a>
                        </td>
                        <td><?= $element['programmerSurname'] ?></td>
                        <td><?= $element['programmerName'] ?></td>
                        <td><?= $element['programmerPatronymic'] ?></td>
                        <td>
                            <?php if (isset($element['fIdDepartment'])): ?>
                                <a href="/CRUDv2/department/<?= $element['fIdDepartment'] ?>?action=FindByPK">
                                    <ins><?= $element['departmentName'] ?></ins>
                                </a>
                            <?php else: ?>
                                нет
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <?php foreach ($accountings as $element2): ?>
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
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        <?php endif; ?>
    </div>
</table>


