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
                        <form method="POST" action="log_student.php">
                            <p>姓名：<input type="text" name="sname"></p>
                            <p>性別：<input type="text" name="sex"></p>
                            <p>讀者編號：<input type="text" name="sid"></p>
                            <p>科系：<input type="text" name="sdepartment"></p>
                            <p>年級：<input type="text" name="grade"></p>
                            <button type="submit">新增</button>
                        </form>
                        <?php
                            require_once 'login.php';
                            $conn = new mysqli($hn, $un, $pw, $db); #負責與 mysql 連線，執行 SQL 查詢
                            $query = ("SET NAMES utf8");
                            if ($conn->connect_error) die($conn->connect_error);

                            if (isset($_POST['sname']) && isset($_POST['sex']) && isset($_POST['sid']) && isset($_POST['sdepartment']) && isset($_POST['grade'])) {  #如果欄位都有填
                                $sname = get_post($conn, 'sname');
                                $sex = get_post($conn, 'sex');
                                $sid = get_post($conn, 'sid');
                                $sdepartment = get_post($conn, 'sdepartment');
                                $grade = get_post($conn, 'grade');
                                $query = "INSERT INTO STUDENT (sname, sex, sid, sdepartment, grade) VALUES ('$sname', '$sex', '$sid', '$sdepartment', '$grade')";
                                $result = $conn->query($query);
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

