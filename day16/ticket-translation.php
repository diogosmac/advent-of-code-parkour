<?php

$filename = "input.txt";

function read_input() {
    global $filename;
    $uncutsections = explode("\r\n\r\n", file_get_contents($filename));
    $sections = [];
    foreach ($uncutsections as $section) {
        $sections[] = explode("\r\n", $section);
    }
    $rules = new \Ds\Map();
    foreach ($sections[0] as $rule) {
        $rule = explode(": ", $rule);
        $intervals = explode(" or ", $rule[1]);
        $vals = [];
        foreach ($intervals as $interval) {
            array_push($vals, ...explode("-", $interval));
        }
        $values = [];
        foreach (range($vals[0], $vals[1]) as $num) {
            $values[] = $num;
        }
        foreach (range($vals[2], $vals[3]) as $num) {
            $values[] = $num;
        }
        $rules->put($rule[0], $values);
    }
    $myticket = array_map("intval", explode(",", $sections[1][1]));
    $nearby = [];
    foreach ($sections[2] as $ticket) {
        $nearby[] = array_map("intval", explode(",", $ticket));
    }
    $data = new \Ds\Map();
    $data->put("rules", $rules);
    $data->put("myticket", $myticket);
    $data->put("nearby", $nearby);
    return $data;
}

function check_rule($rule, $number) {
    foreach ($rule as $num) {
        if ($number == $num) {
            return true;
        }
    }
    return false;
}

function check_rules($rules, $number) {
    foreach ($rules as $rule => $values) {
        if (check_rule($values, $number)) {
            return true;
        }
    }
    return false;
}

function solve_part_one() {
    $data = read_input();
    $errorrate = 0;
    foreach ($data->get("nearby") as $ticket) {
        foreach ($ticket as $number) {
            if (!check_rules($data->get("rules"), $number)) {
                $errorrate += $number;
            }
        }
    }
    return $errorrate;
}


function valid($rules, $ticket) {
    foreach ($ticket as $val) {
        if (!check_rules($rules, $val)) {
            return false;
        }
    }
    return true;
}

function valid_tickets($data) {
    $valid = [$data->get("myticket")];
    foreach ($data->get("nearby") as $ticket) {
        if (valid($data->get("rules"), $ticket)) {
            $valid[] = $ticket;
        }
    }
    return $valid;
}

function possible_fields($rules, $tickets) {
    $fieldtable = new \Ds\Map();
    $size = count($rules);
    foreach ($rules as $rule => $values) {
        $possible = [];
        for ($i = 0; $i < $size; $i++) {
            $valid = true;
            foreach ($tickets as $ticket) {
                if (!check_rule($values, $ticket[$i])) {
                    $valid = false;
                    break;
                }
            }
            if ($valid) {
                $possible[] = $i;
            }
        }
        $fieldtable->put($rule, $possible);
    }
    return $fieldtable;
}

function figure_it_out($possible_positions) {
    $positions = new \Ds\Map();
    $keys = $possible_positions->keys();
    while (!$keys->isEmpty()) {
        foreach ($keys as $key) {
            if (count($possible_positions->get($key)) == 1) {
                $val = current($possible_positions->get($key));
                $positions->put($key, $val);
                $keys->remove($key);
                foreach ($keys as $pos) {
                    $list = $possible_positions->get($pos);
                    if (($index = array_search($val, $list)) !== false) {
                        unset($list[$index]);
                        $possible_positions->put($pos, $list);
                    }
                }
                continue;
            }
        }
    }
    return $positions;
}

function solve_part_two() {
    $data = read_input();
    $validtickets = valid_tickets($data);
    $possible = possible_fields($data->get("rules"), $validtickets);
    $positions = figure_it_out($possible);
    $result = 1;
    foreach ($positions as $rule => $value) {
        if (strpos($rule, "departure") !== false) {
            $result *= $data->get("myticket")[$value];
        }
    }
    return $result;
}


echo solve_part_one() . PHP_EOL;
echo solve_part_two() . PHP_EOL;
