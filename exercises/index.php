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
session_start();

//データベース参照
if ($_GET['order'] === 'desc') {
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
    
    $href = "index.php";
} else {
    $companies = $db->query(
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

//リンク
$index = "index.php";
$signup = "signup.php";
$employee_index = "employee/index.php?company_id=";
$edit = "edit.php?id=";
$delete = "delete.php?id=";

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Exercise</title>
</head>
<body>
    <script src="scripts/main.js"></script>
    <h1>
        <a href=<?php echo $index ?> class="heading">会社一覧</a>
    </h1>
    <a href=<?php echo $signup ?> class="button">新規登録</a>

    <!-- エラー表示 -->
    <?php if ($_SESSION['signup']['company'] === 'completed') : ?>
        <p class="success">新規登録が完了しました</p>
        <?php unset($_SESSION['signup']['company']) ?>
    <?php endif ?>
    <?php if ($_SESSION['edit']['company'] === 'completed') : ?>
        <p class="success">編集が完了しました</p>
        <?php unset($_SESSION['edit']['company']) ?>
    <?php endif ?>
    <?php if ($_SESSION['delete']['company'] === 'completed') : ?>
        <p class="success">削除が完了しました</p>
        <?php unset($_SESSION['delete']['company']) ?>
    <?php endif ?>

    <!-- ここからテーブル -->
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
                    <th>
                        <?php echo h($company['id']) ?>
                    </th>
                    <th>
                        <a href=<?php echo $employee_index. h($company['id']) ?>>
                            <?php echo h($company['company_name']) ?>
                        </a>
                    </th>
                    <th>
                        <?php echo h($company['representative_name']) ?>
                    </th>
                    <th>
                        <?php echo h($company['phone_number']) ?>
                    </th>
                    <th>
                        <?php echo h($company['address']) ?>
                    </th>
                    <th>
                        <?php echo h($company['mail_address']) ?>
                    </th>
                    <th>
                        <?php echo h($company['cnt']) ?>
                    </th>
                    <th>
                        <a href=<?php echo $edit. h($company['id']) ?>>編集</a>
                    </th>
                    <th>
                        <form name="delete_form" action="delete.php" method="POST">
                            <input type="hidden" name="id" 
                            value="<?php echo h($company['id']) ?>">
                            <input type="submit"
                            onclick="return confirmDelete()" value="削除">
                        </form>
                    </th>
                </tr>
            <?php endforeach ?>    
        </table>
    </div>
</body>
</html>