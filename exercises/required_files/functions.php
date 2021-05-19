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
ini_set('display_errors', "On");


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
 * Function selectValue.
 * 
 * @param string $db_table    文字列
 * @param string $input_data  文字列
 * @param string $column_name 文字列
 * 
 * @return string
 * */
function selectValue($db_table, $input_data, $column_name) 
{
    if (!empty($input_data)) {
        return h($input_data[$column_name]);
    } 
    return $db_table[$column_name];
}

/**
 * Function isInputValid.
 * 
 * @param string $input_data 文字列
 * @param array  $column     文字列
 * @param string $input_file 文字列
 * 
 * @return string
 * */
function validateInputData($input_data, $column, $input_file)
{
    //未入力チェック   
    foreach ($column as $column_name) {
        if ($input_data[$column_name] == '') {
            $error['blank'] = 'true';
        }
    }
    
    //電話番号半角数字チェック
    $phone_number = h($input_data['phone_number']);
    if (!empty($phone_number) && isPhoneNumberValid($phone_number) === false) {
        $error['phone_number'] = 'wrong';
    }
    
    //郵便番号半角数字チェック
    $postal_code = h($input_data['postal_code']);
    if (!empty($postal_code) && isPostalCodeValid($postal_code) === false) {
        $error['postal_code'] = 'wrong';
    }
    
    //アイコン画像チェック
    $file_name = $input_file['image']['name'];
    //未入力チェック
    if (empty($file_name)) {
        $error['blank'] = 'true';
    }
    //拡張子チェック
    if (!empty($file_name)) {
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png') {
            $error['image'] = 'type';
        }
    }
    
    return $error;
}

/**
 * Function isPhoneNumberValid.
 * 
 * @param string $value 文字列
 * 
 * @return boolean
 * */
function isPhoneNumberValid($value)
{
    $i = 0;
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

    return true;
}

/**
 * Function isPostalCodeValid.
 * 
 * @param string $value 文字列
 * 
 * @return boolean
 * */
function isPostalCodeValid($value)
{
    $pattern = "/^\d{3}-\d{4}$/";
    if (!preg_match($pattern, $value)) {
        return false;
    }

    return true;
}

/**
 * Function outputError.
 * 
 * @param string $error 文字列
 * 
 * @return string
 * */
function outputErrorMessage($error)
{
    if (!empty($error['blank']) && $error['blank'] === 'true') {
        echo '<p class="error">*入力されていない箇所があります。再度入力してください</p>';
    }
    if (!empty($error['phone_number']) && $error['phone_number'] === 'wrong') {
        echo '<p class="error">*電話番号はハイフン付きの半角数字で入力してください</p>';
    }
    if (!empty($error['postal_code']) && $error['postal_code'] === 'wrong') {
        echo '<p class="error">*郵便番号はハイフン付きの半角数字で入力してください</p>';
    }
    if (!empty($error['image']) && $error['image'] === 'type') {
        echo '<p class="error">*画像は「.jpg」「.jpeg」または「.png」のものを指定してください</p>';
    }
}

/**
 * Function outputCompleted.
 * 
 * @param string $value 文字列
 * 
 * @return string
 * */
function outputCompletionMessage($value)
{
    define('COMPLETION_MSG_SIGNUP', '新規登録が完了しました');
    define('COMPLETION_MSG_EDIT', '編集が完了しました');
    define('COMPLETION_MSG_DELETE', '削除が完了しました');

    //Company
    if (!empty($value)) {
        if ($value['signup']['company'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_SIGNUP.'</p>';
        }
        if ($value['edit']['company'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_EDIT.'</p>';
        }
        if ($value['delete']['company'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_DELETE.'</p>';
        }
    }

    //Employee
    if (!empty($value)) {
        if ($value['signup']['employee'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_SIGNUP.'</p>';
        }
        if ($value['edit']['employee'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_EDIT.'</p>';
        }
        if ($value['delete']['employee'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_DELETE.'</p>';
        }
    }
}

/**
 * Function outputPrefecture.
 * 
 * @param string $value 文字列
 * 
 * @return string
 * */
function outputPrefecture($value)
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

    echo $prefectures[$value-1];
}

/**
 * Function outputHref.
 * 
 * @param string $index 文字列
 * @param string $page  文字列
 * @param string $order 文字列
 * 
 * @return string
 * */
function outputHref($index, $page, $order) 
{
    $pattern = '/company_id/';
    
    //Company
    if (preg_match($pattern, $index) === 0) {
        echo $index .'?page=' .$page; 
        if ($order === 'desc') {
            echo "&order=desc";
        }
    }

    //Employee
    if (preg_match($pattern, $index) === 1) {
        echo $index .'&page=' .$page; 
        if ($order === 'desc') {
            echo "&order=desc";
        } 
    }
}
?>