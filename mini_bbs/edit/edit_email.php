<?php
require_once('../dbconnect.php');
session_start();

//エラー項目の確認
if (!empty($_POST)) {
    if ($_POST['email'] == '') {
        $error = 'blank';
    }
    if ($_POST['re_email'] == '') {
        $error = 'blank';
    }
}

//入力メールアドレス2つが一致する場合のみ更新
if (!empty($_POST['email']) && !empty($_POST['re_email'])) {
    if ($_POST['email'] == $_POST['re_email']) {
        $statement = $db->prepare(
            'UPDATE members SET email=? WHERE id=?'
        );
        $statement->execute(array($_POST['email'], $_SESSION['id']));
        
        header('Location: ../edit_profile.php');
        exit();
    } else {
        $error = 'failed';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>
    
	<link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>ひとこと掲示板</h1>
        </div>
        <div id="content">
            <dl>
                <dt>新しいメールアドレス</dt>
                <form action="edit_email.php" method="POST">
                    <dd>
                        <input type="text", name="email", size="35", maxlength="255",
                        value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES) ?>" />
                    </dd>
                    <p>再度入力してください</p>
                    <dd>
                        <input type="text" name="re_email", size="35", maxlength="255",
                        value="<?php echo htmlspecialchars($_POST['re_email'], ENT_QUOTES) ?>" />
                    </dd>
                    <?php if (!empty($error)): ?>
                        <p class="error">*再度入力してください</p>
                    <?php endif ?>
                    <input type="submit" value="保存">
                </form>
                <a href="../edit_profile.php">戻る</a>
            </dl>
        </div>
        
</div>
</body>
</html>
