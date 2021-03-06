<?php
/** 
 * 社員削除
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Delete
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/employee/delete.php
 * */

require_once'../required_files/dbconnect.php';
require_once'../required_files/functions.php';
session_start();

$company_id = h($_GET['company_id']);
$id = h($_POST['id']);

if (empty($id)) {
    header('Location: ../index.php');
} else {
    //削除記録がないときのみ実行
    $statement = $db->prepare(
        'UPDATE employees SET modified=NOW(), deleted=NOW()
        WHERE id=? AND deleted IS NULL'
    );
    $statement->bindParam(1, $id, PDO::PARAM_INT);
    $statement->execute([$id]);

    $_SESSION['delete']['employee'] = 'completed';
    $url = "index.php?company_id=".$company_id;
    header('Location:'.$url);
    exit();
}


?>