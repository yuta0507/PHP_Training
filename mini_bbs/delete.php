<?php
require_once('dbconnect.php');
session_start();

if (isset($_SESSION['id'])) {
    $id = $_GET['id'];

    //投稿を検査する
    $messages = $db->prepare('SELECT * from posts WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    if ($message['member_id'] == $_SESSION['id']) {
        //削除する
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($id));
    }
}

header('Location: index.php');
exit();
?>