<?php
/** 
 * データベースに接続 
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Dbconnect
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/required_files/dbconnect.php
 * */ 

try {
    $db = new PDO(
        'mysql:dbname=programing_training_2019;
        host=127.0.0.1;charset=utf8mb4', 'root', 'P@ssw0rd'
    );
} catch (PDOException $e) {
    echo 'DB接続エラー：'. $e->getMessage();
}
?>