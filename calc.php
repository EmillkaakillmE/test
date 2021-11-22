<?php
function dump($arr, $var = false, $die = false)
{
    $bt = debug_backtrace();
    $bt = $bt[0];
    $dRoot = $_SERVER["DOCUMENT_ROOT"];
    $dRoot = str_replace("/", "\\", $dRoot);
    $bt["file"] = str_replace($dRoot, "", $bt["file"]);
    $dRoot = str_replace("\\", "/", $dRoot);
    $bt["file"] = str_replace($dRoot, "", $bt["file"]);
    ?>
	<div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000;'>
		<div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?= $bt["file"] ?> [<?= $bt["line"] ?>
			]
		</div>
		<pre style='padding:10px;'><? $var ? var_dump($arr) : print_r($arr); ?></pre>
	</div>
    <?
    if($die) {
        die;
    }
}

$post = [];
//Безопасим
foreach($_POST as $key => $value) {
    $post[$key] = htmlspecialchars($value);
}
$result = [];

//Валидация на бэке. Основная валидация была на фронте
foreach($post as $name => $value) {
    if(empty($value) || $value == false) {
        $result['STATUS'] = 'ERROR';
        $result['CODE'] = 500;
        echo json_encode($post);
        exit(0);
    }
}

//region Полезные переменные
$prevMonthSum = intval($post['sum']);
$sumAdd = intval($post['sumAdd']);
$percent = intval($post['percent']);

$dateStart = DateTime::createFromFormat('d.m.Y', $post['startDate']);
$dateStart->setTimezone(new DateTimeZone('Europe/Moscow'));
$daysY = $dateStart->format('L') ? 366 : 365;

$dateEnd = clone $dateStart;
$dateEnd->modify("+$post[term] ".$post['year-month']);

$interval = $dateStart->diff($dateEnd);
$fullMonthDiff = (intval($interval->format('%y') * 12)) + intval($interval->format('%m'));

$daysN = intval($dateStart->format('t'));
//endregion


$result['RESULT_1'] = round($prevMonthSum + ($prevMonthSum + $sumAdd) * $daysN * ($percent/$daysY));

//https://www.raiffeisen.ru/wiki/kak-rasschitat-procenty-po-vkladu/ Ежемесячная капитализация
$result['RESULT_2'] = round(($prevMonthSum + $sumAdd) * pow((1 + $percent/100), $interval->days));
$result['CODE'] = 200;
$result['STATUS'] = 'OK';
echo json_encode($result);
