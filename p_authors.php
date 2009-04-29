<?
	include('../../../includes/db.php');

	db_select_db('cal_games');

	include('header.txt');

	print "The following people have added pages to the story:<br><br>";

	$ret = db_fetch("SELECT COUNT(id) AS c,email FROM choose_rooms GROUP BY email ORDER BY c DESC");
	foreach ($ret[rows] as $row){
		$row[email] = HtmlSpecialChars($row[email]);
		print "$row[c] - $row[email]<br>";
	}

	include('footer.txt');
?>