<?PHP
/** 
 * 会社一覧 
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Delete
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/index.php
 * */ 

require_once'required_files/dbconnect.php';

$id = $_GET['id'];

$statement = $db->prepare(
    'UPDATE companies SET deleted=NOW() WHERE id=?'
);
$statement->execute(array($id));

header('Location: index.php');
exit();

?>