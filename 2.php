
<?php


session_start();
$access_token = $_SESSION['access_token'];
$uid = $_SESSION['uid'];
session_write_close();

$dbhost = "localhost"; // Имя хоста БД
$dbusername = "root"; // Пользователь БД
$dbpass = "alientoor23"; // Пароль к базе
$dbname = "rtrg"; // Имя базы
$dbconnect = mysql_connect ($dbhost, $dbusername, $dbpass);

mysql_select_db($dbname);

ini_set('max_execution_time', 2300000);
set_time_limit(86400);

if ($_POST)
{
  $md5 = md5(rand(0,1000000).$_SESSION['uid'].$_SESSION['access_token']);
    $start = time();

        // $sql = mysql_query("INSERT INTO `parse` VALUES(NULL, '".$uid."', '".$mk."', '".$usl."', '".$md5."', 'GroupMembers')");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    if($_POST['md5']=="unchecked"){
    $list=explode("\r\n",$_POST['code']);
    }else{
    $valuable = file_get_contents("../tmp/output_".$_POST['md5'].".txt");
    $list=explode("\r\n",$valuable);
    }


    $cache = count($list);
    $results = array ();
    $names = array ();
    $groups = array ();
    $resultsaaa = array ();
    $id_count=0;
    $id_tot = 0;
    $rows=0;
    $numsa=0;
    $num = array ();
    $start_time = mktime();
    $total_members_count = 0;
    //set_time_limit (120);

    foreach($list as $group_id)
    {

  $parse = parse_url($group_id);

   $id = str_replace ("/club", "", trim($parse['path']));

   $id = str_replace ("/public", "", $id);
   $id = str_replace ("/event", "", $id);

   $id = str_replace ("/", "", $id);
        $names[]=$id;
    }

    $url='https://api.vk.com/method/groups.getById';
    $post = 'group_ids=';
    $cycle_count=0;
    $name_count=0;
    foreach($names as $value)
    {

	$post.=$value.",";
	$name_count++;

	if ($cycle_count==250 || count($names)==$name_count)
	{
	    $post.='&fields=members_count&&v=5.0&access_token='.$access_token;
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    	    $wall = curl_exec($ch);
    	    $wall = json_decode($wall);
    	    $wall = $wall->response;
    	    for ($i=0;$i<count($wall);$i++)
    	    {
    		$groups[$wall[$i]->id]=$wall[$i]->members_count;
    		$total_members_count = $total_members_count+$wall[$i]->members_count;
	    }
	    $url='https://api.vk.com/method/groups.getById?';
	    $post = 'group_ids=';
	    $cycle_count=0;
	}
    }

    $mk = mktime();

    $sql = mysql_query("INSERT INTO `parse` VALUES(NULL, ".$uid.", '1', '1', '".$md5."', 'GroupMembers')");



    $cycle_count=0;
    $query = "https://api.vk.com/method/execute?code=return[";
    foreach($groups as $key => $value)
    {
	$offset=0;
	while ($value>$offset)
	{

	    if ($_POST['filter']=="unsure")
		$query.='API.groups.getMembers({"group_id":"'.$key.'","count":"1000","offset":"'.$offset.'","filter":"unsure"}),';
		else $query.='API.groups.getMembers({"group_id":"'.$key.'","count":"1000","offset":"'.$offset.'"}),';
	    $offset+=1000;
	    $cycle_count++;

	    if ($cycle_count==25)
	    {
		$query.='];&v=5.29&access_token='.$access_token;
		$cycle_count=0;
		// Func start
		curl_setopt($ch, CURLOPT_URL, $query);
        	$wall = curl_exec($ch);
    		$wall = json_decode($wall);
    		while ($wall->error->error_code=="6")
    		{
    		    sleep (3);
        	    $wall = curl_exec($ch);
    		    $wall = json_decode($wall);
    		}
    		$wall = $wall->response;
		//print_r ($wall);
		for ($j = 0; $j < count($wall); $j++){
		    for ($k = 0; $k < count($wall[$j]->users); $k++)
		    {
	     // @$resultsaaa[$wall[$j]->users[$k]]++;
	    $id_list.=$wall[$j]->users[$k]."\r\n";

	    $rows++;
	    if($rows%150000==0){
	    $fp=fopen ("../tmp/output_un_$md5.txt", "a+");
	     stream_filter_append($fp, 'convert.iconv.UTF-8/OLD-ENCODING');
  		  fwrite ($fp, $id_list);
  			fclose($fp);
  			$id_list='';
  			  $memory = memory_get_peak_usage();
$memory = $memory/1024/1024;
	    $sql = mysql_query("INSERT INTO `log` VALUES(NULL, ".$uid.", '".$md5."', ".$rows.", ".$total_members_count.", '".$memory."')");
	    $sql = '';
    	}
	    }
		    }
		// Func end
		$query = "https://api.vk.com/method/execute?code=return[";
	    }
	}
    }

    if ($cycle_count>0)
    {
	$query.='];&v=5.29&access_token='.$access_token;
	// Func start
	curl_setopt($ch, CURLOPT_URL, $query);
	curl_setopt($ch, CURLOPT_POST, true);
        $wall = curl_exec($ch);
    	$wall = json_decode($wall);
    	$wall = $wall->response;
	//print_r ($wall);
	for ($j = 0; $j < count($wall); $j++){
	    for ($k = 0; $k < count($wall[$j]->users); $k++)
	    {
	   // @$resultsaaa[$wall[$j]->users[$k]]++;
	   $id_list.=$wall[$j]->users[$k]."\r\n";
	    $rows++;
	    if($rows%150000==0){
	  	  $fp=fopen ("../tmp/output_un_$md5.txt", "a+");
	  	  stream_filter_append($fp, 'convert.iconv.UTF-8/OLD-ENCODING');
 	   fwrite ($fp, $id_list);
 	 	fclose($fp);
 	 	$id_list='';
  			$memory = memory_get_peak_usage();
$memory = $memory/1024/1024;
	    $sql = mysql_query("INSERT INTO `log` VALUES(NULL, ".$uid.", '".$md5."', ".$rows.", ".$total_members_count.", '".$memory."')");
	    $sql = '';
    	}
	    }
	    }
	// Func end
    }

    curl_close($ch);



     $fp=fopen ("../tmp/output_un_$md5.txt", "a+");
      stream_filter_append($fp, 'convert.iconv.UTF-8/OLD-ENCODING');
    fwrite ($fp, $id_list);
  	fclose($fp);

  	unset($id_list);



     if($_POST['md5']=="unchecked"){
    $fp=fopen ("../tmp/input_$md5.txt", "w+");
    fwrite ($fp, $_POST['code']);
    fclose($fp);
    }else{
    $fp=fopen ("../tmp/input_$md5.txt", "w+");
    fwrite ($fp, '111');
    fclose($fp);
    }



    $mk = mktime();

    //$sys = system ("sort -q ../tmp/output_un_".$md5.".txt > ../tmp/output_".$md5.".txt");

    //$sys =   system ("uniq -c ../tmp/output_un2_".$md5.".txt | grep -v \"^      [5-6]\"  | cut -d\" \" -f8 > ../tmp/output_".$md5.".txt");

    //$sys = system("tr -d '\r' < ../tmp/output_un_".$md5.".txt > ../tmp/output_un2_".$md5.".txt");

    // $sys = system ("uniq -c ../tmp/output_un2_".$md5.".txt > ../tmp/output_".$md5.".txt");;



    if($_POST['cross']=='true'){
    $sel_num = $_POST['count']-1;
    if($sel_num=='1'){
    $sel_num = "1";
    }else{
    $sel_num = "1-".$sel_num;
    }
    $sys = system("sort ../tmp/output_un_".$md5.".txt | uniq -c | grep -v \"^      [".$sel_num."]\" | cut -d\" \" -f8 > ../tmp/output_".$md5.".txt");
    }

    if($_POST['cross']=='false'){
    $sys = system ("sort -u ../tmp/output_un_".$md5.".txt > ../tmp/output_".$md5.".txt");
    }

    $rem = system("rm ../tmp/output_un_".$md5.".txt");

     exec("cat ../tmp/output_".$md5.".txt | wc -l", $num);

    $usl = $num[0];

    $sqldel = mysql_query("DELETE FROM `parse` WHERE `output` = '".$md5."' ");

    $sqldel = mysql_query("DELETE FROM `log` WHERE `md5` = '".$md5."' ");

    $sql = mysql_query("INSERT INTO `parse` VALUES(NULL, ".$uid.", ".$mk.", ".$usl.", '".$md5."', 'GroupMembers')");



    $arr = array('id_count' => $usl, 'md5' => $md5, 'is_ending' => 'true');



    echo json_encode($arr);



}
?>
