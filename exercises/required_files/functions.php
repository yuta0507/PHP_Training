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