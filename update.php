<?php

require_once("db.php");
set_time_limit(1300);

function removeslashes($string)
{
    $string=implode("",explode("\\",$string));
    return stripslashes(trim($string));
}
function bd($numbers){

    $numbers;
    $wall = file_get_contents("https://api.vk.com/method/groups.getById?group_ids=" . $numbers . "&fields=members_count,verified,activity,site,city,ban_info,country&v=5.0");
    $st = $wall;
    $j = 0;
    while (preg_match('/id/', $st, $m)) {

        $st = stristr($st, 'id"');
        $pos = strpos($st, 'name"');
        $id_in[$j] = substr($st, 4, $pos - 6);

        $st = stristr($st, 'name"');
        $pos = strpos($st, 'screen_name"');
        $name_in[$j] = substr($st, 7, $pos - 10);
        $name_in[$j] = removeslashes($name_in[$j]);
        $name_in[$j] = stripslashes(stripslashes($name_in[$j]));
        $name_in[$j] = htmlentities($name_in[$j], ENT_SUBSTITUTE | ENT_IGNORE | ENT_QUOTES, 'UTF-8');

        $st = stristr($st, 'type"');
        $pos = strpos($st, 'members_count"');
        $type_in[$j] = substr($st, 7, $pos - 10);

        $st = stristr($st, 'members_count"');
        $pos = strpos($st, 'verified"');
        $members_in[$j] = substr($st, 15, $pos - 17);

        $st = stristr($st, 'verified"');
        $pos = strpos($st, 'activity"');
        $verified_in[$j] = substr($st, 10, $pos - 12);

        $st = stristr($st, 'activity"');
        $pos = strpos($st, 'site"');
        $activity_in[$j] = substr($st, 11, $pos - 14);



        if($type_in[$j] != 'page') {
            $st = stristr($st, 'site"');
            $pos = strpos($st, 'city"');
            $site_in[$j] = substr($st, 7, $pos - 10);
            $site_in[$j] = htmlentities($site_in[$j], ENT_QUOTES, 'UTF-8');


            $st = stristr($st, 'city"');
            $pos = strpos($st, 'country"');
            $city_in[$j] = substr($st, 6, $pos - 8);

            $st = stristr($st, 'country"');
            $pos = strpos($st, ',');
            $country_in[$j] = substr($st, 9, $pos - 9);
        }
        if($type_in[$j] == 'page') {

            substr($st, 0, 50);
            $st = stristr($st, 'site"');
            $pos = strpos($st, 'photo_50"');
            $site_in[$j] = substr($st, 7, $pos - 10);
            $site_in[$j] = htmlentities($site_in[$j], ENT_QUOTES, 'UTF-8');
        }

        //echo 'Для группы №'.$id_in[$j].' количество пользователей ='.$members_in[$j].'<br>';

        $j++;

    }
    echo '<br>';echo '<br>';
    $k = 0;
    while($k<=$j) {
        $a = date(time(),'H:i:s');
        mysql_query("UPDATE vk SET

                          name='$name_in[$k]',
                          type='checked',
                          members_count='$members_in[$k]',
                          verified='$verified_in[$k]',
                          activity='$activity_in[$k]',
                          site='$site_in[$k]',
                          city='$city_in[$k]',
                          country='$country_in[$k]',
                          time = '$a'

                          WHERE id_group='$id_in[$k]'
                 ");
        $k++;
        //echo 'Группа №' . $id_in[$k] . ' обновлена.<br>';
    }

}
$start = time();
$group_numb = 200;
$i = 0;

$in = mysql_query(" SELECT name,id_group,members_count FROM vk ");

while ($info = mysql_fetch_array($in)) {

    if ($info['name'] != 'DELETED' and $info['name'] != 'Закрытая группа' and $info['members_count'] > 10) {

        $numbers .= $info['id_group'] . ",";
        //echo $numbers.'<br>';
        $i++;
        if($i==200) {

            $i = 0;;
            bd($numbers);
            $numbers = '';

        }
    }

}

bd($numbers);
    //echo $numbers;
    //echo "https://api.vk.com/method/groups.getById?group_ids=" . $numbers . "&fields=members_count,verified,activity,site,city,ban_info,country&v=5.0";


mysql_close();
$end = time();

echo "Начальное время:".date($start,'H:i:s').'<br>';
echo "Конечно время:".date($end,'H:i:s').'<br>';
echo 'Время выполнения:'.date($end-$start,'H:i:s');
?>

