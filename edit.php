<?
	include('../../../includes/db.php');

	db_select_db('cal_games');


	#
	# save changes?
	#

	if ($_POST[done]){

		$id = intval($_POST[id]);

		db_update('choose_rooms', array(
			'blurb'		=> AddSlashes($_POST[blurb]),
			'text_1'	=> AddSlashes($_POST[text_1]),
			'text_2'	=> AddSlashes($_POST[text_2]),
		), "id=$id");

		header("location: edit.php?id=$id&done=1");
		exit;
	}


	#
	# delete room?
	#

	if ($_POST[delete]){

		$id = intval($_POST[id]);

		$room = db_single(db_fetch("SELECT * FROM choose_rooms WHERE id=$id"));
		$parent	= db_single(db_fetch("SELECT * FROM choose_rooms WHERE room_1=$id OR room_2=$id"));

		db_write("DELETE FROM choose_rooms WHERE id=$id");
		db_write("UPDATE choose_rooms SET room_1=0 WHERE room_1=$id");
		db_write("UPDATE choose_rooms SET room_2=0 WHERE room_2=$id");

		header("location: edit.php?id=$parent[id]");
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


<h1>Edit <a href="room.php?room=<?=$room[id]?>">Room <?=$room[id]?></a></h1>

<? if ($parent[id]){ ?>
	<p>Parent room: <a href="edit.php?id=<?=$parent[id]?>">room <?=$parent[id]?></a>.</p>
<? } ?>

<? if ($HTTP_GET_VARS[done]){ ?>
	<div style="border: 1px solid #000000; padding: 10px; background-color: #eeeeee;">Your changes have been saved.</div>
<? } ?>

<form action="edit.php" method="post">
<input type="hidden" name="id" value="<?=$room[id]?>" />
<input type="hidden" name="done" value="1" />

	<br /><p>Description:<br /><textarea name="blurb" cols="50" rows="10"><?=HtmlSpecialChars($room[blurb])?></textarea></p>

<? if ($room[end_here]){ ?>

	<p>(stort ends here)</p>

	<input type="hidden" name="text_1" value="" />
	<input type="hidden" name="text_2" value="" />

<? }else{ ?>

	<p>	Choice 1:
<? if ($room[room_1]){ ?>
		(to <a href="edit.php?id=<?=$room[room_1]?>">room <?=$room[room_1]?></a>)
<? }else{ ?>
		(no story written)
<? } ?>
		<br /><input type="text" name="text_1" size="50" value="<?=HtmlSpecialChars($room[text_1])?>" />
	</p>

	<p>	Choice 2:
<? if ($room[room_2]){ ?>
		(to <a href="edit.php?id=<?=$room[room_2]?>">room <?=$room[room_2]?></a>)
<? }else{ ?>
		(no story written)
<? } ?>
		<br /><input type="text" name="text_2" size="50" value="<?=HtmlSpecialChars($room[text_2])?>" />
	</p>

<? } ?>

	<p>
		<input type="submit" value="Save Changes" />
	</p>
</form>

<?
	if ($room[room_1] || $room[room_2]){
?>

<p>This room can't be deleted - it has children.</p>

<?
	}else{
?>

<form action="edit.php" method="post">
<input type="hidden" name="id" value="<?=$room[id]?>" />
<input type="hidden" name="delete" value="1" />

	<p>
		<br />
		<br />
		<br />
		<br />
		<input type="submit" value="Delete This Room" style="background-color: red; color: white; font-weight: bold;" />
	</p>
</form>

<?
	}
?>


<?
	include('footer.txt');
?>