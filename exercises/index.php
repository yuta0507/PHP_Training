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

//ページング
$page = h($_GET['page']);
if ($page == '') {
    $page = 1;
}

$counts = $db->query('SELECT COUNT(*) AS cnt from companies WHERE deleted IS NULL');
$cnt = $counts->fetch();
$max_page = ceil($cnt['cnt'] / 5);
$page = min($page, $max_page);

$start = ($page- 1) * 5;

$displayed_results = h($_COOKIE['displayed_results']);
if ($_COOKIE['displayed_results'] == '') {
    $displayed_results = 5;
}

//データベース参照
if ($_GET['order'] === 'desc') {
    $companies = $db->prepare(
        'SELECT c.*, COUNT(e.id) AS cnt
        FROM companies c  
        LEFT OUTER JOIN employees e 
        ON c.id = e.company_id 
        WHERE c.deleted IS NULL
        AND e.deleted IS NULL 
        GROUP BY c.id
        ORDER BY c.id DESC
        LIMIT ?, ?'
    );
    $companies->bindParam(1, $start, PDO::PARAM_INT);
    $companies->bindParam(2, $displayed_results, PDO::PARAM_INT);
    $companies->execute();
    
    $href = "index.php?page=" .$page;
} else {
    $companies = $db->prepare(
        'SELECT c.*, COUNT(e.id) AS cnt
        FROM companies c  
        LEFT OUTER JOIN employees e 
        ON c.id = e.company_id 
        WHERE c.deleted IS NULL
        AND e.deleted IS NULL 
        GROUP BY c.id
        ORDER BY c.id ASC
        LIMIT ?, ?'
    );
    $companies->bindParam(1, $start, PDO::PARAM_INT);
    $companies->bindParam(2, $displayed_results, PDO::PARAM_INT);
    $companies->execute();
    
    $href = "index.php?page=" .$page ."&order=desc";
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
<body class="<?php echo $_COOKIE['mode'] ?>">
    <!-- ナビゲーションバー -->
    <nav>
        <ul>
            <li>
                <a href="<?php echo $index ?>">会社一覧</a>
            </li>
            <li>
                <a href="settings.php" class="setting">
                    <img src="images/setting.png">
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
                    <a href="<?php outputHref($index, $page-1, $_GET['order']); ?>">
                        ≪
                    </a>
                </li>
                <?php if ($page > 2) : ?>
                    <li>
                        <a href="<?php outputHref($index, 1, $_GET['order']); ?>">
                            1
                        </a>
                    </li>
                    <li>
                        <span>...</span>
                    </li>
                <?php endif ?>
                <li>
                    <a href="<?php outputHref($index, $page-1, $_GET['order']); ?>">
                        <?php echo $page-1; ?>
                    </a>
                </li>    
            <?php endif ?>
            <li>
                <a href="<?php outputHref($index, $page, $_GET['order']); ?>" class="current-page">
                    <?php echo $page; ?>
                </a>
            </li>
            <?php if ($page < $max_page) : ?>
                <li>
                    <a href="<?php outputHref($index, $page+1, $_GET['order']) ?>">
                        <?php echo $page+1 ?>
                    </a>
                </li>
                <?php if ($page < $max_page - 2) : ?>
                    <li>
                        <span>...</span>
                    </li>
                    <li>
                        <a href="<?php outputHref($index, $max_page, $_GET['order']) ?>">
                            <?php echo $max_page ?>
                        </a>
                    </li>
                <?php endif ?>
                <li>
                    <a href="<?php outputHref($index, $page+1, $_GET['order']) ?>">
                        ≫
                    </a>
                </li>
            <?php endif ?>
        </ul>
    </div>
        
    <script src="scripts/main.js"></script>
</body>
</html>