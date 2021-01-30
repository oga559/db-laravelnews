<?php
  $dbconnet = require("dbconnect.php");
  $url_id = $_GET['id'];
  //記事とタイトルをデータベースから取得表示
  $stmt = $db->query("SELECT * FROM articles WHERE id = $url_id");
  //データベースにコメント投稿と削除
  if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["comment"])){
      $comment_number = mb_strlen($_POST["comment"]);
      if($comment_number>50){
        $error[] = "50文字以下にしてください";
      }
      if($comment_number!=0&$comment_number<50){
        $statement = $db->prepare('INSERT INTO comment(article_id,comment) VALUES(:article_id,:comment)');
        $statement->execute(array(":article_id"=>$url_id,":comment"=>$_POST["comment"]));
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
      }
    }
    if(empty($_POST["comment"])&empty($_POST["delete"])){
      $error[] = "コメント入力は必須です";
    }
    if(isset($_POST["delete"])){
          $delete = $db->prepare('DELETE FROM comment WHERE id=:id');
          $delete->execute(array(":id"=>$_POST["delete"]));
        }
      }
?>
  <!DOCTYPE html>
  <html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="news.css">
    <title>詳細ページ</title>
  </head>
  <body>
    <header> <a href="news.php">laravel news</a> </header>
    <div class="news.php-data">
      <?php while($output = $stmt->fetch()):?>
      <?php echo "<hr>"."<p>"."タイトル:".$output['title']."</p>";
      echo "<p>"."記事:".$output['text']."</p>";?>
      <?php endwhile?> </div>
    <hr>
    <form action="comment.php?id=<?php echo $url_id?>" method="POST"> 
    <textarea name="comment" cols="30" rows="10" class="toukou"></textarea> 
    <input type="submit" value="コメントを書く"> </form>
    <!-- エラー文表示 -->
    <?php
    if($_SERVER["REQUEST_METHOD"] === "POST"){
      if(empty($_POST["comment"])&empty($_POST["delete"])){
      foreach ($error as $error_comment){
        echo $error_comment;
      }
      }
    }
    ?>
      <?php
    //コメント表示のためのSQL
  $comment_output_stmt = $db->query("SELECT * FROM comment WHERE article_id = $url_id");
  while($comment_output = $comment_output_stmt->fetch()): 
  ?>
        <p>
          <?php echo $comment_output['comment'];?>
        </p>
        <form method="post"> <input type="hidden" name="delete" value="<?php echo $comment_output['id']?>"> 
        <input type="submit" value="コメント削除"> 
        </form>
        <?php endwhile;?> </body>
  </html>