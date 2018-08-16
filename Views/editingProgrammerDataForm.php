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
            <?php if (stripos($_GET['action'], 'Update') === false): ?>
                action="/CRUDv2/programmer?action=<?= "AddRow" ?>"
            <?php else: ?>
                <?php if (stripos($_GET['action'], 'Current') === false): ?>
                    action="/CRUDv2/programmer?action=<?= "UpdateRow" ?>"
                <?php else: ?>
                    <?php if (stripos($_GET['action'], 'Department') === false): ?>
                        action="/CRUDv2/programmer?action=<?= "UpdateCurrentRow" ?>"
                    <?php else: ?>
                        action="/CRUDv2/programmer?action=<?= "UpdateCurrentDepartmentRow" ?>"
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>>
            <div class="form-group row">
                <label for="idProgrammer" class="col-lg-1">id</label>
                <input type="text" class="form-control col-lg-6" name="idProgrammer" id="idProgrammer"
                       placeholder="отсутствует" readonly pattern="[1-9][0-9]{0,5}" maxlength="6"
                       value="<?php
                       if (!empty($programmer['idProgrammer'])): ?><?= $programmer['idProgrammer'] ?><?php endif; ?>">
            </div>
            <div class="form-group row">
                <label for="programmerToken" class="col-lg-1">Индивидуальный номер<span
                            style="color:red">*</span></label>
                <input type="text" class="form-control col-lg-6" name="programmerToken" id="programmerToken" required
                       pattern="[1-9]{1}[0-9]*" maxlength="6"
                       placeholder="введите индивидуальный номер"
                       value="<?php
                       if (!empty($programmer['programmerToken'])): ?><?= $programmer['programmerToken'] ?><?php endif; ?>">
            </div>
            <div class="form-group row">
                <label for="programmerSurname" class="col-lg-1">Фамилия<span style="color:red">*</span></label>
                <input type="text" class="form-control col-lg-6" name="programmerSurname" id="programmerSurname"
                       required
                       pattern="[а-яёА-ЯЁa-zA-Z]{1}([а-яёА-ЯЁa-zA-Z]+([\s-]{1}[а-яёА-ЯЁa-zA-Z]{1})?)*" maxlength="30"
                       placeholder="введите фамилию программиста" value="<?php
                if (!empty($programmer['programmerSurname'])): ?><?= $programmer['programmerSurname'] ?><?php endif; ?>">
            </div>
            <div class="form-group row">
                <label for="programmerName" class="col-lg-1">Имя<span style="color:red">*</span></label>
                <input type="text" class="form-control col-lg-6" name="programmerName" id="programmerName"
                       placeholder="введите имя программиста" required
                       pattern="[а-яёА-ЯЁa-zA-Z]{1}([а-яёА-ЯЁa-zA-Z]+([\s-]{1}[а-яёА-ЯЁa-zA-Z]{1})?)*" maxlength="30"
                       value="<?php
                       if (!empty($programmer['programmerName'])): ?><?= $programmer['programmerName'] ?><?php endif; ?>">
            </div>
            <div class="form-group row">
                <label for="programmerPatronymic" class="col-lg-1">Отчество<span style="color:red">*</span></label>
                <input type="text" class="form-control col-lg-6" name="programmerPatronymic" id="programmerPatronymic"
                       required pattern="[а-яёА-ЯЁa-zA-Z]{1}([а-яёА-ЯЁa-zA-Z]+([\s-]{1}[а-яёА-ЯЁa-zA-Z]{1})?)*"
                       maxlength="30"
                       placeholder="введите отчество программиста"
                       value="<?php
                       if (!empty($programmer['programmerPatronymic'])): ?><?= $programmer['programmerPatronymic'] ?><?php endif; ?>">
            </div>
            <div class="form-group row">
                <label for="departmentName" class="col-lg-1">Отдел</label>
                <select class="form-control col-lg-6" id="departmentName" name="fIdDepartment">
                    <?php $arrayDepartmentsId = [] ?>
                    <?php foreach ($programmerDerictories['departments'] as $iDepartment): ?>
                        <?php $arrayDepartmentsId[] = $iDepartment['idDepartment'] ?>
                        <option <?php if (!empty($programmer['fIdDepartment']) && strcasecmp($programmer['fIdDepartment'], $iDepartment['idDepartment']) == 0): ?><?= 'selected' ?><?php endif; ?>
                                value="<?= $iDepartment['idDepartment'] ?>"><?= $iDepartment['departmentName'] ?></option>
                    <?php endforeach; ?>
                    <option <?php
                    if (empty($programmer['fIdDepartment']) || !in_array($programmer['fIdDepartment'], $arrayDepartmentsId)): ?><?= 'selected' ?><?php endif; ?>
                            value="">отсутствует
                    </option>
                </select>
            </div>
            <div class=" btn-group">
                <a class="btn btn-secondary"
                    <?php if (stripos($_GET['action'], 'Current') !== false): ?>
                        <?php if (stripos($_GET['action'], 'Department') === false): ?>
                            href="/CRUDv2/programmer/<?= empty($programmer['idProgrammer']) ? "" : $programmer['idProgrammer'] ?>?action=FindByPK"
                        <?php else: ?>
                            href="/CRUDv2/department/<?= empty($programmer['fIdDepartment']) ? "" : $programmer['fIdDepartment'] ?>?action=FindByPK"
                        <?php endif; ?>
                    <?php else: ?>
                        href="/CRUDv2/programmer?action=FindAll"
                    <?php endif; ?>>Назад</a>
                <button class="btn  btn-success" type="submit">Cохранить</button>
            </div>
        </form>
    </div>
</div>















