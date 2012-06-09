<?
	include('../../../includes/db.php');

	db_select_db('cal_games');


	#
	# save changes?
	#

	if ($_POST[done]){

		$id = intval($_POST[id]);

		$subject = "[iamcal-choose] report for room $id";
		$message = "http://www.iamcal.com/games/choose/edit.php?id=$id\n\n".$_POST[message];

		mail('cal@tinyspeck.com', $subject, $message);


		include('header.txt');
		echo "Thanks! The authorities have been alerted to your plight.";
		include('footer.txt');

		exit;
	}


	#
	# get info for display
	#

	$room_id = intval($_GET[id]);

	$room	= db_single(db_fetch("SELECT * FROM choose_rooms WHERE id=$room_id"));
	$parent	= db_single(db_fetch("SELECT * FROM choose_rooms WHERE room_1=$room_id OR room_2=$room_id"));

	if (!$room[id]){
		include('header.txt');
		print "error: room $room_id not found";
		include('footer.txt');
		exit;
	}


	include('header.txt');
?>


	<h1>Report <a href="room.php?room=<?=$room[id]?>">Room <?=$room[id]?></a></h1>
	<br />

	Description:<br />
	<div class="boxy"><?=HtmlSpecialChars($room[blurb])?></div>
	<br />

	Choice 1:<br />
	<div class="boxy"><?=HtmlSpecialChars($room[text_1])?></div>
	<br />

	Choice 2:<br />
	<div class="boxy"><?=HtmlSpecialChars($room[text_2])?></div>
	<br />

<form action="report.php" method="post">
<input type="hidden" name="id" value="<?=$room[id]?>" />
<input type="hidden" name="done" value="1" />

	<br /><p>Complaint:<br /><textarea name="message" cols="50" rows="10"></textarea></p>

	<p>
		<input type="submit" value="Send Report" />
	</p>
</form>


<?
	include('footer.txt');
?>