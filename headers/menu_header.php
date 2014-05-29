<?php
//MENU HEADER

function print_menu() {

require('config.php');
printf("<link rel=stylesheet href=css/style.css type=text/css>");

	printf("<table class=menu border=0 align=center><tr>");

	if(is_member("member") && get_username() != $station_wide_login_name) {
		printf("<td class=menu><a href=useradd.php?action=list>Users</a></td>");
	}
	if(is_member("membership") && $enabled['membership']) {

		 echo '<td class=menu><a href="membership.php">Membership</a></td>';
	}
	if(is_member("library") && $enabled['library']) {
		echo '<td class=menu><a href="library.php">Library</a></td>';
	}
	if(is_member("editlibrary") && $enabled['library'] ) {
		echo '<td class=menu><a href="library.php?action=add">Update Library</a></td>';
	}
	if(is_member("addshow") ) {
	if($enabled['shows'])	echo "<td class=menu><a href='shows.php?action=list'>Shows</a></td>";
	if($enabled['adscheduler'])	echo "<td class=menu><a href='adscheduler.php'>Ad Scheduler</a></td>";
	if($enabled['adscheduler'])	echo "<td class=menu><a href='adreport.php'>Ad Report</a></td>";
	if($enabled['charts'])	echo "<td class=menu><a href='charting.php'>Charts</a></td>";
	if($enabled['report'])	echo "<td class=menu><a href='crtcreport.php'>CRTC Report</a></td>";
	}
	if(is_member("dj")) {
	if($enabled['playsheets'])	echo "<td class=menu><a href='playsheet.php?action=list'>Playsheets</a></td>";
	if($enabled['playsheets'])	echo "<td class=menu><a href='playsheet.php'>New Playsheet</a></td>";
		//printf("<td class=menu><a href='playsheet.php?action=report'>Report</a></td>");
	if($enabled['report'])	echo "<td class=menu><a href='report.php'> Report (by show) </a></td>";
		echo "<td class=menu><a href='help.php' target='_blank'> Help </a></td>";
	}
	printf("<td class=menu><A HREF='index.php?action=logout'>Log Out</a></td>");

	printf("</tr></table>");

	if(isset($_SESSION['sv_login_fails']) && $_SESSION['sv_login_fails']) {
		printf("<br>");
		printf("<BR><center>WARNING!<BR>%s Login Failures</center>\n", $_SESSION['sv_login_fails']);
	}
}
?>

<?
function print_menu2(){
require('config.php');
?>
	<ul id=nav>
		<? if(is_member("member") && get_username() != $station_wide_login_name): ?>
		<li class=nodrop><a href=useradd.php?action=list>Users</a></li>
		<? endif; ?>
		<? if(is_member("membership") && $enabled['membership']) : ?>
		<li class=nodrop><a href="membership.php">Membership</a></li>	
		<? endif; ?>
		<li class=drop><a href='#'>Library</a>
			<div class=dropdown_small>
				<div class=small>
					<ul>
						<? if(is_member("library") && $enabled['library']) : ?>
						<li><a href="library.php"> View Library</a></li>
						<? endif; ?>
						<? if(is_member("editlibrary") && $enabled['library']) : ?>
						<li><a href="library.php?action=add">Update Library</a></li>
						<? endif; ?>
					</ul>
				</div>
			</div>
		</li>

	<? if(is_member("addshow")) : 
		if($enabled['shows']) :?>
			<li class=nodrop><a href="shows.php?action=list">Shows</a></li>
		<? endif; 
		if($enabled['adscheduler']) : ?>
			<li class=drop><a href="#">Manage Ads</a>
				<div class=dropdown_small>
					<div class=small>
						<ul>
							<li><a href="adscheduler.php">Ad Scheduler</a></li>
							<li><a href="adreport.php">Ad Reporting</a></li>
							<li><a href="samAds.php">Sam Ad History</a></li>
						</ul>
					</div>
				</div>
			</li>
		<? endif; 
		if($enabled['charts']) :?>
			<li class=nodrop><a href="charting.php">Charts</a></li>
		<? endif;
	endif; 
	if($enabled['report']): ?>
		<li class=drop ><a href="#">Reports</a>
			<div class=dropdown_small>
				<div class=small>
					<ul>
						<?php if(is_member("dj")) : ?> 
							<li><a href="report.php">Show Report</a></li> 
						<?php endif;
						if(is_member("addshow")) : ?>
							<li><a href="crtcreport.php">CRTC Report</a></li> 
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</li>
		<? endif; ?>
		<li class=drop><a href="#">Playsheets</a>
			<div class=dropdown_small>
				<div class=small>
					<ul>
						<?php if(is_member("dj") && ($enabled['playsheets'])) : ?> 
							<li><a href="playsheet.php">New Playsheet</a></li>
							<li><a href="playsheet.php?socan=true">New Socan Playsheet</a></li>
							<li><a href="playsheet.php?action=list">Open a Playsheet</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</li>
		<li class="menu_right drop"><a href="#"><img src=images/gear.png style="width:20px;"></a>
			<div class="dropdown_small align_right">
				<div class=small>
					<ul>
						<?php if(is_member("dj")) : ?>
							<li><a href="help.php" target="_blank"> Help </a></li>
						<?php endif; ?>
							<li><a href="index.php?action=logout">Log Out</a></li>
					</ul>
				</div>
			</div>
		</li>
	</ul>
<? } ?>