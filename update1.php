<?php

require_once("db.php");

function removeslashes($string)
{
    $string=implode("",explode("\\",$string));
    return stripslashes(trim($string));
}
$group_numb = 200;

$in = mysql_query(" SELECT name,id_group,members_count FROM vk ");

$i = 0;

while ($info = mysql_fetch_array($in)) { //$info['name'] != 'DELETED' and $info['name'] != 'Закрытая группа' and
    echo $info['id_group']; echo '<br>';
    if ($info['members_count'] > 1) {

        $numbers .= $info['id_group'] . ",";

        //echo $numbers.'<br>';
        $i++;
        if($i==200 or mysql_fetch_array($in)=== 'FALSE') {
            echo $i;
            $i= 0;
            //echo $numbers.'<br>';
            echo $wall = file_get_contents("https://api.vk.com/method/groups.getById?group_ids=" . $numbers . "&fields=members_count,verified,activity,site,city,ban_info,country&v=5.0".$token); //&access_token=ca814ffb55ce44521c8449a05559502ccfeb06c692b19ce67a19a02cd9ed77c1e0e17000a7c6a3887f761 Отправляем запрос
            $wall = json_decode($wall); // Преобразуем JSON-строку в массив
            $wall = $wall->response;

            foreach ($wall as $group) {
               /* echo "<br>ID: " . $group->id . "<br>";
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

                     mysql_query("UPDATE vk SET
                                  name='$name',
                                  members_count='$members',
                                  verified='$verified'
                                  activity='$activity',
                                  site='$site',
                                  type='$type'
                                  country='$country',
                                  city='$city'

                                  WHERE id_group='$id'
                 ");
                echo 'Группа №'.$id.' обновлена.<br>';



            }
            unset($numbers);
            $numbers = '';

        }
    }

}
    //echo $numbers;
    //echo "https://api.vk.com/method/groups.getById?group_ids=" . $numbers . "&fields=members_count,verified,activity,site,city,ban_info,country&v=5.0";


mysql_close();



?>

