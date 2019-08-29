<?php

/**
 * Wochentagsberechnung nach https://de.wikipedia.org/wiki/Wochentagsberechnung
 */

/**
 * @param int $w
 * @return mixed
 */
function getWeekdayName(int $w)
{
    $weekDayNames = [
        "Sonntag",
        "Montag",
        "Diensttag",
        "Mittwoch",
        "Donnerstag",
        "Freitag",
        "Samstag",
    ];
    $weekday = $weekDayNames[$w];
    return $weekday;
}

/**
 * @param $day
 * @param $month
 * @param $year
 */
function printEingabe($day, $month, $year): void
{
    echo "Eingabe: {$day}.{$month}.{$year}\n";
}

/**
 * @param $year
 * @param $month
 * @param $day
 */
function printAusgabe($year, $month, $day): void
{
    echo strftime("Berechnung PHP: Wochentag='%A'\n", strtotime("$year-$month-$day"));
}

/**
 * @param $weekday
 * @param int $m
 * @param $y
 * @param $c
 */
function printDebugOutput($weekday, int $m, $y, $c): void
{
    global $argc;
    global $argv;

    echo "Berechnung Algorithmus: Wochentag='{$weekday}'\n";
    if ($argc > 4 && ($argv[4] == '-d' || $argv[4] == '--debug')) {
        echo "DEBUG: m={$m} y={$y} c={$c}\n";
    }
}

/**
 * @param int $argc
 * @param array $argv
 * @return array
 */
function handleCommandLine(int $argc, array $argv): array
{
    $day = $argv[1];
    $month = $argv[2];
    $year = $argv[3]; /* muss vierstellig sein */

    if ($argc < 4 || $argc > 5) {
        echo "Wrong number of arguments.";
        exit(1);
    }
    return [$day, $month, $year];
}

function main(int $argc, array $argv): void
{
    setlocale(LC_TIME, 'de_AT.utf-8');

    list($day, $month, $year) = handleCommandLine($argc, $argv);

    $m = (($month - 2 - 1) + 12) % 12 + 1; // this is because of the modulo

    if ($m >= 11) {
        $c = substr($year - 1, 0, 2);
        $y = substr($year - 1, 2, 2);
    } else {
        $c = substr($year, 0, 2);
        $y = substr($year, 2, 2);
    }

    $weekdayNumber = ($day + intval(2.6 * $m - 0.2) + $y + intval($y / 4) + intval($c / 4) - 2 * $c) % 7;

    $weekday = getWeekdayName($weekdayNumber);

    printEingabe($day, $month, $year);
    printAusgabe($year, $month, $day);
    printDebugOutput($weekday, $m, $y, $c);
}

main($argc, $argv);

