<?php
declare(strict_types=1);
date_default_timezone_set("Europe/Kaliningrad");

const SECONDS_IN_DAY = 86400;

const MONTHS_GEN = [
    1 => "января", 2 => "февраля", 3 => "марта", 4 => "апреля",
    5 => "мая", 6 => "июня", 7 => "июля", 8 => "августа",
    9 => "сентября", 10 => "октября", 11 => "ноября", 12 => "декабря",
];

const WEEKDAYS = [
    1 => "понедельник", 2 => "вторник", 3 => "среда", 4 => "четверг",
    5 => "пятница", 6 => "суббота", 7 => "воскресенье",
];

                                                                                // часть 1

echo "Часть 1. Timestamp и функция date() <br><br>";

// 1 задание
echo "Задание 1 <br>";
$now = time();
echo $now . "<br>";
echo date("Y-m-d H:i:s", $now) . "<br>";
echo date("d.m.Y H:i:s", 0) . "<br>";
echo date("d.m.Y H:i:s", 1000000000) . "<br>";
// 0 это начало unix эпохи, 1 января 1970 года 00:00:00 по UTC
// timestamp это сколько секунд прошло с этого момента
// в моем поясе показывает 03:00, потому что в 1970 году в Калининграде было UTC+3, сейчас UTC+2
echo "<br>";

// 2 задание
echo "Задание 2 <br>";
echo date("d.m.Y") . "<br>";
echo date("Y-m-d H:i:s") . "<br>";

$weekday = (int)date("N");
$day = (int)date("j");
$month = (int)date("n");
echo WEEKDAYS[$weekday] . ", " . $day . " " . MONTHS_GEN[$month] . " " . date("Y") . "<br>";

echo "неделя по ISO: " . date("W") . ", день в году: " . date("z") . "<br>";

echo "дней в месяце: " . date("t") . "<br>";
if (date("L") === "1"){
    echo "год високосный <br>";
} else {
    echo "год не високосный <br>";
}
echo "<br>";

// 3 задание
echo "Задание 3 <br>";
// буквы надо экранировать обратным слешем, иначе php примет их за коды формата
// строку беру в одинарные кавычки, потому что в двойных \r это перенос строки
echo date('\Y\e\a\r: Y, \W\e\e\k: W') . "<br>";
echo "<br>";

// 4 задание
echo "Задание 4 <br>";
$tomorrow = time() + SECONDS_IN_DAY;
$weekAgo = time() - 7 * SECONDS_IN_DAY;
echo "через сутки: " . date("d.m.Y H:i:s", $tomorrow) . "<br>";
echo "неделю назад: " . date("d.m.Y H:i:s", $weekAgo) . "<br>";

$yearStart = mktime(0, 0, 0, 1, 1, (int)date("Y"));
$daysPassed = intdiv(time() - $yearStart, SECONDS_IN_DAY);
echo "с начала года прошло целых суток: " . $daysPassed . "<br>";
echo "<br>";

                                                                                // часть 2

echo "Часть 2. Часовые пояса <br><br>";

echo "текущий пояс: " . date_default_timezone_get() . "<br>";
// совпадает с тем что я поставил вверху

$ts = mktime(12, 0, 0, 11, 15, 2026);
$zones = ["UTC", "Europe/London", "Europe/Moscow", "America/New_York", "Asia/Tokyo"];

foreach ($zones as $zone){
    date_default_timezone_set($zone);
    echo $zone . ": " . date("d.m.Y H:i T", $ts) . " (ts = " . $ts . ")<br>";
}
// timestamp не меняется при смене пояса, это один и тот же момент времени
// меняется только то как он показывается, то есть часы и название пояса

date_default_timezone_set("UTC");
$tsUtc = mktime(12, 0, 0, 11, 15, 2026);
echo "<br>ts в UTC: " . $tsUtc . ", ts в моем поясе: " . $ts . "<br>";
echo "разница: " . ($ts - $tsUtc) . " сек <br>";
// число другое потому что mktime собирает время в текущем поясе
// 12:00 в Калининграде это раньше чем 12:00 по UTC, на 2 часа (7200 сек)

date_default_timezone_set("Europe/Kaliningrad");
echo "<br>";

                                                                                // часть 3

echo "Часть 3. mktime, strtotime, checkdate <br><br>";

// 1 задание
echo "Задание 1 <br>";
$sept = mktime(9, 0, 0, 9, 1, 2027);
$lastSecond = mktime(23, 59, 59, 12, 31, 2026);
echo date("d.m.Y H:i:s", $sept) . "<br>";
echo date("d.m.Y H:i:s", $lastSecond) . "<br>";
echo "<br>";

// 2 задание
echo "Задание 2 <br>";
echo date("d.m.Y", mktime(0, 0, 0, 13, 1, 2026)) . "<br>";
echo date("d.m.Y", mktime(0, 0, 0, 2, 30, 2026)) . "<br>";
// 13 месяца нет, поэтому php перенес лишний месяц на следующий год, получилось 01.01.2027
// 30 февраля тоже нет, в феврале 28 дней, лишние 2 дня ушли в март, получилось 02.03.2026
echo "<br>";

// 3 задание
echo "Задание 3 <br>";
function isRealDate(int $day, int $month, int $year): bool {
    return checkdate($month, $day, $year);
}

$checks = [
    [29, 2, 2028],
    [29, 2, 2026],
    [31, 4, 2026],
    [31, 12, 2026],
    [0, 1, 2026],
    [15, 13, 2026],
];

foreach ($checks as $c){
    echo $c[0] . "." . $c[1] . "." . $c[2] . ": ";
    var_dump(isRealDate($c[0], $c[1], $c[2]));
    echo "<br>";
}
echo "<br>";

// 4 задание
echo "Задание 4 <br>";
$phrases = ["tomorrow", "yesterday", "+10 days", "next friday", "last monday", "first day of next month", "last day of this month"];
foreach ($phrases as $phrase){
    echo $phrase . " → " . date("d.m.Y", strtotime($phrase)) . "<br>";
}

echo "<br> от 2026-12-30: <br>";
$base = strtotime("2026-12-30");
$phrases2 = ["+3 days", "+1 week 2 days", "monday next week"];
foreach ($phrases2 as $phrase){
    echo $phrase . " → " . date("d.m.Y", strtotime($phrase, $base)) . "<br>";
}
echo "<br>";

// 5 задание
echo "Задание 5 <br>";
$formats = ["03/04/2026", "03-04-2026", "03.04.2026", "2026-04-03"];
foreach ($formats as $f){
    echo $f . " → " . date("Y-m-d", strtotime($f)) . "<br>";
}
// "03/04/2026" php прочитал как американский формат мес/день, то есть 4 марта
// остальные три прочитал как 3 апреля (день-месяц-год и год-месяц-день)
echo "<br>";

// 6 задание
echo "Задание 6 <br>";
function safeDate(string $text, string $format = "d.m.Y"): string {
    $ts = strtotime($text);
    if ($ts === false){
        return "Не удалось распознать дату";
    }
    return date($format, $ts);
}

$texts = ["2026-10-27", "tomorrow", "завтра", "", "32.13.2026"];
foreach ($texts as $text){
    echo "\"" . $text . "\" → " . safeDate($text) . "<br>";
}
echo "<br>";

                                                                                // часть 4

echo "Часть 4. DateTime и DateTimeImmutable <br><br>";

// 1 задание
echo "Задание 1 <br>";
$d1 = new DateTimeImmutable("now");
$d2 = new DateTimeImmutable("2026-10-27 14:30");
$d3 = new DateTimeImmutable("tomorrow");

echo $d1->format("d.m.Y H:i") . " | " . $d1->getTimestamp() . "<br>";
echo $d2->format("d.m.Y H:i") . " | " . $d2->getTimestamp() . "<br>";
echo $d3->format("d.m.Y H:i") . " | " . $d3->getTimestamp() . "<br>";
echo "<br>";

// 2 задание
echo "Задание 2 <br>";
$a = new DateTimeImmutable("2026-10-27");
$b = new DateTimeImmutable("2026-10-27 00:00:00");
$c = new DateTimeImmutable("2026-12-31");

var_dump($a == $b);
echo "<br>";
var_dump($a < $b);
echo "<br>";
var_dump($a <=> $b);
echo "<br>";
// первая пара равна потому что без времени php ставит 00:00:00, это один и тот же момент
// поэтому == тру, < фолз, а <=> дает 0

var_dump($a == $c);
echo "<br>";
var_dump($a < $c);
echo "<br>";
var_dump($a <=> $c);
echo "<br><br>";

// 3 задание
echo "Задание 3 <br>";
$date = new DateTimeImmutable("2026-10-27 14:30:45");
$dayStart = $date->setTime(0, 0, 0);
$dayEnd = $date->setTime(23, 59, 59);
$newYear = $date->setDate(2027, 1, 15);

echo "начало дня: " . $dayStart->format("d.m.Y H:i:s") . "<br>";
echo "конец дня: " . $dayEnd->format("d.m.Y H:i:s") . "<br>";
echo "15 января 2027: " . $newYear->format("d.m.Y H:i:s") . "<br>";
echo "<br>";

                                                                                // часть 5

echo "Часть 5. Арифметика дат <br><br>";

// 1 задание
echo "Задание 1 <br>";
$start = new DateTimeImmutable("2026-10-27");
$modifies = ["+1 day", "-2 weeks", "next friday", "first day of next month", "last day of this month", "last day of previous month"];
foreach ($modifies as $m){
    echo $m . " → " . $start->modify($m)->format("d.m.Y") . "<br>";
}
echo "<br>";

// 2 задание
echo "Задание 2 <br>";
$dates = ["2026-01-31", "2026-03-31", "2026-01-30"];
foreach ($dates as $dateStr){
    $d = new DateTimeImmutable($dateStr);
    echo $dateStr . ": +1 month → " . $d->modify("+1 month")->format("d.m.Y");
    echo ", last day of next month → " . $d->modify("last day of next month")->format("d.m.Y") . "<br>";
}
// +1 month просто прибавляет 1 к номеру месяца, а день оставляет тот же
// если такого дня в новом месяце нет (31 февраля), то лишние дни переходят в следующий месяц
// поэтому 31.01 + 1 месяц это не конец февраля, а 03.03
// "last day of next month" всегда дает последний день след. месяца, переполнения нет
// неожиданно ведет себя когда день 29, 30 или 31 а в следующем месяце дней меньше
echo "<br>";

// 3 задание
echo "Задание 3 <br>";
$moment = new DateTimeImmutable("2026-10-27 12:00");
$intervals = ["P1D", "P2W", "P3M", "P1Y2M10DT2H30M", "PT90M", "PT36H"];
foreach ($intervals as $i){
    $result = $moment->add(new DateInterval($i));
    echo $i . " → " . $result->format("d.m.Y H:i") . "<br>";
}
// M до T это месяцы, а M после T это минуты
// T разделяет дату и время, P1M это один месяц, а PT1M это одна минута
echo "<br>";

// 4 задание
echo "Задание 4 <br>";
$march = new DateTimeImmutable("2026-03-10");
echo "минус неделя: " . $march->sub(new DateInterval("P1W"))->format("d.m.Y") . "<br>";
echo "минус 1 месяц 15 дней: " . $march->sub(new DateInterval("P1M15D"))->format("d.m.Y") . "<br>";
echo "<br>";

// 5 задание
echo "Задание 5 <br>";
function addBusinessDays(DateTimeImmutable $start, int $days): DateTimeImmutable {
    $current = $start;
    $added = 0;
    while ($added < $days){
        $current = $current->modify("+1 day");
        // 6 и 7 это суббота и воскресенье, их пропускаем
        if ((int)$current->format("N") >= 6){
            continue;
        }
        $added++;
    }
    return $current;
}

$friday = new DateTimeImmutable("2026-10-23");
$wednesday = new DateTimeImmutable("2026-10-21");

echo "пятница +1: " . addBusinessDays($friday, 1)->format("d.m.Y, l") . "<br>";
echo "среда +5: " . addBusinessDays($wednesday, 5)->format("d.m.Y, l") . "<br>";
echo "среда +0: " . addBusinessDays($wednesday, 0)->format("d.m.Y, l") . "<br>";
echo "<br>";

// 6 задание
echo "Задание 6 <br>";
function startOfMonth(DateTimeImmutable $d): DateTimeImmutable {
    return $d->modify("first day of this month")->setTime(0, 0, 0);
}

function endOfMonth(DateTimeImmutable $d): DateTimeImmutable {
    return $d->modify("last day of this month")->setTime(23, 59, 59);
}

$feb2026 = new DateTimeImmutable("2026-02-14");
$feb2028 = new DateTimeImmutable("2028-02-14");

echo "2026: " . startOfMonth($feb2026)->format("d.m.Y H:i:s") . " - " . endOfMonth($feb2026)->format("d.m.Y H:i:s") . "<br>";
echo "2028: " . startOfMonth($feb2028)->format("d.m.Y H:i:s") . " - " . endOfMonth($feb2028)->format("d.m.Y H:i:s") . "<br>";
// в 2028 году високосный, поэтому в феврале 29 дней
