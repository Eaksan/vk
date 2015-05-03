<?php

    $start = date('d-m H:i:s');// время начала
    set_time_limit(1300);//секунд на выполнение
    require_once('db.php');//файл доступа к БД

    $group_count = $_GET['end']; //сколько групп парсить
    $group_start = $_GET['start'];//с какой начинать
    $token = $_GET['token'];
    $group_numb = 200;//по сколько групп в завпросе


function removeslashes($string)
{
    $string=implode("",explode("\\",$string));
    return stripslashes(trim($string));
}



//формирование запроса
    for ($count = $group_start / $group_numb; $i < $group_count; $count++) {

        for ($i = $count * $group_numb; $i < ($count + 1) * $group_numb; $i++) {

            $numbers = $numbers . $i . ",";

        }
        //echo "https://api.vk.com/method/groups.getById?group_ids=" . $numbers . "&fields=members_count,verified,activity,site,city,ban_info,country&v=5.0";
        $wall = file_get_contents("https://api.vk.com/method/groups.getById?group_ids=" . $numbers . "&fields=members_count,verified,activity,site,city,ban_info,country&v=5.0".$token); //&access_token=ca814ffb55ce44521c8449a05559502ccfeb06c692b19ce67a19a02cd9ed77c1e0e17000a7c6a3887f761 Отправляем запрос
        $wall = json_decode($wall); // Преобразуем JSON-строку в массив
        $wall = $wall->response;


        foreach ($wall as $group) {
            /*echo "<br>ID: " . $group->id . "<br>";
            echo "name: " . htmlentities($group->name,ENT_SUBSTITUTE | ENT_IGNORE | ENT_QUOTES, 'UTF-8') . "<br>";
            echo "Members: " . $group->members_count . "<br>";
            echo "Verified: " . $group->verified . "<br>";
            echo "Activity: " . $group->activity . "<br>";
            echo "Sity: " . $group->site . "<br>";
            echo "Type: " . $group->type . "<br>";
            echo "country: " . $group->country . "<br>";
            echo "city: " . $group->city . "<br>";*/

            $id = $group->id;
            $name = $group->name;
            $name = removeslashes($name);
            $name = stripslashes(stripslashes($name));
            $name = htmlentities($name, ENT_SUBSTITUTE | ENT_IGNORE | ENT_QUOTES, 'UTF-8');
            $members = $group->members_count;
            $verified = $group->verified;
            $activity = $group->activity;
            $site = htmlentities($group->site, ENT_QUOTES, 'UTF-8');
            $type = $group->type;
            $country = $group->country;
            $city = $group->city;

            if($name!= 'DELETED' and $name!= 'Закрытая группа' and $members > 10)
            {
                mysql_query("INSERT INTO vk (id_group,name,members_count,verified,activity,site,type,country,city)
                VALUES ('$id','$name','$members','$verified','$activity','$site','$type','$country','$city')
                ");// запись в БД
            }


        }
        unset($numbers);
        $numbers = '';

    }
    mysql_close();
    $end = date('d-m H:i:s');

    echo 'Начало:' . $start . "<br>Окончание:" . $end;



?>
