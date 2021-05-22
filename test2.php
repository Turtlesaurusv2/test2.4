<!DOCTYPE html>
<html>

<head>
    <style>
    div {

        margin: auto;
    }

    table {

        width: 100%;
        height: 120%;
    }

    button {
        width: 100%;
        height: 100%;
    }

    .ss {
        text-align: left;
        padding: 8px;
        width: 300px;
    }

    th,
    td {
        text-align: left;
        padding: 8px;
        height: 50px;
        border: 1px solid;
    }


    tr:nth-child(even) {
        background-color: white;
    }
    </style>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootpag/1.0.7/jquery.bootpag.min.js">
    </script>


    <script type="text/javascript">
    $(document).ready(function() {

        load_data();

        //สร้าง function รับค่าจาก search_text
        $('#search_text').keyup(function() {
            //ประกาศตัวแปร
            var search = $(this).val();

            if (search != '') {
                load_data(search);
            } else {
                load_data();
            }

        });

    });

    function createPagination(current, page) {
        // แก้ไข เรียกซ้ำซ้อน
        $("#page-selection").unbind();

        $('#page-selection').bootpag({
            total: page, //หน้า  page ทั้งหมด
            page: current, //แสดงหน้าปัจจุบัน
            maxVisible: 6, //จำนวน max หน้า page
            leaps: false,
            next: 'next',
            prev: 'prev'

        }).on('page', function(event, num) {

            var search_text = $("#search_text").val();

            console.log(num);

            load_data(search_text, num);

        });

    }

    function load_data(query = "", page = 1) {
        //ประกาศตัวแปร object 
        var data = {};
        data["query"] = query;
        data["page"] = page;
        //ประกาศตัวแปรjson ช
        var query = JSON.stringify(data);

        $.ajax({
            url: "test2-check.php",
            method: "POST",
            async: false,
            data: {
                "query": query
            },
            dataType: "json",
            success: function(res) {

                var result = res.result;

                var html = "";
                result.forEach(ele => {

                    html += "<tr>" +
                        "<td>" + ele.invoice_id + "</td>" +
                        "<td>" + ele.invoice_number + "</td>" +
                        "<td>" + ele.company_format + "</td>" +
                        "<td>" + ele.name + "</td>" +
                        "<td>" + ele.organization + "</td>" +
                        "<td>" + ele.address + "</td>" +
                        "<td>" + ele.create_dt + "</td>" +
                        "<td ><button onclick='send(" + ele.invoice_id +
                        ");' type='button'  name='butsave' id='show' >" +
                        "<i class='fas fa-plus'></i></button>" +
                        "</td>" +
                        "</tr>" +
                        "<tr class='ss' colspan='5' id='invoiceBody" + ele.invoice_id +
                        "' style='display:none' bgcolor='#FFFF99'>" +
                        "<th colspan='5' id ='invoiceBody" + ele.invoice_id +
                        "' bgcolor='#FFFF99'>" +
                        "</th>" +
                        "</tr>";
                });

                $("#result").html(html);
                // load button
                createPagination(res.currentPage, res.page);

            }

        });

    }
    //รับค่ามาจาก getinv เมื่อกดปุ่มมันจะแสดงข้อมูลตัวลูก
    function send(id) {

        var x = document.getElementById("invoiceBody" + id);

        // ajax
        $.ajax({
            url: "getinv.php",
            type: "POST",
            data: 'id=' + id,
            dataType: 'json',
            success: function(data) {

                //ประกาศตัวแปรเพื่อดเอาข้อมลที่ส่งมา มาใช้งาน
                var rsp = data.res;

                if (x.style.display == "none") {

                    x.style.display = "block";

                    var html = "";

                    if (rsp == "") {

                        html = "ไม่มีข้อมูล";

                    } else {

                        rsp.forEach(ele => {

                            html +=
                                "<br> item: " + ele.item_id + " <br>" +
                                "description: " + ele.description + " <br>" +
                                "price: " + ele.price + "<br>" +
                                "quantity: " + ele.quantity + "<br>" +
                                "vat: " + ele.vat + "<br>" +
                                "before_vat: " + ele.before_vat + "<br>" +
                                "total: " + ele.total +
                                "<br>-----------------------------------<br>";
                        });
                    }

                    $("#invoiceBody" + id).html(html)

                } else {
                    x.style.display = "none";
                }

            }
        });

    }
    </script>

</head>

<body>

</br>
    <div class="w3-container">
        <div><input class=" ser w3-input w3-border" type="text" name="search_text" id="search_text"
                placeholder="Search ">
    </div>
    <br>


    </br>
    <div class="w3-container">

        <br>

        <table style="padding-top:10px">
            <thead>
                <tr>
                    <th>invoice_id</th>
                    <th>invoice_number</th>
                    <th>company_format</th>
                    <th>name</th>
                    <th>organization</th>
                    <th>address</th>
                    <th>create_dt</th>
                    <th>more_info</th>
                </tr>
                </thesd>
            <tbody id="result"></tbody>
        </table>

        <tbody colspan="5" id="invoiceBody"> </tbody>




        <div id="page-selection"></div>

    </div>

</body>

</html>