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
            <?php if (stripos($_GET['action'], 'Add') === false): ?>
                <?php if (stripos($_GET['action'], 'Qualification') === false): ?>
                    action="/CRUDv2/accounting/?action=UpdateProgrammerRow"
                <?php else: ?>
                    action="/CRUDv2/accounting/?action=UpdateQualificationRow"
                <?php endif; ?>
            <?php else: ?>
                <?php $answer = explode('?', $_SERVER['REQUEST_URI']) ?>
                <?php if (mb_stripos($answer[0], 'Qualification') === false && (mb_stripos($answer[0], 'Programmer') === false)): ?>
                    action="/CRUDv2/accounting?action=AddRow"
                <?php else: ?>
                    <?php if (stripos($_GET['action'], 'Current') === false && stripos($_GET['action'], 'ByPK') === false): ?>
                        action="<?= $answer[0] . "?action=AddAccounting" ?>"
                    <?php else: ?>
                        <?php if (mb_stripos($answer[0], 'Qualification') === false): ?>
                            action="/CRUDv2/programmer?action=AddCurrentAccounting"
                        <?php else: ?>
                            action="/CRUDv2/qualification?action=AddCurrentAccounting"
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>>
            <div class="form-group row">
                <label for="idQualificationsAccounting" class="col-lg-1">id связи</label>
                <input type="text" class="form-control col-lg-6" name="idQualificationsAccounting" readonly
                       pattern="[1-9][0-9]{0,5}"
                       id="idQualificationsAccounting" placeholder="отсутствует"
                       value="<?php
                       if (!empty($accounting['idQualificationsAccounting'])): ?><?= $accounting['idQualificationsAccounting'] ?><?php endif; ?>">
            </div>
            <div class="form-group row">
                <label for="fIdProgrammer" class="col-lg-1">Программист<span
                            style="color:red">*</span></label>
                <select class="form-control col-lg-6" id="fIdProgrammer" name="fIdProgrammer" required="">
                    <?php $arrayProgrammersId = [] ?>
                    <?php foreach ($accountingDerictories['programmers'] as $iProgrammer): ?>
                        <?php $arrayProgrammersId[] = $iProgrammer['idProgrammer'] ?>
                    <?php endforeach; ?><?php
                    if (empty($accounting['fIdProgrammer']) || !in_array($accounting['fIdProgrammer'], $arrayProgrammersId)):
                        ?>
                        <option hidden selected value="">выберите прораммиста</option><?php endif; ?>
                    <?php foreach ($accountingDerictories['programmers'] as $iProgrammer): ?>
                        <option <?php if (!empty($accounting['fIdProgrammer']) && strcasecmp($accounting['fIdProgrammer'], $iProgrammer['idProgrammer']) == 0):
                        ?> selected <?php endif;
                        ?>value="<?= $iProgrammer['idProgrammer'] ?>"><?= $iProgrammer['programmerToken'] . ' (' . $iProgrammer['programmerSurname'] . ' ' . $iProgrammer['programmerName'] . ' ' . $iProgrammer['programmerPatronymic'] . ')' ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($accountingDerictories['programmers'])): ?>
                    <small id="fIdProgrammerHelp" class="form-text text-muted col-lg-12">Программисты отсутствуют.
                        Перед учетом навыков программистов, пожалуйста,
                        <a href="/CRUDv2/programmer/?action=ShowRowAddForm">создайте хотя бы одного программиста</a>.
                    </small>
                <?php endif; ?>
            </div>
            <div class="form-group row">
                <label for="qualificationName" class="col-lg-1">Навык<span
                            style="color:red">*</span></label>
                <select class="form-control col-lg-6" id="qualificationName" name="fIdQualification" required="">
                    <?php $arrayQualificationsId = [] ?>
                    <?php foreach ($accountingDerictories['qualifications'] as $iQualification): ?>
                        <?php $arrayQualificationsId[] = $iQualification['idQualification'] ?>
                    <?php endforeach; ?><?php
                    if (empty($accounting['fIdQualification']) || !in_array($accounting['fIdQualification'], $arrayQualificationsId)):
                        ?>
                        <option hidden selected value="">выберите навык</option><?php endif; ?>
                    <?php foreach ($accountingDerictories['qualifications'] as $iQualification): ?>
                        <option <?php if (!empty($accounting['fIdQualification']) && strcasecmp($accounting['fIdQualification'], $iQualification['idQualification']) == 0):
                        ?> selected <?php endif;
                        ?>value="<?= $iQualification['idQualification'] ?>"><?= $iQualification['qualificationName'] ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($accountingDerictories['qualifications'])): ?>
                    <small id="fIdQualificationHelp" class="form-text text-muted col-lg-12">Навыки отсутствуют. Перед
                        учетом навыков программистов, пожалуйста,
                        <a href="/CRUDv2/qualification/?action=ShowRowAddForm">создайте хотя бы один навык</a>.
                    </small>
                <?php endif; ?>
            </div>
            <div class="form-group row">
                <label for="qualificationLevelName" class="col-lg-1">Уровень<span
                            style="color:red">*</span></label>
                <select class="form-control col-lg-6" id="qualificationLevelName" name="fLevelQualificationLevel"
                        required="">
                    <?php $arrayLevelsLevels = [] ?>
                    <?php foreach ($accountingDerictories['levels'] as $iLevel): ?>
                        <?php $arrayLevelsLevels[] = $iLevel['levelQualificationLevel'] ?>
                    <?php endforeach; ?><?php
                    if (empty($accounting['fLevelQualificationLevel']) || !in_array($accounting['fLevelQualificationLevel'], $arrayLevelsLevels)):
                        ?>
                        <option hidden selected value="">выберите уровень</option><?php endif; ?>
                    <?php foreach ($accountingDerictories['levels'] as $iLevel): ?>
                        <option <?php if (!empty($accounting['fLevelQualificationLevel']) && strcasecmp($accounting['fLevelQualificationLevel'], $iLevel['levelQualificationLevel']) == 0):
                        ?> selected <?php endif;
                        ?>value="<?= $iLevel['levelQualificationLevel'] ?>"><?= $iLevel['qualificationLevelName'] . ' (ступень ' . $iLevel['levelQualificationLevel'] . ')' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class=" btn-group">
                <a class="btn btn-secondary"
                    <?php if (stripos($_GET['action'], 'Add') === false): ?>
                        <?php if (stripos($_GET['action'], 'Qualification') === false): ?>
                            href="/CRUDv2/programmer/<?= empty($accounting['fIdProgrammer'])?'':$accounting['fIdProgrammer']?>?action=FindByPK"
                        <?php else: ?>
                            href="/CRUDv2/qualification/<?=  empty($accounting['fIdQualification'])?'':$accounting['fIdQualification'] ?>?action=FindByPK"
                        <?php endif; ?>
                    <?php else: ?>
                        <?php $answer = explode('?', $_SERVER['REQUEST_URI']) ?>
                        <?php if (mb_stripos($answer[0], 'Qualification') === false && (mb_stripos($answer[0], 'Programmer') === false)): ?>
                            href="/CRUDv2/accounting?action=FindAll"
                        <?php else: ?>
                            <?php if (stripos($_GET['action'], 'Current') === false && stripos($_GET['action'], 'ByPK') === false): ?>
                                href="<?= $answer[0] . "?action=FindAll" ?>"
                            <?php else: ?>
                                <?php if (mb_stripos($answer[0], 'Qualification') === false): ?>
                                    href="/CRUDv2/programmer/<?= empty($accounting['fIdProgrammer'])?'':$accounting['fIdProgrammer']?>?action=FindByPK"
                                <?php else: ?>
                                    href="/CRUDv2/qualification/<?= empty($accounting['fIdQualification'])?'':$accounting['fIdQualification']?>?action=FindByPK"
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>>Назад</a>
                <button class="btn  btn-success" type="submit">Cохранить</button>
            </div>
        </form>
    </div>
</div>