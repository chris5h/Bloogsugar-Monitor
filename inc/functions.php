<?php
function get_time_difference($time1, $time2)
{
	$time1 = strtotime("1/1/1980 $time1");
	$time2 = strtotime("1/1/1980 $time2");

if ($time2 < $time1)
{
	$time2 = $time2 + 86400;
}

return $hours =round(($time2 - $time1) / 3600, 2);

}

function logLevel($date, $time, $level){
global $link;
$sql = 
"insert into levels_log
	(testdate, testtime, sugarlevel)
VALUES
	(?,?,?)";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ssi", $date, $time, $level);
mysqli_stmt_execute($stmt);
$_SESSION['last'] =  $num;
header("HTTP/1.1 303 See Other");
header("Location: https://sugar.thehallclan.net/");
}

function updateLevel($id, $date, $time, $level){
global $link;
$sql = "update levels_log set testdate=?, testtime=?, sugarlevel=? where id=?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ssii", $date, $time, $level, $id);
mysqli_stmt_execute($stmt);
$_SESSION['last'] =  $num;
header("HTTP/1.1 303 See Other");
header("Location: https://sugar.thehallclan.net/history.php");
}

function delTest($id){
    global $link;
	$sql = "delete from levels_log where id = ?";
	$stmt = mysqli_prepare($link, $sql);
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	header("HTTP/1.1 303 See Other");
	header("Location: https://sugar.thehallclan.net/history.php");	
}

function LastTest(){
global $link;
$sql = 
"select testdate, testtime, sugarlevel from  levels_log
	order by testdate desc, testtime desc
	limit 1 ";
if($stmt = mysqli_prepare($link, $sql)){
	if(mysqli_stmt_execute($stmt)){
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			return $row;
		}
	}
}

}

function Stats($day){
global $link;
$sql = 
"select min(sugarlevel) Low, max(sugarlevel) High, cast(avg(sugarlevel) as int) Avg from levels_log l
	where l.testdate BETWEEN DATE_SUB(cast(NOW() as date),INTERVAL $day DAY) AND DATE_SUB(cast(NOW() as date),INTERVAL 1 DAY)
";

if($stmt = mysqli_prepare($link, $sql)){
	if(mysqli_stmt_execute($stmt)){
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$lead['Low'] = $row['Low'];
			$lead['High'] = $row['High'];
			$lead['Avg'] = $row['Avg'];
		}
	}
}
$sql = "CALL FastingStats($day)";
if($stmt = mysqli_prepare($link, $sql)){
	if(mysqli_stmt_execute($stmt)){
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$lead['FastingLow'] = $row['Low'];
			$lead['FastingHigh'] = $row['High'];
			$lead['FastingAvg'] = $row['Avg'];
		}
	}
}
return $lead;
}

function lastTen(){
global $link;
$last = 'FIRST';
$a = ARRAY();
$sql =
"select * from (
select * from levels_log l
	order by testdate desc, testtime desc 
	limit 10
	) AS T
	order by testdate, testtime";
if($stmt = mysqli_prepare($link, $sql)){
	if(mysqli_stmt_execute($stmt)){
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$x = $row;
			if ($last !== 'FIRST'){
				if ($row['sugarlevel'] > $last){
					$change = 'up';
				}	else if ($row['sugarlevel'] < $last){
					$change = 'down';
				}	else if ($row['sugarlevel'] == $last) {
					$change = 'same';
				}
			}	else	{
				$change = '';
			}
			$x['change'] = $change;
			array_push($a,$x);
			$last = $row['sugarlevel'];
		}
	}
}
return $a;
}

function History($link, $start, $end){
$last = 'FIRST';
$a = ARRAY();
if  ($start == 'all'){
$sql =
"select * from levels_log l
	order by testdate, testtime";
}	else	{
$sql =
"select * from levels_log l
	where l.testdate BETWEEN CAST(? AS DATE) AND CAST(? AS DATE)
	order by testdate, testtime";
}
if($stmt = mysqli_prepare($link, $sql)){
	if ($start !== 'all'){	mysqli_stmt_bind_param($stmt, "ss", $start, $end);}
	if(mysqli_stmt_execute($stmt)){
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$x = $row;
			if ($last !== 'FIRST'){
				if ($row['sugarlevel'] > $last){
					$change = 'up';
				}	else if ($row['sugarlevel'] < $last){
					$change = 'down';
				}	else if ($row['sugarlevel'] == $last) {
					$change = 'same';
				}
			}	else	{
				$change = '';
			}
			$x['change'] = $change;
			array_push($a,$x);
			$last = $row['sugarlevel'];
		}
	}
}
return $a;
}

function ChartTable($height,$width){
global $link;
$sql = "CALL SixWeekStats()";
$datay1 =  $datay2 = $datay3 = $titles = array();
if($stmt = mysqli_prepare($link, $sql)){
	if(mysqli_stmt_execute($stmt)){
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			array_push($datay1,$row['Total']);
			array_push($datay2,$row['Fasting']);
			array_push($datay3,$row['Night']);
			array_push($titles,$row['Week']);
		}
	}
}

require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');
// Setup the graph
$graph = new Graph($height,$width);
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;

$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(true);
$graph->title->Set('Fasting Levels');
$graph->SetBox(true);

$graph->SetMargin(40,40,40,40);

$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($titles);
$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new LinePlot($datay1);
$graph->Add($p1);
$p1->SetColor("#FF0000");
$p1->SetLegend('Total');

// Create the second line
$p2 = new LinePlot($datay2);
$graph->Add($p2);
$p2->SetColor("#33FF33");
$p2->SetLegend('Fasting');

// Create the third line
$p3 = new LinePlot($datay3);
$graph->Add($p3);
$p3->SetColor("#3333FF");
$p3->SetLegend('Night');

$graph->legend->SetFrameWeight(0);

// Output line
$graph->Stroke();
}
?>