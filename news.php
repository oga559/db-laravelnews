<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
function newNews(){ //データベースに送信
    require("dbconnect.php");
    if($_SERVER["REQUEST_METHOD"] === "POST"){
            $title = $_POST["title"]; //titleを受け取る
            $text =  $_POST["text"]; //kijiを受け取る
            $title_number = mb_strlen($title);//文字列の長さを取得する
            $kiji_number = mb_strlen($text);//文字列の長さを取得する
            if(empty($title)){ //タイトル未記入でエラー文表示
               echo "※タイトルを入力してください<br>";
            }
            if(empty($text)){  //記事が未入力でエラー文表示
                echo "※記事を入力してください<br>";
            }
            if($title_number>30){ //タイトルの文字数30以上でエラー文表示
                echo "※30文字以下で入力してください<br>";
            }
            if(!empty($title_number) && $title_number<=30 && !empty($kiji_number)){//タイトルと記事が入力済み、記事30文字以下で実行
                $statement = $db->prepare('INSERT INTO articles(title,text) VALUES(:title,:text)');
                $statement->execute(array(":title"=>$title,":text"=>$text));
                header('Location: ' . $_SERVER['REQUEST_URI']);//手動でURLを指定すると送信されないことを利用しリロードによる二重投稿対策
                exit;//プログラムを終了
            }
            }
    }
    function newsDate(){ //画面に表示する
        require("dbconnect.php");
        $messages = $db->query('SELECT * FROM articles ORDER BY id DESC');
        while($message = $messages->fetch()){
            $message_number = mb_strlen($message['text']);
            if($message_number>=30){
                $message['text'] = mb_strimwidth(($message['text']),0,70,"...");//超過したら...に変換
            }
            echo "<hr>"."<p>"."タイトル:".$message['title']."</p>";
            echo "<p>"."記事:".$message['text']."</p>";
            echo  "<a href=comment.php?id=".$message['id'].">"."記事全体を表示"."</a>";
        }
        }
?>
  <!DOCTYPE html>
  <html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="news.css">
    <title>laravel news</title>
  </head>
  <body>
    <header> <a href="news.php">laravel news</a></header>
    <form action="news.php" method="POST"> 
    <label for="title">タイトル</label> 
    <input type="text" name="title" placeholder="タイトル" class="toukou"> 
    <label for="kiji">記事</label> 
    <textarea name="text" cols="30" rows="10" placeholder="記事" class="toukou"></textarea> 
    <input type="submit" name="toukou" value="投稿" class="toukou" onclick="return confirm('投稿しますか?')"> </form>
    <?php
newNews();//テキストファイルに書き込む
newsDate();//画面に表示
     ?>
    <hr> </form>
  </body>
  </html>