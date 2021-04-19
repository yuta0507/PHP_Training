<?php
require_once('../dbconnect.php');
session_start();

if (!empty($_POST)) {
    //エラー項目の確認
    if ($_POST['password'] != $_POST['re_password']) {
        $error = 'mismatch';
    }
    if (strlen($_POST['password']) < 4) {
        $error = 'length';
    }
    if ($_POST['password'] == '') {
        $error = 'blank';
    }
    if ($_POST['re_password'] == '') {
        $error = 'blank';
    }
}

if (empty($error) && !empty($_POST)) {
    $statement = $db->prepare(
        'UPDATE members SET password=? WHERE id=?'
    );
    $statement->execute(array(sha1($_POST['password']), $_SESSION['id']));

    header('Location: ../edit_profile.php');
    exit();
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
            <form action="edit_password.php" method="POST">
                <dt>新しいパスワード</dt>
                    <input type="password" name="password" size="10" maxlength="20" 
                    value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES) ?>" />
                    <p>再度入力してください</p>
                    <input type="password" name="re_password" size="10" maxlength="20" 
                    value="<?php echo htmlspecialchars($_POST['re_password'], ENT_QUOTES) ?>" />
                    <?php if ($error): ?>
                        <p class="error">*パスワードを再度入力してください</p>
                    <?php endif ?>
                    <?php if ($error == 'length'): ?>
                        <p class="error">*パスワードは4文字以上で設定してください</p>
                    <?php endif ?>
                <div><input type="submit" value="保存"></div>
            </form>
            <a href="..//edit_profile.php">戻る</a>
        </dl>
    </div>

</div>
</body>
</html>
