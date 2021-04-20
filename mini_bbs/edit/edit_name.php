<?php
require_once('../dbconnect.php');
session_start();

//エラー項目の確認
if (!empty($_POST)) {
    if ($_POST['name'] == '') {
        $error = 'blank';
    }
}

if (empty($error) && !empty($_POST)) {
    $statement = $db->prepare(
        'UPDATE members SET name=?, modified=NOW() WHERE id=?'
    );
    $statement->execute(array($_POST['name'], $_SESSION['id']));
    
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
            <dt>新しいニックネーム</dt>
            <form action="edit_name.php" method="POST">
                <dd>
                    <input type="text" name="name" size="35" maxlength="255" 
                        value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES) ?>"/>
                </dd>
                <?php if ($error): ?>
                    <p class="error">*再度入力してください</p>
                <?php endif ?>
                <input type="submit" value="保存">
            </form>
        </dl>
        <a href="../edit_profile.php">戻る</a>
    </div>

</div>
</body>
</html>
