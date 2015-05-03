<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>группы</title>
</head>
<body>

<!--https://api.vk.com/method/groups.getById?group_ids=1,2,3,29557406&fields=members_count,verified,activity,site,city,ban_info&v=5.0/-->

<?php

$ch = curl_init();

//require_once ('db.php');

/*for($count=0; $i < 10; $count++) {

    for ($i = $count * 10; $i < ($count + 1) * 10; $i++) {

        $numbers = $numbers . "," . $i;

    }*/
    $url = "https://api.vk.com/method/execute?code=return[";
    $url1 = 'API.groups.getById({"group_ids":"1,2,3","fields":"members_count"}),';
    $url2 = 'API.groups.getById({"group_ids":"4,5,6","fields":"members_count"})';
    $token = '];&v=5.0&access_token=c0f4a503b6460b9a80b8962add1880ada8c24e94535b42fc5733639b876fa9e7d045ae8ba9d172ea87a57';
    $execute = $url . $url1 . $url2 . $token;

    //echo $execute;
$a = 'https://api.vk.com/method/groups.getById?group_ids=1,2,3&fields=members_count,verified,activity,site,cite&v=5.0';

curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$d = curl_exec($ch);
curl_close($ch);
print_r ($d);
$d = json_decode($d);
print_r ($d);
$d = $d->response;
print_r ($d);

echo '<br>';
print_r ($d);



/*echo $url;
echo $url1;
echo $token;
echo $execute;

    /*$wall = file_get_contents('https://api.vk.com/method/execute?code=return
                                [API.groups.getById({"group_ids":"1,2,3","fields":"members_count"})
                                ];&v=5.0&access_token=4fb34c75f5c24e2898b756d192c1e9c21130f9e572e8dbe229828cfdee43066fea217944671ab8beb5ba5a6a03cab'); // Отправляем запрос
    $wall = json_decode($wall); // Преобразуем JSON-строку в массив
    $wall = $wall->response;// Получаем массив комментариев
    print_r($wall);
    $numbers = 0;
}
   /* $wall = file_get_contents("https://api.vk.com/method/groups.getById?group_ids=" . $numbers . "&fields=members_count,verified,activity,site,city,ban_info,country&v=5.0"); // Отправляем запрос
    $wall = json_decode($wall); // Преобразуем JSON-строку в массив
    $wall = $wall->response;// Получаем массив комментариев

}

    foreach ($wall as $group) {
        echo "<br>ID: " . $group->id . "<br>";
        echo "name: " . $group->name . "<br>";
        echo "Members: " . $group->members_count . "<br>";
        echo "Verified: " . $group->verified . "<br>";
        echo "Activity: " . $group->activity . "<br>";
        echo "Sity: " . $group->site . "<br>";
        echo "Type: " . $group->type . "<br>";
        echo "country: " . $group->country . "<br>";
        echo "city: " . $group->city . "<br>";

        $id = $group->id;
        $name = $group->name;
        $members = $group->members_count;
        $verified = $group->verified;
        $activity = $group->activity;
        $site = $group->site;
        $type = $group->type;
        $country = $group->country;
        $city = $group->city;

        mysql_query("INSERT INTO vk (id,name,members_count,verified,activity,site,type,country,city)
              VALUES ('$id','$name','$members','$verified','$activity','$site','$type','$country','$city')
              ");


    }
    unset($numbers);
    $numbers = 0;
}
mysql_close();*/


?>

</body>
</html>