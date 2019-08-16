
<?php
    $dbc = mysql_connect("localhost:3310","root","hunan2010");
	mysql_select_db("patent",$dbc);
	mysql_query('SET NAMES UTF8');
    date_default_timezone_set("Asia/Shanghai");

    $stock_code = "";
    $ap_company = "";
    $patent_title = "";
    $patent_abstract = "";
    $query = "select  stock_code ,ap_company , patent_title, patent_abstract  from patent order by stock_code  limit 1 ";
    $result = mysql_query($query,$dbc);
    if($row = mysql_fetch_array($result)){
        $stock_code = $row['stock_code'];
        $ap_company = $row['ap_company'];
        $patent_title = $row['patent_title'];
        $patent_abstract = $row['patent_abstract'];
    }

?>

<html>
    <head>
        <title>专利操作</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
            table {
                font-size:16px;
                width : 900px;  margin: auto;padding:30px;
                border: 2px solid #000;
                text-align:left;
            }
            button {
                    width:150px; height: 40px; text-aign:center; line-height:40px; border:none; border-radius: 10px;color:#fff;cursor:pointer;margin:20px;
            }
            #green { background:green;}
            #red {background:red;}
        </style>
    </head>
    <body>
        <table>
            <thead></thead>
            <tbody>
                <tr>
                    <td>股票代码：</td><td><?php  echo $stock_code;  ?></td>
                </tr>
                <tr>
                    <td>申请公司：</td><td><?php  echo $ap_company;  ?></td>
                </tr>
                <tr>
                    <td>专利标题：</td><td><?php  echo $patent_title;  ?></td>
                </tr>
                <tr>
                    <td>摘要：</td><td><?php  echo $patent_abstract;  ?></td>
                </tr>
                <tr>
                    <td></td><td></td>
                </tr>
                <tr>
                    <td><button id = "green">绿色专利</button></td><td><button id = "red">非绿色专利</button></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>