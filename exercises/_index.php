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
    ORDER BY c.id'
);
$companies->execute();

$i = 0;
$row = ['ID', '会社名', '代表', 'Tel', '住所', 'Mail', '社員数'];
$column = ['id', 'company_name', 'representative_name', 'phone_number', 'address', 'mail_address', 'cnt'];
$column_len = count($column);

foreach ($companies as $company) {    
    for ($j = 0; $j < $column_len; $j++) {
        $table[$i][$column[$j]] = h($company[$column[$j]]);
    }
    $i++;
}

$ids = array_column($table, 'id');
$max_i = $i;

if ($_GET['order'] === 'desc') {
    array_multisort($ids, SORT_DESC, $table);
    $href = "_index.php";
} else {
    array_multisort($ids, SORT_ASC, $table);
    $href = "_index.php?order=desc";
}

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
    <h1>会社一覧</h1>
    <a href="signup.php" class="button">新規登録</a><br>
    <a href=<?php echo $href ?>>並び替え</a>
    <div class="index-table" >
        <table border="1">
            <tr>
                <?php foreach ($row as $value): ?>
                    <th><?php echo $value ?></th>
                <?php endforeach ?>    
            </tr>
            <?php for ($i = 0; $i < $max_i; $i++): ?>
                <tr>
                    <?php for ($j = 0; $j < $column_len; $j++) : ?>
                        <th><?php echo $table[$i][$column[$j]] ?></th>
                    <?php endfor ?>
                </tr>
            <?php endfor ?>
        </table>
    </div>
</body>
</html>