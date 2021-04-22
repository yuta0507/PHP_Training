<?php
/** 
 * 会社一覧 
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Index
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/index.php
 * */ 

require_once'required_files/dbconnect.php';
require_once'required_files/functions.php';

//IDによる昇順降順の並び替え
if (!empty($_GET['order'])) {
    $companies = $db->query(
        'SELECT * from companies ORDER BY id DESC'
    );
    $companies->execute();    
} else {
    $companies = $db->query(
        'SELECT * from companies ORDER BY id ASC'
    );
    $companies->execute();
}

//削除された会社のIDを登録
$deleted_companies = $db->query(
    'SELECT id from companies WHERE deleted IS NOT NULL'
);
$deleted_companies->execute();

foreach ($deleted_companies as $deleted_company) {
    $deleted_id[] = h($deleted_company['id']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Exercise</title>
</head>
<body>
    <h1>会社一覧</h1>
    <a href="signup.php" class="button">新規登録</a>
    <div class="index-table" >
        <table border="1">
            <tr>
                <?php $URI = $_SERVER['REQUEST_URI'] ?>
                <?php if ($URI == '/exercises/index.php' ) : ?>
                    <th><a href="index.php?order=desc">ID</a></th>
                <?php endif ?>
                <?php if ($URI == '/exercises/index.php?order=desc' ) : ?>
                    <th><a href="index.php">ID</a></th>
                <?php endif ?>
                <th>会社名</th>
                <th>代表</th>
                <th>Tel</th>
                <th>住所</th>
                <th>Mail</th>
                <th>社員数</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
            <?php foreach ($companies as $company): ?>
                <?php if (!array_search(h($company['id']), $deleted_id)) : ?>    
                    <tr>
                        <th><?php echo h($company['id']) ?></th>
                        <th><?php echo h($company['company_name']) ?></th>
                        <th><?php echo h($company['representative_name']) ?></th>
                        <th><?php echo h($company['phone_number']) ?></th>
                        <th><?php echo h($company['address']) ?></th>
                        <th><?php echo h($company['mail_address']) ?></th>
                        <th></th>
                        <th><a href="edit.php?id=<?php echo h($company['id']) ?>">編集</a></th>
                        <th><a href="delete.php?id=<?php echo h($company['id']) ?>">削除</a></th>
                    </tr>
                <?php endif ?>    
            <?php endforeach ?>    
        </table>
    </div>
</body>
</html>