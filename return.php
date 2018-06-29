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
                        <form method="POST" action="return.php">
                            <p>條碼號：<input type="text" name="barcode"></p>
                            <!-- <p>歸還日期：<input type="date" name="rdate"></p>  -->
                            <button type="submit">還書</button>
                        </form>
                        <?php
                            require_once 'login.php';
                            $conn = new mysqli($hn, $un, $pw, $db); #負責與 mysql 連線，執行 SQL 查詢
                            $query = ("SET NAMES utf8");
                            if ($conn->connect_error) die($conn->connect_error);

                            if (isset($_POST['barcode'])) {  #如果欄位都有填
                                $barcode = get_post($conn, 'barcode');

                                $query = "SELECT Status FROM BOOK WHERE Barcode = '$barcode'";
                                $result = $conn->query($query);

                                if(!$result->fetch_assoc()['Status']){  #書有順利借出過
                                    $query = "INSERT INTO TURN (barcode) VALUES ('$barcode')"; #做歸還紀錄
                                    $result = $conn->query($query);
                                    $query = "UPDATE BOOK SET Status = 1 WHERE barcode = '$barcode'";  #將書的狀態改為可借
                                    $result = $conn->query($query);
                                    $query = "SELECT DATEDIFF(NOW(), `Bdate`) AS day FROM BORROW  WHERE barcode = 002131595 AND Turn = 0"; #取得總共借了幾天
                                    $day = $conn->query($query);
                                    $query = "SELECT Day FROM BORROW, STUDENT, LEVEL WHERE Turn = 0 AND 002131595 = BORROW.Barcode AND BORROW.SID = STUDENT.SID AND STUDENT.Lnum = LEVEL.Lnum"; #此讀者真正可借閱天數
                                    $ruleday = $conn->query($query);
                                    
                                    if( (int)$day->fetch_assoc()['day'] > (int)$ruleday->fetch_assoc()['Day']) {
                                        $day->data_seek(0);
                                        $ruleday->data_seek(0);
                                        $substraction = (int)$day->fetch_assoc()['day'] - (int)$ruleday->fetch_assoc()['Day'];
                                        $money = $substraction * 5;
                                        echo  "此讀者逾期 ". $substraction  ." 天，需要繳交罰款 ". $money ." 元。<br>" ;
                                    }
                                    $query = "UPDATE BORROW SET Turn = 1 WHERE barcode = '$barcode' AND Turn = 0"; #將 turn 標示為已還
                                    $result = $conn->query($query);

                                    echo "還書成功！";
                                }
                                else {  #書未順利借出，或是本來就沒有借
                                    echo "還書失敗，此書未被借出！";
                                }
                                if (!$result) echo "INSERT failed : $query <br>" . $conn->error . "<br><br>";
                            }


                            $result->close();
                            $conn->close();
                                
                            function get_post($conn, $var) {
                                return $conn->real_escape_string($_POST[$var]);
                            }
                        ?>        
                        
                    </div>
                    <div class="col-lg-4"></div>
                </div>
            </div>
        </div>
    </body>
</html>

