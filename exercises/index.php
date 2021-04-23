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
if ($_GET['order'] === 'desc') {
    $companies = $db->query(
        // 'SELECT * from companies WHERE deleted IS NULL ORDER BY id DESC'
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
    
    $href = "index.php";
} else {
    $companies = $db->query(
        // 'SELECT * from companies WHERE deleted IS NULL ORDER BY id ASC'
        'SELECT c.*, COUNT(e.id) AS cnt
        FROM companies c  
        LEFT OUTER JOIN employees e 
        ON c.id = e.company_id 
        WHERE c.deleted IS NULL
        AND e.deleted IS NULL 
        GROUP BY c.id
        ORDER BY c.id ASC'
    );
    $companies->execute();
    
    $href = "index.php?order=desc";
}

?>
<!DOCTYPE html>
<html lang="ja">
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
            <?php foreach ($companies as $company): ?>
                <tr>
                    <th><?php echo h($company['id']) ?></th>
                    <th>
                        <a href="employee/index.php
                        ?company_id=<?php echo h($company['id']) ?>">
                            <?php echo h($company['company_name']) ?>
                        </a>
                    </th>
                    <th><?php echo h($company['representative_name']) ?></th>
                    <th><?php echo h($company['phone_number']) ?></th>
                    <th><?php echo h($company['address']) ?></th>
                    <th><?php echo h($company['mail_address']) ?></th>
                    <th><?php echo h($company['cnt']) ?></th>
                    <th>
                        <a href="edit.php?id=<?php echo h($company['id']) ?>">編集</a>
                    </th>
                    <th><
                        a href="delete.php?id=<?php echo h($company['id']) ?>">削除</a>
                    </th>
                </tr>
            <?php endforeach ?>    
        </table>
    </div>
</body>
</html>