<?
	include('../../../includes/network.txt');

	mysql_select_db('cal_games');

	include('header.txt');

	print "The following people have added pages to the story:<br><br>";

	$result = mysql_query("SELECT COUNT(id) AS c,email FROM choose_rooms GROUP BY email ORDER BY c DESC",$db);
	while($row = mysql_fetch_array($result)){
		print "$row[c] - $row[email]<br>";
	}

	include('footer.txt');
?>