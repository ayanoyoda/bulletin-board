<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
    　　<?php
        error_reporting(E_ALL&~E_NOTICE);
        // DB接続設定
	    $dsn = 'データベース名';
	    $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        // テーブル作成 テーブル名はmission
        $sql = "CREATE TABLE IF NOT EXISTS mission"
	    ." ("
	    . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "pass TEXT,"
        . "date TEXT,"
        . "comment TEXT"
	    . ");";
        $stmt = $pdo->query($sql);
    
        // 関数の定義
        $edinumber =$_POST["edinumber"];
        $textbox =$_POST["textbox"];
        $pass3=$_POST["pass3"];
        $pass2=$_POST["pass2"];

        // 編集表示
        if(isset($_POST["edit"]))
        {
            if(empty($_POST["edinumber"]) || empty($_POST["pass3"]))
            {}
            else
            {
                $sql = 'SELECT * FROM mission';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row)
                {
                    if($row['pass'] == $pass3)
                    {
                        if($row['id'] == $edinumber)
                        {   
                            $textbox = $row['id'];
                            $ediname = $row['name'];
                            $edicomment = $row['comment'];
                        }
                    }    
                }
            

            }
            
        }
        ?>

        <form action = "" method = "post">
        【投稿フォーム】
        <br>
        名前：
        <input type="text" name="name" value="<?php echo $ediname?>">
        <br>
        コメント：
        <input type="text" name="comment" value="<?php echo $edicomment?>">
        <br>
        パスワード：
        <input type="password" name="pass">
        <br>
        <input type="submit" name="submit">
        <br>
        【削除フォーム】
        <br>
        投稿番号：
        <input type="text" name="delnumber">
        <br>
        パスワード：
        <input type="password" name="pass2">
        <br>
        <input type="submit" value="削除" name="delete">
        <br>
        【編集フォーム】
        <br>
        投稿番号：
        <input type="text" name="edinumber">
        <br>
        パスワード：
        <input type="password" name="pass3">
        <br>
        <input type="submit" value="編集" name="edit">
        <input type="text" name="textbox"value="<?php echo $edinumber?>">
        </form>

    　　<?php
    　　//エラー表示
            if(isset($_POST["submit"]))
            {
                if(empty($_POST["name"]))
                {
                    echo "ERROR:名前が入力されていません"."<br>";
                }
                if(empty($_POST["comment"]))
                {
                    echo "ERROR:コメントが入力されていません"."<br>";
                }
                if(empty($_POST["pass"]))
                {
                    echo "ERROR:パスワードが入力されていません"."<br>";
                }
            }
            if(isset($_POST["delete"]))
            {
                $sql = 'SELECT * FROM mission';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row)
                {
                    if(empty($_POST["delnumber"]))
                    {
                        echo "ERROR:削除番号が入力されていません"."<br>";
                    }
                    if(empty($_POST["pass2"]))
                    {
                        echo "ERROR:パスワードが入力されていません"."<br>";
                    }
                    if($row['pass'] != $pass2)
                    {
                        echo "パスワードが間違っています"."<br>";
                    }
                }
            }
            if(isset($_POST["edit"]))
            {
                $sql = 'SELECT * FROM mission';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row)
                {
                    if(empty($_POST["edinumber"]))
                    {
                            echo "ERROR:編集番号が入力されていません"."<br>";
                    }
                    if(empty($_POST["pass3"]))
                    {
                        echo "ERROR:パスワードが入力されていません"."<br>";
                    }
                    if($row['pass'] != $pass3)
                    {
                        echo"パスワードが間違っています"."<br>";
                    }
                }
            }
        ?>
    --------------------
    　　<br>
    　　<p>【投稿一覧】</p>
    　　<?php
        if(isset($_POST["submit"]))
        {
            if(empty($_POST["name"]) || empty($_POST["comment"]) || empty($_POST["pass"]))
            {}
            else
            {
                if(empty($_POST["textbox"]))
                { //通常投稿
                    $sql = $pdo -> prepare("INSERT INTO mission (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

                    $name=$_POST["name"];
                    $comment=$_POST["comment"];
                    $date = date("Y/m/d H:i:s");
                    $pass = $_POST["pass"];
                    $sql -> execute();
                }
                else
                { //編集後の投稿
                    $sql = 'SELECT * FROM mission';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row)
                    {
                        if($row['id'] == $textbox)
                        {
                            $id = $textbox; //変更する投稿番号
                   
                            $sql = 'UPDATE mission SET name=:name,comment=:comment,date=:date WHERE id=:id';
	                        $stmt = $pdo->prepare($sql);
	                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    
                            $name=$_POST["name"];
                            $comment=$_POST["comment"];
                            $date = date("Y/m/d H:m:s");
                            $pass = $_POST["pass"];
                            $stmt -> execute();
                        }
                    }
                
                }
            }
        }

        //投稿削除    
        if(isset($_POST["delete"]))
        {
            if(empty($_POST["delnumber"]) || empty($_POST["pass2"]))
            {}
            else
            {   $id = $_POST["delnumber"];
                $sql = 'SELECT * FROM mission';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row)
                {
                    if($row['pass'] == $pass2)
                    {
                        if($row['id'] == $id)
                        {    
	                        $sql = 'delete from mission where id=:id';
	                        $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }
            }
        }

        $sql = 'SELECT * FROM mission';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row)
        {
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            echo "<hr>";
        }
        ?>
    </body>
</html>