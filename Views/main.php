<?php
/**
 * Created by PhpStorm.
 * User: yulia
 * Date: 7/18/18
 * Time: 5:36 PM
 */
?>
<!doctype html>
<html lang="ru">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Система учета</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<div class="navbar-dark bg-dark py-3" role="navigation">
    <div class="container-fluid">
        <nav class="nav nav-pills " role="navigation">
            <?php foreach ($TopMenuHTML as $item): ?>
                <a class="px-4 py-3 nav-item nav-link text-uppercase <?php if ($item['active']): ?>active bg-secondary text-light <?php else: ?>bg-dark text-secondary<?php endif; ?>"
                   href="<?= $item['href'] ?>"><b><?= $item['title'] ?></b></a>
            <?php endforeach; ?>
            <nav class="ml-auto my-auto px-4 btn-group justify-content-end">
                <?php foreach ($TopButtonsHTML as $item): ?>
                    <a class="btn btn-outline-<?= $item['color'] ?? 'light' ?>"
                       href="<?= $item['href'] ?>?action=<?= $item['action'] ?>" role="button"><?= $item['name'] ?></a>
                <?php endforeach; ?>
            </nav>
        </nav>
    </div>
</div>
<?php if (isset($MessageHTML) && !empty($MessageHTML)): ?>
    <div class="alert alert-light" role="alert">
        <?= $MessageHTML ?>
    </div>
<?php endif; ?>
<?php \CRUD\Controllers\BasicController::constuctView($ContentHTML); ?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>
</html>
