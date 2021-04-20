<?php
require_once('../dbconnect.php');
session_start();

//エラー項目の確認
if (!empty($_FILES)) {
    $fileName = $_FILES['image']['name'];
    if (!empty($fileName)) {
        $ext = substr($fileName, -3);
        if ($ext != 'jpg' && $ext != 'gif') {
            $error = 'type';
        }
    }
}

if (empty($error) && !empty($_FILES)) {
    //画像をアップロードする
    $image = date('YmdHis') . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../member_picture/$image");

    $statement = $db->prepare(
        'UPDATE members SET picture=?, modified=NOW() WHERE id=?'
    );
    $statement->execute(array($image, $_SESSION['id']));

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
            <dt>新しいプロフィール画像</dt>
            <form action="./edit_picture.php" method="POST" enctype="multipart/form-data" >
                <dd>
                    <input type="file", name="image", size="35" />
                    <?php if ($error == 'type'): ?>
                        <p class="error">*画像は「.jpg」または「.gif」のものを指定してください</p>
                    <?php endif ?>
                    <?php if (!empty($error)): ?>
                        <p class="error">*画像を再度指定してください</p>
                    <?php endif ?>
                </dd>
                <input type="submit" value="保存" />
            </form>
        <a href="../edit_profile.php">戻る</a>
        </dl>
    </div>

</div>
</body>
</html>
