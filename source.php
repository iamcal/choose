<?
	include('../../../includes/network.txt');

	mysql_select_db('cal_games');

	include('header.txt');
?>
	<b>The Source</b><br>
	<br>

	So you all want to know how this masterpiece was constructed, right? Right? Well, here's the source for your enjoyment.<br>
	<br>
	<br>

	The program requires PHP 4 and mysql, though it could easily be adapted to use other databases (or even other languages if you were feeling adventurous).<br>
	<br>
	<br>

	The original version, which requires register_globals to be on, is here:
	<a href="http://www.iamcal.com/games/choose/room_globals_on.phps">http://www.iamcal.com/games/choose/room_globals_on.phps</a><br>
	<br>
	<br>

	A new, updated version, which doesn't requires register_globals to be on, is here:
	<a href="http://www.iamcal.com/games/choose/room_globals_off.phps">http://www.iamcal.com/games/choose/room_globals_off.phps</a><br>
	<br>
	<br>

	A script to allow an administrator to edit rooms is here: (you'll want to password protect it)
	<a href="http://www.iamcal.com/games/choose/edit.phps">http://www.iamcal.com/games/choose/edit.phps</a><br>
	<br>
	<br>

	And finally you'll need the database schema:<br>
	<br>
	<div class="code">
	CREATE TABLE choose_rooms (<br>
	&nbsp;id int(11) NOT NULL auto_increment,<br>
	&nbsp;email varchar(255) NOT NULL default '',<br>
	&nbsp;blurb text NOT NULL,<br>
	&nbsp;text_1 varchar(255) NOT NULL default '',<br>
	&nbsp;room_1 int(11) NOT NULL default '0',<br>
	&nbsp;text_2 varchar(255) NOT NULL default '',<br>
	&nbsp;room_2 int(11) NOT NULL default '0',<br>
	&nbsp;end_here tinyint(4) NOT NULL default '0',<br>
	&nbsp;ip varchar(255) NOT NULL default '',<br>
	&nbsp;PRIMARY KEY (id),<br>
	) TYPE=MyISAM;<br>
	<br>
	INSERT INTO choose_rooms VALUES (1, 'cal@iamcal.com', 'First Room', 'Choice 1', 0, 'Choice 2', 0, 0, '');<br>
	</div>
	<br>

<?php include('footer.txt'); ?>
