<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Jua" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Library Management System</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                var oldtext=['關於圖書館', '借還書服務', '資料登錄', '資料修改', '搜尋']
                var newtext = ['About', 'Book', 'Log', 'Modify', 'Search']

                //  讓 #menu 的寬度自動根據 main 的數量而變
                //$("#menu").css("width", $(".main").length * 100)

                //  一進入畫面時收合選單
                $(".sub").slideUp(0);
            
                for (i = 0; i < $(".main").length; i++) {
                    //  點選按扭時收合或展開選單
                    $(".main:eq(" + i + ")").on("mouseover", {  
                        id: i
                    }, function(e) {
                        n = e.data.id
                        $(".sub:eq(" + n + ")").slideToggle()
                        $(".sub:not(:eq(" + n + "))").slideUp()
                        $(".main:eq(" + n + ")").text(newtext[n])
                    })
                    $(".main:eq(" + i + ")").on("mouseleave", {
                        id: i
                    }, function(e) {
                        n = e.data.id
                        $(".main:eq(" + n + ")").text(oldtext[n])
                        $(".sub").stop();
                    })
                }
            })
        </script>
    </head>
    <body>
        <div id="top">
            <p><a href="index.html"><i class="fas fa-book"></i> Library Management System</a></p>
        </div>

        <div id="menu">
            <div class="item">
                <div class="main">關於圖書館</div>
                <div class="sub">
                    <ul>
                        <li><a href="floor.php">樓層介紹</a></li>
                        <li><a href="team.php">組別介紹</a></li>
                    </ul>
                </div>
            </div>

            <div class="item">
                <div class="main">借還書服務</div>
                <div class="sub">
                    <ul>
                        <li><a href="borrow.php">借書</a></li>
                        <li><a href="return.php">還書</a></li>
                    </ul>
                </div>
            </div>

            <div class="item">
                <div class="main">資料登錄</div>
                <div class="sub">
                    <ul>
                        <li><a href="log_student.php">學生</a></li>
                        <li><a href="log_employee.php">員工</a></li>
                        <li><a href="log_book.php">書籍</a></li>
                    </ul>
                </div>
            </div>

            <div class="item">
                <div class="main">資料修改</div>
                <div class="sub">
                    <ul>
                        <li><a href="modify_student.php">學生</a></li>
                        <li><a href="modify_employee.php">員工</a></li>
                        <li><a href="modify_book.php">書籍</a></li>
                    </ul>
                </div>
            </div>

            <div class="item">
                <div class="main">搜尋</div>
                <div class="sub">
                    <ul>
                        <li><a href="search_student.php">學生</a></li>
                        <li><a href="search_employee.php">員工</a></li>
                        <li><a href="search_book.php">書籍</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div id="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4"></div>
                    <div class="col-lg-4 back">
                        <p>請選擇要查詢的類型：</p>
                        <form method="POST" action="search_book.php">
                            <p><input type=radio value="1" name="check"> 條碼號：<input type="text" name="barcode"></p>
                            <p><input type=radio value="2" name="check"> 索書號：<input type="text" name="callnum"></p>
                            <p><input type=radio value="3" name="check" checked> 書名：<input type="text" name="bname"></p>
                            <p><input type=radio value="4" name="check"> 樓層：<input type="text" name="floor"></p>
                            <button type="submit">查詢</button>
                        </form>
                        <?php
                            require_once 'login.php';
                            $conn = new mysqli($hn, $un, $pw, $db); #負責與 mysql 連線，執行 SQL 查詢
                            if ($conn->connect_error) die($conn->connect_error);
                            $query = ("SET NAMES utf8");

                            if (isset($_POST['barcode']) || isset($_POST['callnum']) || isset($_POST['bname']) || isset($_POST['floor'])) { #如果4個欄位都有填
                                $barcode = get_post($conn, 'barcode');
                                $callnum = get_post($conn, 'callnum');
                                $bname = get_post($conn, 'bname');
                                $floor = get_post($conn, 'floor');
                            }
                            // else {
                            //     echo "請輸入查詢內容！";
                            // }
                            
                            if(isset($_POST['check'])) {
                                if(!strcmp($_POST['check'], "1")) {
                                    if ($_POST['barcode']) {
                                        $query  = "SELECT * FROM BOOK WHERE Barcode = '$barcode'";
                                        $result = $conn->query($query);
                                    }
                                    else{
                                        echo "請輸入條碼號！";
                                    }
                                }
                                else if(!strcmp($_POST['check'], "2")) {
                                    if ($_POST['callnum']) {
                                        $query  = "SELECT * FROM BOOK WHERE Callnum like '%$callnum%'";
                                        $result = $conn->query($query);
                                    }
                                    else{
                                        echo "請輸入索書號！";
                                    }
                                }
                                else if(!strcmp($_POST['check'], "3")) {
                                    if ($_POST['bname']) {
                                        $query  = "SELECT * FROM BOOK WHERE Bname like '%$bname%'";
                                        $result = $conn->query($query);
                                    }
                                    else{
                                        echo "請輸入書名！";
                                    }
                                }
                                else {
                                    if ($_POST['floor']) {
                                        $query  = "SELECT * FROM BOOK WHERE Floor = '$floor'";
                                        $result = $conn->query($query);
                                    }
                                    else{
                                        echo "請輸入樓層！";
                                    }
                                }
                            }
                        ?>
                    </div>
                    <div class="col-lg-4"></div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-7 back">
                        <?php
                            if (!$result) die($conn->error);
                            $rows = $result->num_rows;   #查詢結果的資料筆數
                            echo "<br><table border=1>";
                            echo "<tr class='head'>";
                            echo "<td>書名" ."</td>";
                            echo "<td>條碼" . "</td>"; 
                            echo "<td>索書號" . "</td>";
                            echo "<td>所在樓層" . "</td>";
                            echo "<td>借閱狀態" . "</td>";
                            echo "</tr>";    
                            for ($j = 0 ; $j < $rows ; ++$j)
                            {
                                echo "<tr>";
                                $result->data_seek($j);
                                echo "<td>" . $result->fetch_assoc()['Bname'] ."</td>";
                                $result->data_seek($j);
                                echo "<td>" . $result->fetch_assoc()['Barcode'] ."</td>"; #關聯陣列，只能用 $row[$colname] 存取
                                $result->data_seek($j);
                                echo "<td>" . $result->fetch_assoc()['Callnum'] ."</td>";
                                $result->data_seek($j);
                                echo "<td>" . $result->fetch_assoc()['Floor'] ."</td>";
                                $result->data_seek($j);
                                echo "<td>" . $result->fetch_assoc()['Status'] ."</td>";
                                echo "</tr>";
                            }
                            echo "</table><br><br>";
                            $result->close();
                            $conn->close();
                            
                            function get_post($conn, $var) {
                                return $conn->real_escape_string($_POST[$var]);
                            }
                        ?>
                    </div>
                    <div class="col-lg-2"></div>
                </div>
            </div>
        </div>
    </body>
</html>

