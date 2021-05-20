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
ini_set('display_errors', "On");

require_once'../required_files/dbconnect.php';
require_once'../required_files/functions.php';
session_start();


//company_idがない場合は会社一覧に戻る
if (empty($_GET['company_id'])) {
    header('Location: ../index.php');
    exit();
} else {
    $company_id = h($_GET['company_id']);
}

//表示件数を設定
$displayed_results = h($_COOKIE['displayed_results']);
if ($_COOKIE['displayed_results'] == '') {
    $displayed_results = 5;
}

//データベース参照し、該当データの数を数える
$sql = 'SELECT COUNT(*) AS cnt FROM employees 
        WHERE deleted IS NULL AND company_id=' .$company_id;
$counts = $db->query($sql);
$cnt = $counts->fetch();

//ページング
//現在のページと最大ページを設定
if (!empty($_GET['page'])) {
    $page = h($_GET['page']);
} else {
    $page = 1;
}
$max_page = ceil($cnt['cnt'] / $displayed_results);
if ($max_page == 0) {
    $max_page = 1;
    $page = 1;
} else {
    //URLで最大ページより大きな数を指定されても、最大ページを表示する
    $page = min($page, $max_page);
}

//ページの先頭のデータを設定
$start = ($page- 1) * $displayed_results;

if (!empty($_GET['order']) && $_GET['order'] === 'desc') {
    //並び替えIDボタンのURL作成
    $href = "index.php?company_id=" .$company_id .'&page=' .$page;
    
    //昇順or降順の情報を変数に格納
    $order = h($_GET['order']);
} else {
    //並び替えIDボタンのURL作成
    $href = "index.php?company_id=" .$company_id .'&page=' .$page ."&order=desc";
    
    //昇順or降順の情報を変数に格納
    $order = "asc";
}

//データベース参照
$employees = $db->prepare(
    "SELECT * from employees 
    WHERE company_id=? AND deleted IS NULL ORDER BY id $order LIMIT ?, ?"
);
$employees->bindParam(1, $company_id, PDO::PARAM_INT);
$employees->bindParam(2, $start, PDO::PARAM_INT);
$employees->bindParam(3, $displayed_results, PDO::PARAM_INT);
$employees->execute();


//リンク
$company_index = "../index.php";
$employee_index = "index.php?company_id=".$company_id;
$signup = "signup.php?company_id=".$company_id;
$edit = "edit.php?company_id=".$company_id."&id=";
$delete = "delete.php?company_id=".$company_id;
$member_pics = "../images/member_pictures/"
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
<body class="<?php 
if (!empty($_COOKIE['mode'])) {
    echo $_COOKIE['mode'];
}  
?>">
    
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
                            <?php if ($employee['picture'] !== null) : ?>
                                <img class="icon" 
                                src="<?php 
                                echo $member_pics .h($employee['picture']) ?>">
                            <?php endif ?>
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
                    <a href="<?php
                    outputHref($employee_index, $page-1, null, $order);
                    ?>">
                        
                        ≪
                    </a>
                </li>
                <?php if ($page > 2) : ?>
                    <li>
                        <a href="<?php
                        outputHref($employee_index, 1, null, $order);
                        ?>">
                            1
                        </a>
                    </li>
                <?php endif ?>
                <?php if ($page > 3 && $max_page != 4) : ?>
                    <li>
                        <span>...</span>
                    </li>
                <?php endif ?>
                <?php if ($page == $max_page && $max_page != 3 && $max_page != 2) : ?>
                    <li>
                        <a href="<?php
                        outputHref($employee_index, $max_page-2, null, $order);
                        ?>">
                            <?php echo $max_page-2; ?>
                        </a>
                    </li>
                <?php endif ?>
                <li>
                    <a href="<?php
                    outputHref($employee_index, $page-1, null, $order);
                    ?>">
                        <?php echo $page-1; ?>
                    </a>
                </li>    
            <?php endif ?>
            <li>
                <a href="<?php
                outputHref($employee_index, $page, null, $order);
                ?>" 
                class="current-page">
                    <?php echo $page; ?>
                </a>
            </li>
            <?php if ($page < $max_page) : ?>
                <li>
                    <a href="<?php
                    outputHref($employee_index, $page+2, null, $order);
                    ?>">
                        <?php echo $page+1 ?>
                    </a>
                </li>
                <?php if ($page == 1 && $max_page != 3 && $max_page != 2) : ?>
                    <li>
                        <a href="<?php
                        outputHref($employee_index, 3, null, $order);
                        ?>">
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
                        <a href="<?php
                        outputHref($employee_index, $max_page, null, $order);
                        ?>">
                            <?php echo $max_page; ?>
                        </a>
                    </li>
                <?php endif ?>    
                <li>
                    <a href="<?php 
                    outputHref($employee_index, $page+1, null, $order);
                    ?>">
                        ≫
                    </a>
                </li>
            <?php endif ?>
        </ul>
    </div>
    <script src="../scripts/main.js"></script>
</body>
</html>