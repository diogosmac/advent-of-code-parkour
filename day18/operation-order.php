#!/usr/bin/php
<?php
$values = explode("\r\n", file_get_contents('input.txt'));

function evaluate_expression($expression) {
    $op = null;
    $num = 0;
    $res = 0;
    for ($i = 0; $i < strlen($expression); $i++) {
        if (is_numeric($expression[$i])) {
            $endpos = strpos($expression, " ", $i);
            if ($endpos === false) {
                $num = intval(substr($expression, $i));
                $i = strlen($expression);
            } else {
                $num = intval(substr($expression, $i, $endpos - $i));
                $i = $endpos;
            }
        } else if ($expression[$i] == "(") {
            $numpar = 1;
            $ptr = $i + 1;
            do {
                $openpos = strpos($expression, "(", $ptr);
                $endpos = strpos($expression, ")", $ptr);
                if ($openpos === false) {
                    $numpar--;
                    $ptr = $endpos + 1;
                } else if ($openpos > $endpos) {
                    $numpar--;
                    $ptr = $endpos + 1;
                } else {
                    $numpar++;
                    $ptr = $openpos + 1;
                }
            } while ($numpar > 0);
            $exp = substr($expression, $i + 1, $endpos - $i - 1);
            $num = evaluate_expression($exp);
            $i = $endpos + 1;
        } else {
            $op = $expression[$i];
            $i++;
            continue;
        }
        switch ($op) {
            case null:
                $res = $num;
                break;
            case "*":
                $res *= $num;
                break;
            case "+":
                $res += $num;
                break;
        }
    }
    return $res;
}

function solve_part_one() {
    global $values;
    $results = [];
    foreach ($values as $exp) {
        $results[] = evaluate_expression($exp);
    }
    return array_sum($results);
}

function sum_start($expression, $ind) {
    $parents = 0;
    for ($i = $ind; $i >= 0; $i--) {
        $c = $expression[$i];
        switch ($c) {
            case " ":
                break;
            case "*":
                break;
            case "+":
                break;
            case ")":
                $parents++;
                break;
            case "(":
                $parents--;
            default:
                if ($parents == 0) {
                    return $i;
                }
        }
    }
    return -1;
}

function sum_end($expression, $ind) {
    $parents = 0;
    for ($i = $ind; $i < strlen($expression); $i++) {
        $c = $expression[$i];
        switch ($c) {
            case " ":
                break;
            case "*":
                break;
            case "+":
                break;
            case "(":
                $parents++;
                break;
            case ")":
                $parents--;
            default:
                if ($parents == 0) {
                    return $i;
                }
        }
    }
    return -1;
}

function transform($expression) {
    $res = "";
    $chars = str_split($expression);
    $open = [];
    $close = [];
    foreach ($chars as $i => $c) {
        if ($c == "+") {
            $open[] = sum_start($expression, $i);
            $close[] = sum_end($expression, $i);
        }
    }
    foreach ($chars as $i => $c) {
        if (array_search($i, $open) !== false) {
            $res .= "(";
        }
        $res .= $c;
        if (array_search($i, $close) !== false) {
            $res .= ")";
        }
    }
    return $res;
}

function solve_part_two() {
    global $values;
    $results = [];
    foreach ($values as $exp) {
        $results[] = evaluate_expression(transform($exp));
    }
    return array_sum($results);
}

echo solve_part_one() . PHP_EOL;
echo solve_part_two() . PHP_EOL;
