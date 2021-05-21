<?php
/** 
 * 作成したファンクションを管理
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Functions
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/required_files/functions.php
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
 * Function validateInputData.
 * 
 * @param string $input_data    文字列
 * @param array  $column        配列
 * @param array  $column_length 配列
 * @param string $input_file    文字列
 * 
 * @return array
 * */
function validateInputData($input_data, $column, $column_length, $input_file)
{
    //未入力チェック   
    foreach ($column as $column_name) {
        if ($input_data[$column_name] == '') {
            $error['blank'] = 'true';
        }
    }
    
    //電話番号半角数字チェック
    $phone_number = $input_data['phone_number'];
    if (!empty($phone_number) && isPhoneNumberValid($phone_number) === false) {
        $error['phone_number'] = 'wrong';
    }
    
    //郵便番号半角数字チェック
    $postal_code = $input_data['postal_code'];
    if (!empty($postal_code) && isPostalCodeValid($postal_code) === false) {
        $error['postal_code'] = 'wrong';
    }
    
    //文字数制限チェック
    $i = 0;
    foreach ($column as $column_name) {
        if (mb_strlen($input_data[$column_name]) > $column_length[$i]) {
            $error[$column_name] = 'length';
        }
        $i++;
    }
    
    //都道府県コードチェック
    if (!empty($input_data['prefectures_code'])) {
        $prf_code = $input_data['prefectures_code']; 
        if ($prf_code < 1 || 47 < $prf_code) {
            $error['prefectures_code'] = 'false';
        }
    }

    //アイコン画像チェック
    if ($input_file !== null) {
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
        //長さチェック
        if (mb_strlen($file_name) > 241) {
            $error['image'] = 'length';
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
    //市外局番、携帯電話、フリーダイヤルのうち、どれか1つにマッチすればtrue
    $patterns = [
        "/^0(\d-\d{4}|\d{2}-\d{3}|\d{3}-\d{2}|\d{4}-\d)-\d{4}$/",
        "/^0([7-9])0-\d{4}-\d{4}$/",
        "/^0120-\d{3}-\d{3}$/"
    ];
    
    $i = 0;
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
 * @return null
 * */
function outputErrorMessage($error)
{
    if (!empty($error['blank']) && $error['blank'] === 'true') {
        echo '<p class="error">*入力されていない箇所があります。再度入力してください</p>';
    }
    if (!empty($error['company_name']) && $error['comapny_name'] === 'length') {
        echo '<p class="error">*会社名は50字以内で入力してください</p>';
    }
    if (!empty($error['employee_name']) && $error['employee_name'] === 'length') {
        echo '<p class="error">*社員名は20字以内で入力してください</p>';
    }
    if (!empty($error['representative_name'])) {
        if ($error['representative_name'] === 'length') {
            echo '<p class="error">*代表名は20字以内で入力してください</p>';
        }
    }
    if (!empty($error['division_name']) && $error['division_name'] === 'length') {
        echo '<p class="error">*部署名は20字以内で入力してください</p>';
    }
    if (!empty($error['phone_number']) && $error['phone_number'] === 'wrong') {
        echo '<p class="error">*電話番号はハイフン付きの半角数字で入力してください</p>';
    }
    if (!empty($error['phone_number']) && $error['phone_number'] === 'length') {
        echo '<p class="error">*電話番号は13字以内で入力してください</p>';
    }
    if (!empty($error['postal_code']) && $error['postal_code'] === 'wrong') {
        echo '<p class="error">*郵便番号はハイフン付きの半角数字で入力してください</p>';
    }
    if (!empty($error['postal_code']) && $error['postal_code'] === 'length') {
        echo '<p class="error">*郵便番号は8字で入力してください</p>';
    }
    if (!empty($error['prefectures_code'])) {
        if ($error['prefectures_code'] === 'false') {
            echo '<p class="error">*不整合な都道府県データが検出されました</p>';
        }
    }
    if (!empty($error['address']) && $error['address'] === 'length') {
        echo '<p class="error">*住所は100字以内で入力してください</p>';
    }
    if (!empty($error['mail_address']) && $error['mail_address'] === 'length') {
        echo '<p class="error">*メールアドレスは100字以内で入力してください</p>';
    }
    if (!empty($error['image']) && $error['image'] === 'length') {
        echo '<p class="error">*画像のファイル名は241字以内に設定してください</p>';
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
 * @return null
 * */
function outputCompletionMessage($value)
{
    define('COMPLETION_MSG_SIGNUP', '新規登録が完了しました');
    define('COMPLETION_MSG_EDIT', '編集が完了しました');
    define('COMPLETION_MSG_DELETE', '削除が完了しました');

    //Company
    if (!empty($value['signup']['company'])) {
        if ($value['signup']['company'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_SIGNUP.'</p>';
        }
    }
    if (!empty($value['edit']['company'])) {
        if ($value['edit']['company'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_EDIT.'</p>';
        }
    }
    if (!empty($value['delete']['company'])) {
        if ($value['delete']['company'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_DELETE.'</p>';
        }
    }

    //Employee
    if (!empty($value['signup']['employee'])) {
        if ($value['signup']['employee'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_SIGNUP.'</p>';
        }
    }
    if (!empty($value['edit']['employee'])) {
        if ($value['edit']['employee'] === 'completed') {
            echo '<p class="success">'. COMPLETION_MSG_EDIT.'</p>';
        }
    }
    if (!empty($value['delete']['employee'])) {
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
 * @return null
 * */
function outputPrefecture($value)
{
    if (1 <= $value && $value <= 47 ) {
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
}
?>