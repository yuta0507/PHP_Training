<?php
/** 
 * テスト用のファンクションを管理 
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Test_Functions
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/tests/test_functions.php
 * */ 

/**
 * Function randomCompanyName.
 * 
 * @return string
 * */
function randomCompanyName() 
{
    $company_names = ['テスト', 'test', 'ねこ', 'いぬ', 'PC', 'スマホ', 'Web'];
    $company_name = "株式会社" .$company_names[array_rand($company_names)];

    return $company_name;
}

/**
 * Function randomRepresentativeName.
 * 
 * @return string
 * */
function randomRepresentativeName() 
{
    $first_name = ['鈴木', '田中', '岡本', '山田', '佐藤', '山崎'];
    $last_name = ['一郎', '圭太', '花子', '優太', '理恵', '加奈子'];
    
    $rep_name = $first_name[array_rand($first_name)] .' ' .
                $last_name[array_rand($last_name)];
    return $rep_name;
}

/**
 * Function randomEmployeeName.
 * 
 * @return string
 * */
function randomEmployeeName() 
{
    $first_name = ['鈴木', '田中', '岡本', '山田', '佐藤', '山崎'];
    $last_name = ['一郎', '圭太', '花子', '優太', '理恵', '加奈子'];
    
    $employee_name = $first_name[array_rand($first_name)] .' ' .
                     $last_name[array_rand($last_name)];
    return $employee_name;
}

/**
 * Function randomDivisionName.
 * 
 * @return string
 * */
function randomDivisionName() 
{
    $division_names = ['人事部', 'システム部', '営業部', 'デザイン部', 'マーケティング部', '総務部'];
    $division_name = $division_names[array_rand($division_names)];

    return $division_name;
}

/**
 * Function randomPhoneNumber.
 * 
 * @return string
 * */
function randomPhoneNumber() 
{
    $first = ['090', '080', '070'];
    $second = rand(1111, 9999);
    $third = rand(1111, 9999);

    $phone_number = $first[array_rand($first)] .'-' .$second .'-' .$third;
    return $phone_number; 
}


/**
 * Function randomKeyword.
 * 
 * @return string
 * */
function randomKeyword() 
{
    $keywords = [
        "北海道", "青森県", "岩手県", "宮城県", "秋田県", 
        "山形県", "福島県", "茨城県", "栃木県", "群馬県", 
        "埼玉県", "千葉県", "東京都", "神奈川県", "新潟県", 
        "富山県", "石川県", "福井県", "山梨県", "長野県", 
        "岐阜県", "静岡県", "愛知県", "三重県", "滋賀県", 
        "京都府", "大阪府", "兵庫県", "奈良県", "和歌山県",
        "鳥取県", "島根県", "岡山県", "広島県", "山口県", 
        "徳島県", "香川県", "愛媛県", "高知県", "福岡県", 
        "佐賀県", "長崎県", "熊本県", "大分県", "宮崎県", 
        "鹿児島県", "沖縄県", "府中", "中野", "住吉", "文京",
        "本郷", "工藤", "山崎", "松林", "吉川"
    ];

    $keyword = $keywords[array_rand($keywords)];
    
    return $keyword;
}

/**
 * Function randomAddress.
 * 
 * @return string
 * */
function randomAddress() 
{
    
    $url =  'http://geoapi.heartrails.com/api/'.
            'json?method=suggest&matching=like&keyword='.
            randomKeyword();
    
    $json = file_get_contents($url);
    $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    $data = json_decode($json, true);
    
    $arr = $data['response']['location'];
    $array = $arr[array_rand($arr)];
    
    return $array;
}

/**
 * Function findPrefectureCode.
 * 
 * @param string $value 文字列
 * 
 * @return string
 * */
function findPrefectureCode($value) 
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

    $i = 0;
    foreach ($prefectures as $prefecture) {
        if ($prefecture === $value) {
            break;
        }
        $i++;
    }

    $prf_code = $i + 1;

    return $prf_code;
}

/**
 * Function getPostalCode.
 * 
 * @param string $postal_code 文字列
 * 
 * @return string
 * */
function getPostalCode($postal_code) 
{
    $first = substr($postal_code, 0, -4);
    $second = substr($postal_code, -4);
    $postal_code = $first .'-' .$second;
    
    return $postal_code;
} 

/**
 * Function getAddress.
 * 
 * @param string $city 文字列
 * @param string $town 文字列
 * 
 * @return string
 * */
function getAddress($city, $town) 
{
    if ($town == '（その他）') {
        $town = null;
    }
    $address = $city .$town;

    return $address;
}
?>