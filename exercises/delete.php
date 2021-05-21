<?PHP
/** 
 * 会社削除
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Delete
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/delete.php
 * */ 
ini_set('display_errors', "On");

require_once'required_files/dbconnect.php';
require_once'required_files/functions.php';
session_start();

if (!empty($_POST['id'])) {
    $id = h($_POST['id']);
}

if (empty($id)) {
    //直接URLが叩かれた場合、index.phpに遷移
    header('Location: index.php');
    exit();
} else {
    //削除記録がないときのみ実行
    $statement = $db->prepare(
        'UPDATE companies SET modified=NOW(), deleted=NOW() 
        WHERE id=? AND deleted IS NULL'
    );
    $statement->bindParam(1, $id, PDO::PARAM_INT);
    $statement->execute();

    $_SESSION['delete']['company'] = 'completed';
    header('Location: index.php');
    exit();
}
?>
