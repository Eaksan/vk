<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>cURL</title>
</head>
<body>

<?php

$url = 'https://api.vk.com/method/execute?code=return[';
$url1 = 'API.groups.getById({"group_ids":"1,2,3","fields":"members_count"}),';
$url2 = 'API.groups.getById({"group_ids":"4,5,6","fields":"members_count"})';
$token = '];&v=5.0&access_token=c0f4a503b6460b9a80b8962add1880ada8c24e94535b42fc5733639b876fa9e7d045ae8ba9d172ea87a57';
$execute = $url . $url1 . $url2 . $token;

echo 'Полная ссылка<br>'.$execute.'<br>';
echo '<br>';
$wall = file_get_contents($execute); // Отправляем запрос
$wall = json_decode($wall); // Преобразуем JSON-строку в массив
$wall = $wall->response;
$wall1 = $wall[0];// Получаем массив комментариев

echo '<br>';echo '<br>';

foreach ($wall1 as $group) {
    echo "<br> ID: " . $group->id . "<br>";
    echo "name: " . $group->name . "<br>";
    echo "Members: " . $group->members_count . "<br>";
    echo "Verified: " . $group->verified . "<br>";
    echo "Activity: " . $group->activity . "<br>";
    echo "Sity: " . $group->site . "<br>";
    echo "Type: " . $group->type . "<br>";
    echo "country: " . $group->country . "<br>";
    echo "city: " . $group->city . "<br>";
}
?>

</body>
</html>