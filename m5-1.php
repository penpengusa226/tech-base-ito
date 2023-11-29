<!DOCTYPE html>　
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_ 5-1</title>
</head>

<body>

<?php
//DB接続
    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザ名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS boardm5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name CHAR(32),"
    . "comment TEXT,"
    . "time DATETIME,"
    . "password CHAR(10)"
    .");";
    $stmt = $pdo->query($sql);

$editname = "";
$editcomment="";
$editnumber="";
$editpassword="";
if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){ //編集番号選択
    //パスワードの一致確認
    $sql = 'SELECT * FROM boardm5 WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    //値のセット: bindParam(':カラム',値,値の型)
    $stmt->bindParam(':id',$_POST["edit"],PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetch();
    if($results['password']==$_POST["password_edit"]){//パスワード一致
              $editnumber = $_POST["edit"];
              $editname = $results['name'];
              $editcomment = $results['comment'];
              $editpassword = $results['password'];//フォームに編集元を表示
          }
}
?>

<form action="" method="post"><br> 
        <!-- 投稿フォーム -->
        <input type="text" name="name" placeholder="name" value=<?php echo $editname;?>><br>
        <input type="text" name="comment" placeholder="comment" value=<?php echo $editcomment;?>><br>
        <input type="text" name="password" placeholder="password (10 word)" value=<?php echo $editpassword;?>>
        <input type="hidden" name="edit_number" placeholder="editnumber" value=<?php echo $editnumber;?>>
        <input type="submit" name="submit" value="send"><br>
        <br>
        <!-- 削除フォーム -->
        <input type="number" name="delete" placeholder="delete number"><br>
        <input type="text" name="password_delete" placeholder="password">
        <input type="submit" name="delete2" value="delete"><br>
        <br>
        <!-- 編集フォーム-->
        <input type="number" name="edit" placeholder="edit number"><br>  
        <input type="text" name="password_edit" placeholder="password">
        <input type="submit" name="edit_submit" value="edit">
        
    </form>
    <br>
    <br>
    
<?php
    
    //データの入力
    $date = date("Y-m-d H:i:s");
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"] && empty($_POST["edit_number"]))){
    $sql = "INSERT INTO boardm5 (name, comment, time, password) VALUES (:name, :comment, :time, :password)";
    $stmt = $pdo->prepare($sql);
    //値をセット　bindParm(':カラム', 値, 値の型(PARAM_STRは文字列型))
    $stmt->bindParam(':name', $_POST["name"], PDO::PARAM_STR);
    $stmt->bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
    $stmt->bindParam(':time', $date);
    $stmt->bindParam(':password', $_POST["password"], PDO::PARAM_STR);
    $stmt->execute();
    
    echo "<br><br>新規投稿完了<br><br>";
    }
    
    //データの削除
    elseif(!empty($_POST["delete"]) && !empty($_POST["password_delete"])){
        //パスワードの一致確認
        $sql = 'SELECT * FROM boardm5 WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        //値のセット: bindParam(':カラム',値,値の型)
        $stmt->bindParam(':id',$_POST["delete"],PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetch();
            if($results['password']==$_POST["password_delete"]){//一致の場合
            //データの削除:'delete from テーブル名　where カラム=値'
            $sql = 'delete from boardm5 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $_POST["delete"], PDO::PARAM_INT);
            $stmt->execute();
            echo "<br><br>投稿番号".$_POST["delete"]."を削除しました<br><br>"; 
            }
            else{//不一致の場合
            echo "<br><br>パスワードが違います<br><br>";
            }
        
    }
    elseif(!empty($_POST["detele"]) && empty($_POST["password_detele"])){//パスワードがない場合
        echo "<br><br>パスワードを入力してください<br><br>";
    }
    
    //編集番号選択
    elseif(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){ 
    //パスワードの一致確認
    $sql = 'SELECT * FROM boardm5 WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    //値のセット: bindParam(':カラム',値,値の型)
    $stmt->bindParam(':id',$_POST["edit"],PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetch();
          if($results['password']==$_POST["password_edit"]){//パスワード一致
              echo "<br><br>投稿番号".$_POST["edit"]."を編集中<br><br>";
          }
          elseif($results['password']!=$_POST["password_edit"]){ //パスワード不一致
              echo "<br><br>パスワードが違います<br><br>";
          }
        
    }
    elseif(!empty($_POST["edit"]) && empty($_POST["password_edit"])){ //パスワードなし
    echo "<br><br>パスワードを入力してください<br><br>";
    }

    //データの編集
    elseif(!empty($_POST["name"]) && !empty($_POST["comment"] && !empty($_POST["edit_number"]))){
        $id = $_POST["edit_number"];
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        //データレコードの編集:'UPDATE テーブル名 set カラム=値 where カラム=変更する値'
        $sql = 'UPDATE boardm5 SET name=:name,comment=:comment,time=:time WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':time', $date);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo "<br><br>投稿番号".$_POST["edit_number"]."を編集しました<br><br>";
    }
    else{
        echo "<br><br>新規投稿なし<br><br>";
    }
    
    //データの表示
    $sql = 'SELECT * FROM boardm5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].'<br>';
        echo $row['name'].'<br>';
        echo $row['comment'].'<br>';
        echo $row['time'].'<br>';
    echo "<hr>";
    }
    
?>
</body>
</html>