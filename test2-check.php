<?php

$input = json_decode($_POST["query"], true);
$query = $input["query"];
$page = $input["page"];

$res = [];

$q = explode( " ", $query);
//เชื่อมฐานข้อมูลจากหน้า db_conn.php
include('db_conn.php'); 
$mydb = new db(); 
$conn = $mydb->connect();

// ค้าหา
$dText = "";
foreach($q as $key => $value)
{
    if( $key == 0 )
        $dText .= "WHERE";

    if($key > 0 )
        $dText .= " AND ";
//เอาcolumnมาต่อกัน
        $dText .= 
        "
            CONCAT(name, ' ', organization, ' ', title, ' ') LIKE '%". $value ."%'
        ";
}

$COUNT= $conn->prepare("

SELECT COUNT(*)as ttt FROM invoice {$dText} 

");
$COUNT->execute();
$rec = $COUNT->fetch(PDO::FETCH_ASSOC);
$ttt = $rec['ttt'];

$rpp = 10; // limit
$startPage = ( $page - 1 ) * $rpp; 

$ttp = ceil($ttt/$rpp);

//รับค่า Query จากหน้า test2.php 
if(!empty($query))
{
// ค้นหาข้อมูลใน database ที่ตรงกับ input 
	
$results = $conn->prepare("SELECT * FROM invoice {$dText}

LIMIT {$startPage},{$rpp};
");

}
else
{
 //ถ้าไม่ได้ input  จะแสดงข้อมูล ใน datadase
 $results = $conn->prepare("SELECT * FROM invoice  LIMIT {$startPage},{$rpp}");

}
//แสดงข้อมูล column database
$results->execute();

$res["result"] = $results->fetchAll(PDO::FETCH_ASSOC);
$res["page"] = $ttp;
$res["currentPage"] = $page;

exit( json_encode( $res ) );

?>