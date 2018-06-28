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
                var oldtext=['關於圖書館','借還書服務','資料登錄','搜尋']
                var newtext = ['About', 'Book', 'Log', 'Search']
            
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
                    <div class="col-lg-4">
                        <p>請選擇要查詢的類型：</p>
                        <form method="POST" action="search_employee.php">
                            <p><input type=radio value="1" name="check" checked> 員工編號：<input type="text" name="EID"></p>
                            <p><input type=radio value="2" name="check"> 姓名：<input type="text" name="ename"></p>
                            <p><input type=radio value="3" name="check"> 分機：<input type="text" name="extension"></p>
                            <p><input type=radio value="4" name="check"> 組別：<input type="text" name="dnum"></p>
                            <p><input type=radio value="5" name="check"> 性別：<input type="text" name="sex"></p>
                            <button type="submit">查詢</button>
                        </form>
                        <?php 
                            require_once 'login.php';
                            $conn = new mysqli($hn, $un, $pw, $db); #負責與 mysql 連線，執行 SQL 查詢
                            if ($conn->connect_error) die($conn->connect_error);
                            $query = ("SET NAMES utf8");

                            if (isset($_POST['EID']) || isset($_POST['ename']) || isset($_POST['extension']) || isset($_POST['dnum']) || isset($_POST['sex'])) { #如果4個欄位都有填
                                $EID = get_post($conn, 'EID');
                                $ename = get_post($conn, 'ename');
                                $extension = get_post($conn, 'extension');
                                $dnum = get_post($conn, 'dnum');
                                $sex = get_post($conn, 'sex');
                            }
                            // else {
                            //     echo "請輸入查詢內容！";
                            // }
                            
                            if(isset($_POST['check'])) {
                                if(!strcmp($_POST['check'], "1")) {
                                    if ($_POST['EID']) {
                                        $query  = "SELECT * FROM EMPLOYEE WHERE EID = '$EID'";
                                        $result = $conn->query($query);
                                    }
                                    else{
                                        echo "請輸入員工編號！";
                                    }
                                }
                                else if(!strcmp($_POST['check'], "2")) {
                                    if ($_POST['ename']) {
                                        $query  = "SELECT * FROM EMPLOYEE WHERE Ename like '%$ename%'";
                                        $result = $conn->query($query);
                                    }
                                    else{
                                        echo "請輸入姓名！";
                                    }
                                }
                                else if(!strcmp($_POST['check'], "3")) {
                                    if ($_POST['extension']) {
                                        $query  = "SELECT * FROM EMPLOYEE WHERE Extension = '$extension'";
                                        $result = $conn->query($query);
                                    }
                                    else{
                                        echo "請輸入分機！";
                                    }
                                }
                                else if(!strcmp($_POST['check'], "4")) {
                                    if ($_POST['dnum']) {
                                        $query  = "SELECT * FROM EMPLOYEE WHERE Dnum = '$dnum'";
                                        $result = $conn->query($query);
                                    }
                                    else{
                                        echo "請輸入組別！";
                                    }
                                }
                                else {
                                    if ($_POST['sex']) {
                                        $query  = "SELECT * FROM EMPLOYEE WHERE Sex = '$sex'";
                                        $result = $conn->query($query);
                                    }
                                    else{
                                        echo "請輸入性別！";
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
                    <div class="col-lg-6 back">
                        <?php
                            if (!$result) die($conn->error);
                            $rows = $result->num_rows;   #查詢結果的資料筆數
                            echo "<br><table border=1>";
                            echo "<tr class='head'>";
                            echo "<td>員工編號" ."</td>";
                            echo "<td>姓名" . "</td>"; 
                            echo "<td>分機" . "</td>";
                            echo "<td>組別" . "</td>";
                            echo "<td>性別" . "</td>";
                            echo "</tr>";    
                            for ($j = 0 ; $j < $rows ; ++$j)
                            {
                                echo "<tr>";
                                $result->data_seek($j);
                                echo "<td>" . $result->fetch_assoc()['EID'] ."</td>";
                                $result->data_seek($j);
                                echo "<td>" . $result->fetch_assoc()['Ename'] ."</td>"; #關聯陣列，只能用 $row[$colname] 存取
                                $result->data_seek($j);
                                echo "<td>" . $result->fetch_assoc()['Extension'] ."</td>";
                                $result->data_seek($j);
                                echo "<td>" . $result->fetch_assoc()['Dnum'] ."</td>";
                                $result->data_seek($j);
                                echo "<td>" . $result->fetch_assoc()['Sex'] ."</td>";
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
                    <div class="col-lg-3"></div>
                </div>
            </div>
        </div>
    </body>
</html>

