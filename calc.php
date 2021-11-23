<?php
require_once 'assets/back_functions/dump.php';
$post = [];
//Безопасим
foreach($_POST as $key => $value) {
    $post[$key] = htmlspecialchars($value);
}
$result = [];

//Валидация на бэке. Основная валидация была на фронте
foreach($post as $name => $value) {
    if($value === false && $name !== 'fill-per-month') {
        $result['STATUS'] = 'ERROR';
        $result['CODE'] = 500;
        echo json_encode($name);
        exit(0);
    }
}

//region Полезные переменные
$prevMonthSum = intval(str_replace(' ', '', $post['sum']));
$sumAdd = intval(str_replace(' ', '', $post['sumAdd']));
$percent = intval($post['percent']) / 100;

$dateStart = DateTime::createFromFormat('d.m.Y', $post['startDate']);
$dateStart->setTimezone(new DateTimeZone('Europe/Moscow'));
$daysY = $dateStart->format('L') ? 366 : 365;

$dateEnd = clone $dateStart;
$dateEnd->modify("+$post[term] " . $post['year-month']);

$interval = $dateStart->diff($dateEnd);
$fullMonthDiff = (intval($interval->format('%y') * 12)) + intval($interval->format('%m'));

//Количество дней оставшихся до конца нулевого месяца. (Тот месяц, в который был совершен вклад)
$daysN = intval($dateStart->format('t')) - intval($dateStart->format('d'));
//endregion


$monthN = clone $dateStart;
$monthCounter = $fullMonthDiff;
while($monthCounter > 0) {
    //Для не нулевого месяца берём переменную daysN и $daysY за циклом. Для последующих месяцев расчитываем
    if($monthCounter != $fullMonthDiff) {
        $monthN->modify("+1 month");
        $daysN = $monthN->format('t');
        //На случай, если год будет высокосным в этом расчёте
        $daysY = $monthN->format('L') ? 366 : 365;
    }
    $prevMonthSum += ($prevMonthSum + $sumAdd) * $daysN * ($percent / $daysY);
    $monthCounter--;
}

$res = intval(round($prevMonthSum));
$result['RESULT_1'] = $res;//round($prevMonthSum + ($prevMonthSum + $sumAdd) * $daysN * ($percent/$daysY));

//https://www.raiffeisen.ru/wiki/kak-rasschitat-procenty-po-vkladu/ Ежемесячная капитализация
//$result['RESULT_2'] = round((intval(str_replace(' ', '', $post['sum'])) + intval(str_replace(' ', '', $post['sum']))) * pow((1 + $percent / 100), $fullMonthDiff));
$result['CODE'] = 200;
$result['STATUS'] = 'OK';

echo json_encode($result);
