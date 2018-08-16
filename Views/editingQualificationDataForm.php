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
                action="/CRUDv2/qualification?action=<?= "AddRow" ?>"
            <?php else: ?>
                <?php if (stripos($_GET['action'], 'Current') === false): ?>
                    action="/CRUDv2/qualification?action=<?= "UpdateRow" ?>"
                <?php else: ?>
                    action="/CRUDv2/qualification?action=<?= "UpdateCurrentRow" ?>"
                <?php endif; ?>
            <?php endif; ?>>
            <div class="form-group row">
                <label for="idQualification" class="col-lg-1">id</label>
                <input type="text" class="form-control col-lg-6" name="idQualification" id="idQualification"
                       placeholder="отсутствует" readonly pattern="[1-9][0-9]{0,5}" maxlength="6"
                       value="<?= $qualification['idQualification'] ?? '' ?>">
            </div>
            <div class="form-group row">
                <label for="qualificationName" class="col-lg-1">Название<span style="color:red">*</span></label>
                <input type="text" class="form-control col-lg-6"
                       title="Допускается давать название, начинающееся с буквы или цифры, остальные символы могут быть буквами и цифрами. Между цифробуквенными символами допускается пробел, дефис, точка. Все буквы прописные, доступны как русские, так и английские буквы."
                       class="form-control" name="qualificationName" id="qualificationName" required
                       pattern="[A-ZА-ЯЁ0-9]{1}([A-ZА-ЯЁ0-9]+([\.\s-]{1}[A-ZА-ЯЁ0-9]{1})?)*" maxlength="30"
                       placeholder="введите название навыка" value="<?php
                if (!empty($qualification['qualificationName'])): ?><?= $qualification['qualificationName'] ?><?php endif; ?>">
            </div>
            <div class=" btn-group">
                <a class="btn btn-secondary"
                   href="<?php if (stripos($_GET['action'], 'Current') !== false && !empty($qualification['idQualification'])): ?>
                    /CRUDv2/qualification/<?= $qualification['idQualification'] ?>?action=FindByPK
                <?php else: ?>
                    /CRUDv2/qualification?action=FindAll
                <?php endif; ?>">Назад</a>
                <button class="btn  btn-success" type="submit">Cохранить</button>
            </div>
        </form>
    </div>
</div>


