<?php
require_once('dbconnect.php');
session_start();

if (empty($_GET['id'])) {
    header('Location: index.php');
}

//投稿を取得する
$posts = $db->prepare(
    'SELECT m.name, m.picture, p.* FROM members m, posts p 
    WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC'
);
$posts->execute(array($_GET['id']));
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
    <p>&laquo;<a href="index.php">一覧に戻る</a></p>
    <?php if ($post = $posts->fetch()): ?>
    <div class="msg">
        <?php
            $pic_name = htmlspecialchars($post['picture'], ENT_QUOTES);
            $msg = htmlspecialchars($post['message'], ENT_QUOTES);
            $post_name = htmlspecialchars($post['name'], ENT_QUOTES);
            $time = htmlspecialchars($post['created'], ENT_QUOTES);
            $id = htmlspecialchars($post['id'], ENT_QUOTES);
        ?>
        <img src="member_picture/<?php echo $pic_name ?>" alt="<?php echo $name ?>" width="48" height="48" />
        <p>
            <span class="name"><?php echo $post_name ?>:</span>
            <?php echo $msg ?>
            [<a href="index.php?res=<?php echo $id ?>">Re</a>]
        </p>
        <p class="day"><?php echo $time ?></p>
    </div>
    <?php else: ?>
        <p>その投稿は削除されたか、URLが間違っています</p>
    <?php endif ?>
  </div>

</div>
</body>
</html>
