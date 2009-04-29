<?
	$db = mysql_connect("**********", "**********", "**********");
	mysql_select_db("**********",$db);

	$vars = array_merge($HTTP_POST_VARS, $HTTP_GET_VARS);

	if ($vars['email']){
		setcookie('email_cookie', $vars['email'], time()+(60*60*24*365));
	}

	include('header.txt');

	list($rooms) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM choose_rooms",$db));
	$insert = "<b>Rooms:</b> $rooms";

	function getRoomDepth($room){
		global $db;
		if ($room == 1){return 1;}
		$data = mysql_fetch_array(mysql_query("SELECT * FROM choose_rooms WHERE room_1='$room' OR room_2='$room'",$db));
		return 1 + getRoomDepth($data[id]);
	}

	if ($vars['addroom']){
		mysql_query("INSERT INTO choose_rooms (email,blurb,text_1,room_1,text_2,room_2,end_here,ip) VALUES ('{$vars['email']}', '{$vars['blurb']}', '{$vars['choice1']}', 0, '{$vars['choice2']}', 0, '{$vars['end_here']}', '$REMOTE_ADDR')", $db);
		$id = mysql_insert_id();
		mysql_query("UPDATE choose_rooms SET room_{$vars['opt']}=$id WHERE id='{$vars['from']}'",$db);
		print "Your room has been added. <a href=\"room.php\">Click here</a> to start again.";
		include('footer.txt');
		exit;
	}

	if ($vars['room'] == '0'){
		$fromrow = mysql_fetch_array(mysql_query("SELECT * FROM choose_rooms WHERE id='{$vars['from']}'",$db));
		$depth = getRoomDepth($vars['from']);
		print "<!-- $depth -->";
		print "Now it's time for you to create your own adventure :)<br><br>";
		print "What happens when someone chooses &quot;".$fromrow["text_".$vars['opt']]."&quot;?<br>";
		print "<form method=\"post\">";
		print "<input type=\"hidden\" name=\"addroom\" value=\"1\">";
		print "<input type=\"hidden\" name=\"from\" value=\"{$vars['from']}\">";
		print "<input type=\"hidden\" name=\"opt\" value=\"{$vars['opt']}\">";
		print "your email: (it wont be shown on the site)<br><input type=\"text\" name=\"email\" size=\"50\" value=\"{$HTTP_COOKIE_VARS['email_cookie']}\"><br><br>";
		print "blurb:<br><textarea name=\"blurb\" cols=\"50\" rows=\"10\"></textarea><br><br>";
		if ($depth>9){
			print "<select name=\"end_here\"><option value=\"0\">The adventure continues...</option><option value=\"1\">The adventure ends here</option></select><br><br>";
		}else{
			print "<input type=\"hidden\" name=\"end_here\" value=\"0\">";
		}
		print "choice 1:<br><input type=\"text\" name=\"choice1\" size=\"50\"><br><br>";
		print "choice 2:<br><input type=\"text\" name=\"choice2\" size=\"50\"><br><br>";
		print "<input type=\"submit\" value=\"Add My Room!\">";
		include('footer.txt');
		exit;
	}

	$room = ($vars['room'] != '')?$vars['room']:1;

	$data = mysql_fetch_array(mysql_query("SELECT * FROM choose_rooms WHERE id='$room'",$db));

	if (!$data[id]){
		print "error: room $room not found";
		include('footer.txt');
		exit;
	}

	$depth = getRoomDepth($room);

	$data = mysql_fetch_array(mysql_query("SELECT * FROM choose_rooms WHERE id='$room'",$db));

	if ($data[end_here]){
		print nl2br(htmlentities(chop($data[blurb])));
		print "<br><br><b>It's all over.</b> Why not <a href=\"room.php\">start again</a>.";
	}else{
		print nl2br(htmlentities(chop($data[blurb])));
		print "<br><br><b>Choose:</b> ";
		print "<a href=\"room.php?room=$data[room_1]&from=$room&opt=1\">".htmlentities($data[text_1])."</a>";
		print " or ";
		print "<a href=\"room.php?room=$data[room_2]&from=$room&opt=2\">".htmlentities($data[text_2])."</a>";
	}
	print "<br><br><br><br>Something wrong with this entry? Bad spelling/grammar? Too offensive? Then <a href=\"mailto:choose@iamcal.com?subject=entry $data[id]\">tell me</a>.";

	include('footer.txt');

?>