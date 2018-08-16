<?php
/**
 * Created by PhpStorm.
 * User: LiveAndWork
 * Date: 31.07.2018
 * Time: 21:10
 */
?>
<?php if (isset($message) && !empty($message)): ?>
    <div class="alert alert-light" role="alert">
        <?= $message ?>
    </div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <form method="post"
            <?php if (stripos($_GET['action'], 'Current') === false && stripos($_GET['action'], 'ByPK') === false): ?>
                action="/CRUDv2/department?action=UpdateProgrammer"
            <?php else: ?>
                action="/CRUDv2/department?action=UpdateCurrentProgrammer"
            <?php endif; ?>>
            <div class="container-fluid">
                <div class="form-group row">
                    <label class=" col-lg-1" for="programmerFullName">Программист<span
                                style="color:red">*</span></label>
                    <select class="form-control col-lg-6" id="programmerFullName" name="idProgrammer" required="">
                        <?php $arrayProgrammersRealId = [] ?>
                        <?php foreach ($programmerDerictories['programmers'] as $iProgrammer): ?>
                            <?php $arrayProgrammersRealId[] = $iProgrammer['idProgrammer'] ?>
                        <?php endforeach; ?><?php
                        if (empty($programmer['idProgrammer']) || !in_array($programmer['idProgrammer'], $arrayProgrammersRealId)):
                            ?>
                            <option hidden selected value="">выберите прораммиста</option><?php endif; ?>
                        <?php foreach ($programmerDerictories['programmers'] as $iProgrammer): ?>
                            <option <?php if (!empty($programmer['idProgrammer']) && strcasecmp($programmer['idProgrammer'], $iProgrammer['idProgrammer']) === 0):
                            ?> selected <?php endif;
                            ?>value="<?= $iProgrammer['idProgrammer'] ?>"><?= $iProgrammer['programmerToken'] . ' (' . $iProgrammer['programmerSurname'] . ' ' . $iProgrammer['programmerName'] . ' ' . $iProgrammer['programmerPatronymic'] . ')' ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($programmerDerictories['programmers'])): ?>
                        <small id="idProgrammerHelp" class="form-text text-muted">Программисты отсутствуют. Перед
                            учетом
                            навыков программистов, пожалуйста,
                            <a href="/CRUDv2/programmer/?action=ShowRowAddForm">создайте хотя бы одного программиста</a>.
                        </small>
                    <?php endif; ?>
                </div>
                <div class="form-group row ">
                    <label for="departmentName" class=" col-lg-1">Отдел</label>
                    <select class="form-control col-lg-6 " id="departmentName" name="fIdDepartment">
                        <?php $arrayDepartmentsId = [] ?>
                        <?php foreach ($programmerDerictories['departments'] as $iDepartment): ?>
                            <?php $arrayDepartmentsId[] = $iDepartment['idDepartment'] ?>
                            <option <?php if ((empty($programmer['fIdDepartment']) && empty($programmer['fIdDepartment'])) || (!empty($programmer['fIdDepartment']) && strcasecmp($programmer['fIdDepartment'], $iDepartment['idDepartment']) === 0)) : ?><?= 'selected' ?><?php endif; ?>
                                    value="<?= $iDepartment['idDepartment'] ?>"><?= $iDepartment['departmentName'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($arrayDepartmentsId)): ?>
                        <small id="fIdDepartmentHelp" class="form-text text-muted col-lg-12">Отдела не
                            существует. Перед преводом программиста в отдел, пожалуйста,
                            <a href="/CRUDv2/department/?action=ShowRowAddForm">создайте желаемый отдел</a>.
                        </small>
                    <?php endif; ?>
                </div>
                <div class=" btn-group">
                    <a class="btn btn-secondary" role="button"
                        <?php if (stripos($_GET['action'], 'Current') === false && stripos($_GET['action'], 'ByPK') === false): ?>
                            href="/CRUDv2/department?action=FindAll"
                        <?php else: ?>
                            href="/CRUDv2/department/<?= empty($programmer['fIdDepartment'])?'':$programmer['fIdDepartment']?>?action=FindByPK"
                        <?php endif; ?>>Назад</a>
                    <button class="btn  btn-success" type="submit">Cохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>

