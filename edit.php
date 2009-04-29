<?
	include('../../../includes/network.txt');

	mysql_select_db('cal_games');


	if ($HTTP_POST_VARS[done]){
		$id = $HTTP_POST_VARS[id];
		$blurb = $HTTP_POST_VARS[blurb];
		$text_1 = $HTTP_POST_VARS[text_1];
		$text_2 = $HTTP_POST_VARS[text_2];

		mysql_query("UPDATE choose_rooms SET blurb='$blurb', text_1='$text_1', text_2='$text_2' WHERE id='$id'",$db);

		header("location: edit.php?id=$id&done=1");
		exit;
	}


	$room = $HTTP_GET_VARS[id];

	$room_row = mysql_fetch_array(mysql_query("SELECT * FROM choose_rooms WHERE id='$room'",$db));

	$parent_row = mysql_fetch_array(mysql_query("SELECT * FROM choose_rooms WHERE room_1='$room' OR room_2='$room'",$db));


	if (!$room_row[id]){
		include('header.txt');
		print "error: room $room not found";
		include('footer.txt');
		exit;
	}


	include('header.txt');
?>

<h1>Edit <a href="room.php?room=<?=$room_row[id]?>">Room <?=$room_row[id]?></a></h1>

<? if ($parent_row[id]){ ?>
	<p>Parent room: <a href="edit.php?id=<?=$parent_row[id]?>">room <?=$parent_row[id]?></a>.</p>
<? } ?>

<? if ($HTTP_GET_VARS[done]){ ?>
	<div style="border: 1px solid #000000; padding: 10px; background-color: #eeeeee;">Your changes have been saved.</div>
<? } ?>

<form action="edit.php" method="post">
<input type="hidden" name="id" value="<?=$room_row[id]?>" />
<input type="hidden" name="done" value="1" />

	<p>blurb:<br /><textarea name="blurb" cols="50" rows="10"><?=HtmlSpecialChars($room_row[blurb])?></textarea></p>

<? if ($room_row[end_here]){ ?>

	<p>(stort ends here)</p>

	<input type="hidden" name="text_1" value="" />
	<input type="hidden" name="text_2" value="" />

<? }else{ ?>

	<p>	choice 1:
<? if ($room_row[room_1]){ ?>
		(to <a href="edit.php?id=<?=$room_row[room_1]?>">room <?=$room_row[room_1]?></a>)
<? }else{ ?>
		(no story written)
<? } ?>
		<br /><input type="text" name="text_1" size="50" value="<?=HtmlSpecialChars($room_row[text_1])?>" />
	</p>

	<p>	choice 2:
<? if ($room_row[room_2]){ ?>
		(to <a href="edit.php?id=<?=$room_row[room_2]?>">room <?=$room_row[room_2]?></a>)
<? }else{ ?>
		(no story written)
<? } ?>
		<br /><input type="text" name="text_2" size="50" value="<?=HtmlSpecialChars($room_row[text_2])?>" />
	</p>

<? } ?>

	<p>
		<input type="submit" value="Save Changes" />
	</p>
</form>

<?
	include('footer.txt');
?>