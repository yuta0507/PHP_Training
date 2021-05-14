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

//表示件数
$displayed_results = h($_COOKIE['displayed_results']);
if ($_COOKIE['displayed_results'] == '') {
    $displayed_results = 5;
}

//ページング
$page = $_GET['page'];
if ($page == '') {
    $page = 1;
}

$sql = 'SELECT COUNT(*) AS cnt FROM employees WHERE deleted IS NULL AND company_id=' .$company_id;
$counts = $db->query($sql);
$cnt = $counts->fetch();
$max_page = ceil($cnt['cnt'] / $displayed_results);
$page = min($page, $max_page);

$start = ($page- 1) * $displayed_results;



//データベース参照
if ($_GET['order'] === 'desc') {
    $employees = $db->prepare(
        'SELECT * from employees 
        WHERE company_id=? AND deleted IS NULL ORDER BY id DESC LIMIT ?, ?'
    );
    $employees->bindParam(1, $company_id, PDO::PARAM_INT);
    $employees->bindParam(2, $start, PDO::PARAM_INT);
    $employees->bindParam(3, $displayed_results, PDO::PARAM_INT);
    $employees->execute();

    $href = "index.php?company_id=" .$company_id .'&page=' .$page;
} else {
    $employees = $db->prepare(
        'SELECT * from employees 
        WHERE company_id=? AND deleted IS NULL ORDER BY id ASC LIMIT ?, ?'
    );
    $employees->bindParam(1, $company_id, PDO::PARAM_INT);
    $employees->bindParam(2, $start, PDO::PARAM_INT);
    $employees->bindParam(3, $displayed_results, PDO::PARAM_INT);
    $employees->execute();

    $href = "index.php?company_id=" .$company_id .'&page=' .$page ."&order=desc";
}


//リンク
$company_index = "../index.php";
$employee_index = "index.php?company_id=".$company_id;
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
<body class="<?php echo $_COOKIE['mode'] ?>">
    
    <!-- ナビゲーションバー -->
    <nav>
        <ul>
            <li>
                <a href="<?php echo $company_index ?>">会社一覧</a>
            </li>
            <li>
                <a href="<?php echo $employee_index ?>" class="employee">社員一覧</a>
            </li>
            <li>
                <a href="../settings.php" class="setting">
                    <img src="../images/setting.png">
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="index-container">
        <a href=<?php echo $signup ?> class="button">新規登録</a>
        
        <!-- 新規登録・編集・削除の完了メッセージを表示 -->
        <?php 
        outputCompletionMessage($_SESSION);
        $_SESSION = [];
        session_destroy();
        ?>

        <!-- ここからテーブル -->
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
                            onclick="return outputConfirmationPopup()" value="削除">
                        </form>
                    </th>
                </tr>
                <?php endforeach ?>
            </table>        
        </div>
        <ul class="paging">
            <?php if ($page > 1) : ?>
                <li>
                    <a href="<?php outputHref($employee_index, $page-1, $_GET['order']); ?>">
                        ≪
                    </a>
                </li>
                <?php if ($page > 2) : ?>
                    <li>
                        <a href="<?php outputHref($employee_index, 1, $_GET['order']); ?>">
                            1
                        </a>
                    </li>
                <?php endif ?>
                <?php if ($page > 3 && $max_page != 4) : ?>
                    <li>
                        <span>...</span>
                    </li>
                <?php endif ?>
                <?php if ($page == $max_page && $max_page != 3) : ?>
                    <li>
                        <a href="<?php outputHref($employee_index, $max_page-2, $_GET['order']); ?>">
                            <?php echo $max_page-2; ?>
                        </a>
                    </li>
                <?php endif ?>
                <li>
                    <a href="<?php outputHref($employee_index, $page-1, $_GET['order']); ?>">
                        <?php echo $page-1; ?>
                    </a>
                </li>
            <?php endif ?>
            <li>
                <a href="<?php outputHref($employee_index, $page, $_GET['order']); ?>"　
                class="current-page">
                    <?php echo $page; ?>
                </a>
            </li>
            <?php if ($page < $max_page) : ?>
                <li>
                    <a href="<?php outputHref($employee_index, $page+1, $_GET['order']); ?>">
                        <?php echo $page+1; ?>
                    </a>
                </li>
                <?php if ($page == 1 && $max_page != 3) : ?>
                    <li>
                        <a href="<?php outputHref($employee_index, 3, $_GET['order']); ?>">
                            3
                        </a>
                    </li>
                <?php endif ?>
                <?php if ($page < $max_page - 2 && $max_page != 4) : ?>
                    <li>
                        <span>...</span>
                    </li>
                <?php endif ?>
                <?php if ($page < $max_page - 1) : ?>
                    <li>
                        <a href="<?php outputHref($employee_index, $max_page, $_GET['order']); ?>">
                            <?php echo $max_page; ?>
                        </a>
                    </li>
                <?php endif ?>
                <li>
                    <a href="<?php outputHref($employee_index, $page+1, $_GET['order']); ?>">
                        ≫
                    </a>
                </li>
            <?php endif ?>
        </ul>
    </div>
    <script src="../scripts/main.js"></script>
</body>
</html>