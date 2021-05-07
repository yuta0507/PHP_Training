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
    if (!empty($value2)) {
        return $value2;
    } 
    return $value1;
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

/**
 * Function checkPostalCode.
 * 
 * @param string $value 文字列
 * 
 * @return string
 * */
function setPrefecture($value)
{
    $prefectures = [
        "北海道", "青森県", "岩手県", "宮城県", "秋田県", 
        "山形県", "福島県", "茨城県", "栃木県", "群馬県", 
        "埼玉県", "千葉県", "東京都", "神奈川県", "新潟県", 
        "富山県", "石川県", "福井県", "山梨県", "長野県", 
        "岐阜県", "静岡県", "愛知県", "三重県", "滋賀県", 
        "京都府", "大阪府", "兵庫県", "奈良県", "和歌山県",
        "鳥取県", "島根県", "岡山県", "広島県", "山口県", 
        "徳島県", "香川県", "愛媛県", "高知県", "福岡県", 
        "佐賀県", "長崎県", "熊本県", "大分県", "宮崎県", 
        "鹿児島県", "沖縄県"
    ];

    return $prefectures[$value-1];
}

?>