<?php
/** 
 * 作成したファンクションを管理
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Function
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/required_files/function.php
 * */

/**
 * Function h.
 * 
 * @param string $value 文字列
 * 
 * @return string
 * */
function h($value) 
{
    return htmlspecialchars($value, ENT_QUOTES);
}

/**
 * Function setValue.
 * 
 * @param string $value1 文字列
 * @param string $value2 文字列
 * 
 * @return string
 * */
function setValue($value1, $value2) 
{
    if (empty($_POST)) {
        return $value1; 
    } else {
        return $value2;
    }
}

/**
 * Function checkPhoneNumber.
 * 
 * @param string $value 文字列
 * 
 * @return string
 * */
function checkPhoneNumber($value)
{
    $i=0;
    $patterns = [
        "/^0(\d-\d{4}|\d{2}-\d{3}|\d{3}-\d{2}|\d{4}-\d)-\d{4}$/",
        "/^0([7-9])0-\d{4}-\d{4}$/",
        "/^0120-\d{3}-\d{3}$/"
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $value)) {
            $i++;
        }
    }

    if ($i === 0) {
        return false;
    }
        
}

/**
 * Function checkPostalCode.
 * 
 * @param string $value 文字列
 * 
 * @return string
 * */
function checkPostalCode($value)
{
    $pattern = "/^\d{3}-\d{4}$/";
    if (!preg_match($pattern, $value)) {
        return false;
    }
}

// function bubble_sort_asc($numbers) 
// {
//     $len_numbers = count($numbers);
//     for ($i = 0; $i < $len_numbers; $i++) {
//         for ($j = 0; $j < $len_numbers - 1 - $i; $j++) {
//             if ($numbers[$j] > $numbers[$j+1]) {
//                 $tmp = $numbers[$j];
//                 $numbers[$j] = $numbers[$j+1];
//                 $numbers[$j+1] = $tmp;
//             }
//         }
//     }
//     return $numbers;
// }

// function bubble_sort_desc($numbers) 
// {
//     $len_numbers = count($numbers);
//     for ($i = 0; $i < $len_numbers; $i++) {
//         for ($j = 0; $j < $len_numbers - 1 - $i; $j++) {
//             if ($numbers[$j] < $numbers[$j+1]) {
//                 $tmp = $numbers[$j];
//                 $numbers[$j] = $numbers[$j+1];
//                 $numbers[$j+1] = $tmp;
//             }
//         }
//     }
//     return $numbers;
// }

?>