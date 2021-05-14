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


//prefecture_code
function randomPrefectureCode() {
    return rand(1, 47);
}


//繰り返し回数を決める
echo '何件分のデータを作成しますか？：';
$repeat = intval(trim(fgets(STDIN)));
$sql = null;
$sqls = null;

for ($i = 0; $i < $repeat; $i++) {
    $sql =  'INSERT INTO companies SET '.
            'company_name=' ."'" .randomCompanyName() ."'".
            ', representative_name=' ."'" .randomRepresentativeName() ."'".
            ', phone_number=' ."'" .randomPhoneNumber() ."'".
            ", postal_code='000-0000'" .
            ', prefectures_code=' ."'" .randomPrefectureCode() ."'".
            ", address='-'".
            ", mail_address='test@test.com'".
            ', created=NOW()'.
            ', modified=NOW()' .';'. "\n";
    
    $sqls .= $sql;
}

$file = 'test_data.sql';
file_put_contents($file, $sqls);
?>