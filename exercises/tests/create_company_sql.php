<?php
//company_name
function randomCompanyName() {
    $company_name = ['テスト', 'test', 'ねこ', 'いぬ', 'PC', 'スマホ', 'Web'];
    
    return "株式会社" .$company_name[array_rand($company_name)];
}

//representative_name
function randomRepresentativeName() {
    $first_name = ['鈴木', '田中', '岡本', '山田', '佐藤', '山崎'];
    $last_name = ['一郎', '圭太', '花子', '優太', '理恵', '加奈子'];
    
    return $first_name[array_rand($first_name)] .' ' .$last_name[array_rand($last_name)];
}

//phone_number
function randomPhoneNumber() {
    $first = ['090', '080', '070'];
    $second = rand(1111, 9999);
    $third = rand(1111, 9999);

    return $first[array_rand($first)] .'-' .$second .'-' .$third; 
}


//Address
function randomKeyword() {
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

function randomAddress() {
    
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

function findPrefectureCode($value) {
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

    return $i + 1;
}

function getPostalCode($postal_code) {
    $first = substr($postal_code, 0, -4);
    $second = substr($postal_code, -4);
    $postal_code = $first .'-' .$second;
    
    return $postal_code;
} 

function getAddress($city, $town) {
    if ($town == '（その他）') {
        $town = null;
    }
    return $city .$town;
}

//繰り返し回数を決める
echo '何件分のデータを作成しますか？：';
$repeat = intval(trim(fgets(STDIN)));
$sql = null;
$sqls = null;

for ($i = 0; $i < $repeat; $i++) {
    $array = randomAddress();

    $sql =  'INSERT INTO companies SET '.
            "company_name='" .randomCompanyName() ."'".
            ", representative_name='" .randomRepresentativeName() ."'".
            ", phone_number='" .randomPhoneNumber() ."'".
            ", postal_code='" .getPostalCode($array['postal']) ."'" .
            ", prefectures_code='" .findPrefectureCode($array['prefecture']) ."'".
            ", address='" .getAddress($array['city'], $array['town']) . "'".
            ", mail_address='test@test.com'".
            ', created=NOW()'.
            ', modified=NOW()' .';'. "\n";
    
    $sqls .= $sql;
}

$file = 'test_data.sql';
file_put_contents($file, $sqls);
?>