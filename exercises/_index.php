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

//IDによる昇順降順の並び替えbyPHPProgram
$companies = $db->query(
    'SELECT c.*, COUNT(e.id) AS cnt
    FROM companies c  
    LEFT OUTER JOIN employees e 
    ON c.id = e.company_id 
    WHERE c.deleted IS NULL
    AND e.deleted IS NULL 
    GROUP BY c.id
    ORDER BY c.id DESC'
);
$companies->execute();

$i = 0;
$column = ['id', 'company_name', 'representative_name', 'phone_number','address', 'mail_address', 'cnt'];
$column_len = count($column);

foreach ($companies as $company) {
    for ($k = 0; $k < $column_len; $k++) {
        $table[$i][$column[$k]] = h($company[$column[$k]]);
    }
    $i++;
}

$max_i = $i;

if ($_GET['order'] === 'desc') {
    $href = "_index.php";
} else {
    $href = "_index.php?order=desc";
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
                <th><a href=<?php echo $href ?>>ID</a></th>
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
                <?php for ($i = 0; $i < $max_i; $i++): ?>
                    <tr>
                        <?php for ($j = 0; $j < $column_len; $j++) : ?>
                            <th><?php echo $table[$i][$column[$j]] ?></th>
                        <?php endfor ?>
                            <th><a href="edit.php?id=<?php echo $table[$i]['id'] ?>">編集</a></th>
                            <th><a href="delete.php?id=<?php echo $table[$i]['id'] ?>">削除</a></th>
                    </tr>
                <?php endfor ?>
            <?php else : ?>
                <?php for ($i = $max_i-1; $i >= 0; $i--): ?>
                    <tr>
                        <?php for ($j = 0; $j < $column_len; $j++) : ?>
                            <th><?php echo $table[$i][$column[$j]] ?></th>
                        <?php endfor ?>
                        <th><a href="edit.php?id=<?php echo $table[$i]['id'] ?>">編集</a></th>
                        <th><a href="delete.php?id=<?php echo $table[$i]['id'] ?>">削除</a></th>
                    </tr>
                <?php endfor ?>
            <?php endif ?>        
        </table>
    </div>
</body>
</html>