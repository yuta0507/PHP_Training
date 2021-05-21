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
ini_set('display_errors', "On");

require_once'required_files/dbconnect.php';
require_once'required_files/functions.php';
require_once'required_files/paging.php';
session_start();

//表示件数を設定
$displayed_results = h($_COOKIE['displayed_results']);
if (empty($_COOKIE['displayed_results'])) {
    $displayed_results = 5;
}

//検索があった場合、SQLにLIKE文を追加するための処理
if (!empty($_GET['search'])) {
    $search = h($_GET['search']);
    $like_sql = "AND company_name LIKE '%$search%'";
} else {
    $search = "";
    $like_sql = "";
}

//データベース参照し、該当データの数を数える
$counts = $db->query(
    "SELECT COUNT(*) AS cnt from companies WHERE deleted IS NULL $like_sql"
);
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
    if (!empty($search)) {
        $search_href = "&search=$search";
    } else {
        $search_href = "";
    }
    $href = "index.php?page=" .$page .$search_href;
    
    //昇順or降順の情報をを変数に格納
    $order = h($_GET['order']);
} else {
    //並び替えIDボタンのURL作成
    if (!empty($search)) {
        $search_href = "&search=$search";
    } else {
        $search_href = "";
    }
    $href = "index.php?page=" .$page .$search_href."&order=desc";
    
    //昇順or降順の情報をを変数に格納
    $order = "asc";
}

//データベース参照
$companies = $db->prepare(
    "SELECT c.*, COUNT(e.id) AS cnt
    FROM companies c  
    LEFT OUTER JOIN employees e 
    ON c.id = e.company_id 
    WHERE c.deleted IS NULL
    AND e.deleted IS NULL 
    $like_sql
    GROUP BY c.id
    ORDER BY c.id $order
    LIMIT ?, ?"
);
$companies->bindParam(1, $start, PDO::PARAM_INT);
$companies->bindParam(2, $displayed_results, PDO::PARAM_INT);
$companies->execute();

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
<body class="<?php 
if (!empty($_COOKIE['mode'])) {
    echo $_COOKIE['mode'];
}  
?>">
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
        <div class="search">
            <form action="" method="get">
                <label for="search">
                    会社名検索：
                    <input type="text" name="search" id="search">
                </label>
                <input type="submit" value="検索" class="search-button">
            </form>
        </div>
        
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
                            <form name="delete_form" action="delete.php" 
                            method="POST">
                                <input type="hidden" name="id" 
                                value="<?php echo h($company['id']) ?>">
                                <input type="submit"
                                onclick="return outputConfirmationPopup()" 
                                value="削除">
                            </form>
                        </th>
                    </tr>
                <?php endforeach ?>    
            </table>
        </div>
        <!-- ページング -->
        <?php outputPaging($index, $page, $max_page, $search, $order); ?>
    </div>
        
    <script src="scripts/main.js"></script>
</body>
</html>