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
<?php
$memos = $db->prepare('SELECT * FROM memos WHERE id=?');
$memos->execute(array($_GET['id']));
$memo = $memos->fetch();
?>
<article>
    <pre><?php echo $memo['memo'] ?></pre>
    <a href="index.php">Back</a>
    |
    <a href="update.php?id=<?php echo $memo['id'] ?>">Edit</a>
    |
    <a href="delete.php?id=<?php echo $memo['id'] ?>">Delete</a>
</article>

</main>
</body>    
</html>