<?php

include('db_conn.php'); 
$mydb = new db(); // สร้าง object ใหม่ , class db()

$res = [];

$conn = $mydb->connect();
//รับค่า Query จากหน้า test2.php 

if(isset($_POST["id"])){
    $id = $_POST["id"];

    $result2 = $conn->prepare("SELECT * FROM invoice_item   WHERE  invoice_id = $id LIMIT 0,10 ");

}

$result2->execute();
$row = $result2->fetch(PDO::FETCH_ASSOC);
$inv = $row['invoice_id']?? '';


//ถ้า id มีค่าเท่ากับ inv จะแสดงข้อมูล และถ้าไม่ตรง จะส่งข้อความกลัวว่า ไม่มีข้อมูล
if($id = $inv) {  
    $res = $conn->prepare("SELECT * FROM invoice_item   WHERE  invoice_id = $id  ");
    $res->execute();
    $data['res'] =  $res->fetchAll(PDO::FETCH_ASSOC);
    //แสดงข้อมูลจนกว่าข้อมูลจะหมด
    exit( json_encode( $data ) );



}else {


    $data['res'] = "";
    exit( json_encode( $data ) );
}



?>