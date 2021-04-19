<?php
require_once('dbconnect.php');
session_start();

//データを呼び出す
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    //ログインしている
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
} else {
    //ログインしてない
    header('Location: /login.php');
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

	<link rel="stylesheet" href="css/style.css" />
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>ひとこと掲示板</h1>
    </div>
    <div id="content">
        <dl>
            <dt>プロフィール画像</dt>
            <dd><img src="member_picture/<?php echo htmlspecialchars($member['picture'], ENT_QUOTES) ?>"  width="100" height="100" /></dd>
            <dt>ニックネーム<a class="editing" href="edit/edit_name.php">【編集】</a></dt>
            <dd><?php echo htmlspecialchars($member['name'], ENT_QUOTES) ?></dd>
            <dt>メールアドレス<a href="edit/edit_email.php" class="editing">【編集】</a></dt>
            <dd><?php echo htmlspecialchars($member['email'], ENT_QUOTES) ?></dd>
            <dt>パスワード<a href="edit/edit_password.php" class="editing">【編集】</a></dt>
            <dd>【表示できません】</dd>
        </dl>
        <p><a href="index.php">戻る</a></p>
    </div>

</div>
</body>
</html>
