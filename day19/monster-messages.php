#!/usr/bin/php
<?php
$sections = explode("\r\n\r\n", file_get_contents('input.txt'));
$rules = explode("\r\n", $sections[0]);
$messages = explode("\r\n", $sections[1]);

$ruleset = [];

foreach ($rules as $rule) {
    [$id, $content] = explode(": ", $rule);
    if (!array_key_exists(intval($id), $ruleset)) {
        $ruleset[intval($id)] = [];
    }
    foreach (explode(" | ", $content) as $segment) {
        if ($segment[0] == "\"") {
            $endpos = strpos($segment, "\"", 1);
            $val = substr($segment, 1, $endpos - 1);
            $ruleset[intval($id)][] = $val;
            continue;
        }
        $parts = [];
        foreach (explode(" ", $segment) as $part) {
            $parts[] = intval($part);
        }
        $ruleset[intval($id)][] = $parts;
    }
}

function check($line, $rule) {
    global $ruleset;
    if (count($rule) == 0) {
        return strlen($line) == 0;
    }
    $inner = $ruleset[array_shift($rule)];
    if (current($inner) === "a" || current($inner) === "b") {
        return 
            strpos($line, current($inner)) === 0 && 
            check(substr($line, 1), $rule);
    } else {
        foreach ($inner as $r) {
            if (check($line, [...$r, ...$rule])) {
                return true;
            }
        }
        return false;
    }
}

function solve_part_one() {
    global $messages;
    $count = 0;
    foreach ($messages as $message) {
        if (check($message, [0])) {
            $count++;
        }
    }
    return $count;
}

function solve_part_two() {
    global $messages, $ruleset;
    $ruleset[8] = [[42], [42, 8]];
    $ruleset[11] = [[42, 31], [42, 11, 31]];
    $count = 0;
    foreach ($messages as $message) {
        if (check($message, [0])) {
            $count++;
        }
    }
    return $count;
}

echo solve_part_one() . PHP_EOL;
echo solve_part_two() . PHP_EOL;
