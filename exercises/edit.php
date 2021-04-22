<?php
/** 
 * 会社情報編集 
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Edit
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/edit.php
 * */ 

require_once'required_files/dbconnect.php';
require_once'required_files/functions.php';

//エラー項目確認
if (!empty($_POST)) {
    if ($_POST['company_name'] == '') {
        $error['company_name'] = 'blank';
    }
    if ($_POST['representative_name'] == '') {
        $error['representative_name'] = 'blank';
    }
    if ($_POST['phone_number'] == '') {
        $error['phone_number'] = 'blank';
    }
    if ($_POST['postal_code'] == '') {
        $error['postal_code'] = 'blank';
    }
    if ($_POST['prefectures_code'] == '') {
        $error['prefectures_code'] = 'blank';
    }
    if ($_POST['address'] == '') {
        $error['address'] = 'blank';
    }
    if ($_POST['mail_address'] == '') {
        $error['mail_address'] = 'blank';
    }
}

if ($error) {
    echo "error";
}

$id = intval($_GET['id']);

if (empty($error) && !empty($_POST)) {
    $statement = $db->prepare(
        'UPDATE companies SET 
        company_name=?, 
        representative_name=?, 
        phone_number=?, 
        postal_code=?, 
        prefectures_code=?, 
        address=?, 
        mail_address=?,  
        modified=NOW()
        WHERE id=?'
    );
    $statement->execute(
        [
            $_POST['company_name'],
            $_POST['representative_name'],
            $_POST['phone_number'],
            $_POST['postal_code'],
            $_POST['prefectures_code'],
            $_POST['address'],
            $_POST['mail_address'],
            $id
        ]
    );

    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>SignUp</title>
</head>
<body>
    <form action="edit.php?id=<?php echo h($_GET['id']) ?>" method="POST">
        <div class="edit-table">
            <table border="1">
                <tr>
                    <th class="left">会社名</th>
                    <th>
                        <input type="text" name="company_name" 
                        maxlength="50" value="Textbox"/>
                    </th>
                </tr>
                <tr>
                    <th class="left">代表</th>
                    <th class="right">
                        <input type="text" name="representative_name" 
                        maxlength="20" value="Textbox"/>
                    </th>
                </tr>
                <tr>
                    <th class="left">Tel</th>
                    <th class="right">
                        <input type="text" name="phone_number" 
                        maxlength="11" value="Textbox"/>
                    </th>
                </tr>
                <tr>
                    <th class="left">住所</th>
                    <th class="right">
                        <label for="postal_code" class="th-left">郵便番号</label> 
                        <input type="text" name="postal_code" 
                        maxlength="7" value="Textbox" class="th-right" />
                        <br>
                        <label for="prefectures_code" class="th-left">都道府県</label>    
                        <select name="prefectures_code" class="th-right">
                            <option value="" selected>items</option>
                            <option value="1">北海道</option>
                            <option value="2">青森県</option>
                            <option value="3">岩手県</option>
                            <option value="4">宮城県</option>
                            <option value="5">秋田県</option>
                            <option value="6">山形県</option>
                            <option value="7">福島県</option>
                            <option value="8">茨城県</option>
                            <option value="9">栃木県</option>
                            <option value="10">群馬県</option>
                            <option value="11">埼玉県</option>
                            <option value="12">千葉県</option>
                            <option value="13">東京都</option>
                            <option value="14">神奈川県</option>
                            <option value="15">新潟県</option>
                            <option value="16">富山県</option>
                            <option value="17">石川県</option>
                            <option value="18">福井県</option>
                            <option value="19">山梨県</option>
                            <option value="20">長野県</option>
                            <option value="21">岐阜県</option>
                            <option value="22">静岡県</option>
                            <option value="23">愛知県</option>
                            <option value="24">三重県</option>
                            <option value="25">滋賀県</option>
                            <option value="26">京都府</option>
                            <option value="27">大阪府</option>
                            <option value="28">兵庫県</option>
                            <option value="29">奈良県</option>
                            <option value="30">和歌山県</option>
                            <option value="31">鳥取県</option>
                            <option value="32">島根県</option>
                            <option value="33">岡山県</option>
                            <option value="34">広島県</option>
                            <option value="35">山口県</option>
                            <option value="36">徳島県</option>
                            <option value="37">香川県</option>
                            <option value="38">愛媛県</option>
                            <option value="39">高知県</option>
                            <option value="40">福岡県</option>
                            <option value="41">佐賀県</option>
                            <option value="42">長崎県</option>
                            <option value="43">熊本県</option>
                            <option value="44">大分県</option>
                            <option value="45">宮崎県</option>
                            <option value="46">鹿児島県</option>
                            <option value="47">沖縄県</option>
                        </select>
                        <br>
                        <label for="address" class="th-left">住所</label> 
                        <input type="text" name="address" 
                        maxlength="100" value="Textbox" class="th-right"/>
                    </th>
                </tr>
                <tr>
                    <th class="left">Mail</th>
                    <th class="right">
                        <input type="text" name="mail_address" 
                        maxlength="100" value="Textbox"/>
                    </th>
                </tr>
            </table>
        </div>
        <input type="submit" class="button" value="登録">
    </form>
</body>
</html>