<?php
require_once('dbconnect.php');
session_start();

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    //ログインしている
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
} else {
    //ログインしてない
    header('Location: login.php');
    exit();
}

//投稿を記録する
if (!empty($_POST)) {
    if ($_POST['message'] != '') {
        $message = $db->prepare(
            'INSERT INTO posts SET member_id=?, message=?, reply_post_id=?, created=NOW()'
        );
        $message->execute(array(
            $member['id'],
            $_POST['message'],
            $_POST['reply_post_id']
        ));
        header('Location: index.php');
        exit();
    }
}

//投稿を取得する
$posts = $db->query(
    'SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC'
);

//返信の場合
if (isset($_GET['res'])) {
    $response = $db->prepare(
        'SELECT m.name, m.picture, p.* from members m, posts p 
        WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC'
    );
    $response->execute(array($_GET['res']));

    $table = $response->fetch();
    $message = '@'.$table['name'].' '.$table['message'];
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
    <div style="text-align: right;"><a href="logout.php">ログアウト</a></div>
    <form action="" method="POST">
        <dl>
            <dt>
                <?php $login_name = htmlspecialchars($member['name'], ENT_QUOTES); ?>
                <?php echo $login_name ?>さん、メッセージをどうぞ
            </dt>
            <dd>
                <?php $res = htmlspecialchars($_GET['res'], ENT_QUOTES) ?>
                <textarea name="message" cols="50" rows="5"><?php echo $message ?></textarea>
                <input type="hidden" name="reply_post_id" value="<?php echo $res ?>">
            </dd>
        </dl>
        <div>
            <input type="submit" value="投稿する">
        </div>
    </form>

    <?php foreach ($posts as $post): ?>
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
            <p class="day"><a href="view.php?id=<?php echo $id ?>"><?php echo $time ?></a></p>
        </div>
    <?php endforeach ?>    
  </div>

</div>
</body>
</html>
