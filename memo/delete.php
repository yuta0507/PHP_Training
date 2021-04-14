<?php require_once('dbconnect.php') ?>
<!doctype html>
<html lang="ja">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="style.css">

<title>よくわかるPHPの教科書</title>
</head>
<body>
<header>
<h1 class="font-weight-normal">よくわかるPHPの教科書</h1>
</header>

<main>
<h2>Practice</h2>
<pre>
<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $memos = $db->prepare('SELECT * from memos WHERE id=?');
    $memos->execute(array($id));
    $memo = $memos->fetch();
}
echo $memo['memo'];
?>
</pre>
<p>Are you sure to delete?</p>
<a href="memo.php?id=<?php echo $memo['id'] ?>">No</a>
|
<a href="delete_do.php?id=<?php echo $memo['id'] ?>">Yes</a>
</main>
</body>    
</html>