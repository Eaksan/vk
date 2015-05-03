<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Добавлеине новости</title>
</head>
<body>

<form method="post" action="add.php">

НАзвание новости <br />
<input type="text" name="title" /><br />

Текст новости <br />
<textarea cols="40" rows="10" name="text"></textarea><br />

Автор новости<br />
<input type="text" name="author" ><br />
<input type="hidden" name="date" value="<?php echo date('Y-m-d');?>" />
<input type="hidden" name="time" value="<?php echo date('H:i:s');?>"/><br />

<input type="submit" name="add" value="добавить" />

</form>

<?php

require_once("db.php");

if(isset($_POST['add'])) {
    $title = strip_tags(trim($_POST['title']));
    $text = strip_tags(trim($_POST['text']));
    $author = strip_tags(trim($_POST['author']));
    $date = strip_tags(trim($_POST['date']));
    $time = strip_tags(trim($_POST['time']));

    mysql_query("INSERT INTO vk(id,name,city)
              VALUES ('$title','$text','$author')
              ");
    mysql_close();
    echo "Новость добавлена!";


}


?>

</body>
</html>