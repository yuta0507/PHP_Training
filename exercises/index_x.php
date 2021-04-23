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

//IDによる昇順降順の並び替えbySQL
// if (!empty($_GET['order'])) {
//     $companies = $db->query(
//         'SELECT * from companies ORDER BY id DESC'
//     );
//     $companies->execute();    
// } else {
//     $companies = $db->query(
//         'SELECT * from companies ORDER BY id ASC'
//     );
//     $companies->execute();
// }

//IDによる昇順降順の並び替えbyPHPProgram
$companies = $db->prepare(
    'SELECT * from companies'
);
$companies->execute();

$i = 0;
foreach ($companies as $company) {
    $table[$i]['id'] = $company['id'];
    $table[$i]['company_name'] = $company['company_name'];
    $table[$i]['representative_name'] = $company['representative_name'];
    $table[$i]['phone_number'] = $company['phone_number'];
    $table[$i]['postal_code'] = $company['prefectures_code'];
    $table[$i]['address'] = $company['address'];
    $table[$i]['mail_address'] = $company['mail_address'];   
    $table[$i]['created'] = $company['created'];
    $table[$i]['modified'] = $company['modified'];
    $table[$i]['deleted'] = $company['deleted'];
    $i++;
}

$max_i = $i;

//削除された会社のIDを登録
$deleted_companies = $db->query(
    'SELECT id from companies WHERE deleted IS NOT NULL'
);
$deleted_companies->execute();

foreach ($deleted_companies as $deleted_company) {
    $deleted_id[] = ($deleted_company['id']);
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
                <?php if ($URI == '/exercises/index_x.php' ) : ?>
                    <th><a href="index_x.php?order=desc">ID</a></th>
                <?php endif ?>
                <?php if ($URI == '/exercises/index_x.php?order=desc' ) : ?>
                    <th><a href="index_x.php">ID</a></th>
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
            <?php if ($_GET['order'] === 'desc') : ?>
                <?php for ($j=$max_i-1; $j>=0; $j--): ?>
                    <?php if (!array_search($table[$j]['id'], $deleted_id)) : ?>    
                        <tr>
                            <th><?php echo $table[$j]['id'] ?></th>
                            <th><?php echo $table[$j]['company_name'] ?></th>
                            <th><?php echo $table[$j]['representative_name'] ?></th>
                            <th><?php echo $table[$j]['phone_number'] ?></th>
                            <th><?php echo $table[$j]['address'] ?></th>
                            <th><?php echo $table[$j]['mail_address'] ?></th>
                            <th></th>
                            <th><a href="edit.php?id=<?php echo $table[$j]['id'] ?>">編集</a></th>
                            <th><a href="delete.php?id=<?php echo $table[$j]['id'] ?>">削除</a></th>
                        </tr>
                    <?php endif ?>    
                <?php endfor ?>
            <?php else : ?>
                <?php for ($j=0; $j<$max_i; $j++): ?>
                    <?php if (!array_search($table[$j]['id'], $deleted_id)) : ?>    
                        <tr>
                            <th><?php echo $table[$j]['id'] ?></th>
                            <th><?php echo $table[$j]['company_name'] ?></th>
                            <th><?php echo $table[$j]['representative_name'] ?></th>
                            <th><?php echo $table[$j]['phone_number'] ?></th>
                            <th><?php echo $table[$j]['address'] ?></th>
                            <th><?php echo $table[$j]['mail_address'] ?></th>
                            <th></th>
                            <th><a href="edit.php?id=<?php echo $table[$j]['id'] ?>">編集</a></th>
                            <th><a href="delete.php?id=<?php echo $table[$j]['id'] ?>">削除</a></th>
                        </tr>
                    <?php endif ?>    
                <?php endfor ?>
            <?php endif ?>        
        </table>
    </div>
</body>
</html>