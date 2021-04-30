<?php
/** 
 * 社員一覧 
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Index
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/employee/index.php
 * */ 

require_once'../required_files/dbconnect.php';
require_once'../required_files/functions.php';
session_start();

$company_id = h($_GET['company_id']);

//company_idがない場合は会社一覧に戻る
if (empty($company_id)) {
    header('Location: ../index.php');
    exit();
}

//データベース参照
if ($_GET['order']) {
    $employees = $db->prepare(
        'SELECT * from employees 
        WHERE company_id=? AND deleted IS NULL ORDER BY id DESC'
    );
    $employees->execute([$company_id]);

    $href = "index.php?company_id=".$company_id;
} else {
    $employees = $db->prepare(
        'SELECT * from employees 
        WHERE company_id=? AND deleted IS NULL ORDER BY id ASC'
    );
    $employees->execute([$company_id]);

    $href = "index.php?company_id=".$company_id."&order=desc";
}


//リンク
$index = "index.php?company_id=".$company_id;
$signup = "signup.php?company_id=".$company_id;
$edit = "edit.php?company_id=".$company_id."&id=";
$delete = "delete.php?company_id=".$company_id;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Exercise</title>
</head>
<body>
    <script src="../scripts/main.js"></script>
    <h1><a href=<?php echo $index ?> class="heading">社員一覧</a></h1>
    <a href=<?php echo $signup ?> class="button">新規登録</a>
    <?php if ($_SESSION['signup']['employee'] === 'completed') : ?>
        <p class="success">新規登録が完了しました</p>
        <?php unset($_SESSION['signup']['employee']) ?>
    <?php endif ?>
    <?php if ($_SESSION['edit']['employee'] === 'completed') : ?>
        <p class="success">編集が完了しました</p>
        <?php unset($_SESSION['edit']['employee']) ?>
    <?php endif ?>
    <?php if ($_SESSION['delete']['employee'] === 'completed') : ?>
        <p class="success">削除が完了しました</p>
        <?php unset($_SESSION['delete']['employee']) ?>
    <?php endif ?>
    <div class="index-table" >
        <table border="1">
            <tr>
                <th>
                    <a href=<?php echo $href ?>>ID</a>
                </th>
                <th>社員名</th>
                <th>部署</th>
                <th>Tel</th>
                <th>住所</th>
                <th>Mail</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
            <?php foreach ($employees as $employee) : ?>
                <tr>
                    <th>
                        <?php echo h($employee['id']) ?>
                    </th>
                    <th>
                        <?php echo h($employee['employee_name']) ?>
                    </th>
                    <th>
                        <?php echo h($employee['division_name']) ?>
                    </th>
                    <th>
                        <?php echo h($employee['phone_number']) ?>
                    </th>
                    <th>
                        <?php echo h($employee['address']) ?>
                    </th>
                    <th>
                        <?php echo h($employee['mail_address']) ?>
                    </th>
                    <th>
                        <a href=<?php echo $edit. h($employee['id']) ?>>編集</a>
                    </th>
                    <th>
                        <form name="delete_form" 
                        action="<?php echo $delete ?>" method="POST">
                            <input type="hidden" name="id" 
                            value="<?php echo h($employee['id']) ?>">
                            <input type="submit" 
                            onclick="return confirmDeletion()" value="削除">
                        </form>
                    </th>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</body>
</html>