<?php
/** 
 * 【社員テストデータ】SQL自動生成プログラム 
 * 
 * PHP Version >= 7.2.24
 * 
 * @category Exercise
 * @package  Create_Employee_Sql
 * @author   Yuta Kikkawa <kikkawa@ye-p.co.jp>
 * @license  MIT License
 * @link     http://192.168.2.62/exercises/tests/create_employee_sql.php
 * */ 

require_once'test_functions.php';

//company_idを決める
echo 'company_idを指定してください：';
$company_id = intval(trim(fgets(STDIN)));

//繰り返し回数を決める
echo '何件分のデータを作成しますか？：';
$repeat = intval(trim(fgets(STDIN)));
$sql = 'INSERT INTO employees VALUE'. "\n";

for ($i = 0; $i < $repeat; $i++) {
    $address_data = randomAddress();

    $employee_name = randomEmployeeName();
    $division_name = randomDivisionName();
    $phone_number = randomPhoneNumber();
    $postal_code = getPostalCode($address_data['postal']);
    $prf_code = findPrefectureCode($address_data['prefecture']);
    $address = getAddress($address_data['city'], $address_data['town']);

    $sql .= "(DEFAULT, $company_id, '$employee_name', '$division_name', ". 
            "'$phone_number', '$postal_code', '$prf_code', '$address', ".
            "'test@test.com', NULL, NOW(), NOW(), NULL)";

    if ($i === $repeat-1) {
        $sql .= ';';
    } else {
        $sql .= ',' ."\n";
    }
}

$file = 'test_data.sql';
file_put_contents($file, $sql);
?>