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

//htmlspecialchars()のfunction
function h($value) {
  return htmlspecialchars($value, ENT_QUOTES);
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
    <p>&laquo;<a href="index.php">一覧に戻る</a></p>
    <?php if ($post = $posts->fetch()): ?>
      <div class="msg">
        <img src="member_picture/<?php echo h($post['picture']) ?>" alt="<?php echo h($post['name']) ?>" width="48" height="48" />
        <p>
          <span class="name"><?php echo h($post['name']) ?>:</span>
          <?php echo h($post['message']) ?>
          [<a href="index.php?res=<?php echo h($post['id']) ?>">Re</a>]
        </p>
        <p class="day">
          <a href="view.php?id=<?php echo h($post['id']) ?>"><?php echo h($post['created']) ?></a>
        </p>
      </div>
    <?php else: ?>
        <p>その投稿は削除されたか、URLが間違っています</p>
    <?php endif ?>
  </div>

</div>
</body>
</html>
