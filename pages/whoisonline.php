<?php
if(!defined('INITIALIZED'))
	exit;

$orderby = 'name';
if(isset($_REQUEST['order']))
{
	if($_REQUEST['order']== 'level')
		$orderby = 'level';
	elseif($_REQUEST['order'] == 'vocation')
		$orderby = 'vocation';
}
$players_online_data = $SQL->query('SELECT ' . $SQL->tableName('accounts') . '.' . $SQL->fieldName('flag') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('name') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('vocation') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('level') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('skull') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('looktype') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('lookaddons') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('lookhead') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('lookbody') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('looklegs') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('lookfeet') . ' FROM ' . $SQL->tableName('accounts') . ', ' . $SQL->tableName('players') . ', ' . $SQL->tableName('players_online') . ' WHERE ' . $SQL->tableName('players') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('players_online') . '.' . $SQL->fieldName('player_id') . ' AND ' . $SQL->tableName('accounts') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('players') . '.' . $SQL->fieldName('account_id') . ' ORDER BY ' . $SQL->fieldName($orderby))->fetchAll();
$number_of_players_online = 0;
$vocations_online_count = array(0,0,0,0,0); // change it if you got more then 5 vocations
$players_rows = '';
foreach($players_online_data as $player)
{
	$vocations_online_count[$player['vocation']] += 1;
	$bgcolor = (($number_of_players_online++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
	$skull = '';
	if ($player['skull'] == 4)
		$skull = "<img style='border: 0;' src='./images/skulls/redskull.gif'/>";
	else if ($player['skull'] == 5)
		$skull = "<img style='border: 0;' src='./images/skulls/blackskull.gif'/>";

	$players_rows .= '<TR BGCOLOR='.$bgcolor.'><TD WIDTH=5%><img src="' . $config['site']['outfit_images_url'] . '?id=' . $player['looktype'] . '&addons=' . $player['lookaddons'] . '&head=' . $player['lookhead'] . '&body=' . $player['lookbody'] . '&legs=' . $player['looklegs'] . '&feet=' . $player['lookfeet'] . '" alt="" /></td><TD WIDTH=65%><A HREF="?subtopic=characters&name='.urlencode($player['name']).'">'.htmlspecialchars($player['name']).$skull.'<img src="' . $config['site']['flag_images_url'] . $player['flag'] . $config['site']['flag_images_extension'] . '" title="Country: ' . $player['flag'] . '" alt="' . $player['flag'] . '" /></A></TD><TD WIDTH=10%>'.$player['level'].'</TD><TD WIDTH=20%>'.htmlspecialchars($vocation_name[$player['vocation']]).'</TD></TR>';
}		
if($config['site']['private-servlist.com_server_id'] > 0)
{
	$main_content.= '<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD WIDTH=10% CLASS=white><center><B>Players Online Chart</B></TD></TR></TABLE><table align="center"><tr><td><img src="http://private-servlist.com/server-chart/' . $config['site']['private-servlist.com_server_id'] . '.png" width="500px" /></td></tr></table>';
}
if($number_of_players_online == 0)
{
	//server status - server empty
	$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Who is Online?</h3></div><div class="panel-body">
	<p>The server is currently empty.</p></br>
	<a href="?view=account" class="btn btn-default">Back</a>
</div>
</div>';
}
else
{
	//server status - someone is online
	$main_content .= '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Who is Online?</h3></div><div class="panel-body">
	<p>There is currently <b>'.$number_of_players_online.'</b> ' . (($config['status']['serverStatus_players']  > 1) ? 'players' : 'player') . ' online.</p></br>
	<a href="?view=account" class="btn btn-default">Back</a>
	</div>
	</div>';
}