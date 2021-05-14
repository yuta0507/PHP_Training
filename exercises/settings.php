<?php
$index = 'index.php';

if (!empty($_POST)) {
    if (!is_numeric($_POST['displayed-results'])) {
        $error['displayed_results'] = 'not_numeric';
    }
}

if (!empty($_POST) && empty($error)) {
    if ($_POST['mode'] === 'dark-mode') {
        setcookie('mode', 'darkmode', time()+3600*24*365*10);
    }
    if ($_POST['mode'] === 'normal-mode') {
        setcookie('mode', '', time()-1);
    }

    if (is_numeric($_POST['displayed-results'])) {
        setcookie('displayed_results', $_POST['displayed-results'], time()+3600*24*365*10);
    }

    header('Location: settings.php');
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>設定</title>
</head>
<body class="<?php echo $_COOKIE['mode'] ?>">
    <!-- ナビゲーションバー -->
    <nav>
        <ul>
            <li>
                <a href="<?php echo $index ?>">会社一覧</a>
            </li>
            <li>
                <a href="settings.php" class="setting">
                    <img src="images/setting.png">
                </a>
            </li>
        </ul>
    </nav>

    <div class="container">
        <form action="" method="POST">
            <div class="mode-choices">
                <label class="mode">
                    ダークモード
                    <input type="radio" name="mode" value="dark-mode"
                    <?php
                    if ($_COOKIE['mode'] === 'darkmode') {echo "checked";}
                    ?>>
                </label>
                <label class="mode">
                    通常モード
                    <input type="radio" name="mode" value="normal-mode"
                    <?php
                    if ($_COOKIE['mode'] == '') {echo "checked";}
                    ?>>
                </label>
            </div>
            <br>
            <div class="displayed-results">
                <span>表示件数：</span>
                <input type="number" name="displayed-results" min="1"
                value="<?php echo $_COOKIE['displayed_results'] ?>">
                <?php if ($error['displayed_results']) : ?>
                    <p class="error">*表示件数は半角数字で入力してください。</p>
                <?php endif ?>
            </div>
            <br>
            <input type="submit" class="button-submit" value="設定">
        </form>
    </div>
</body>
</html>