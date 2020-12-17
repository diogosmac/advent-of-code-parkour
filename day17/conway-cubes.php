#!/usr/bin/php
<?php
$values = explode("\r\n", file_get_contents('input.txt'));
$grid = new \Ds\Set();
for ($i = 0; $i < count($values); $i++) {
    $line = str_split($values[$i]);
    for ($j = 0; $j < count($line); $j++) {
        if ($line[$j] == '#') {
            $grid->add([$i, $j, 0, 0]);
        }
    }
}

function cartesian($input, $repeat) {
    $result = array(array());
    for ($i = 0; $i < $repeat; $i++) {
        $append = array();
        foreach($result as $product) {
            foreach($input as $item) {
                $new = $product;
                array_push($new, $item);
                $append[] = $new;
            }
        }
        $result = $append;
    }
    return $result;
}

function simulate($active, $size, $iterations) {
    $product = cartesian(range(-1, 1), $size);
    $positions = array();
    foreach ($product as $pos) {
        if ($size == 4) {
            if ($pos !== [0, 0, 0, 0]) {
                $positions[] = $pos;
            }
        } else {
            if ($pos !== [0, 0, 0]) {
                $positions[] = [...$pos, 0];
            }
        }
    }
    for ($i = 0; $i < $iterations; $i++) {
        $next = new \Ds\Set();
        $neighbors = new \Ds\Set();
        foreach ($active as $cube) {
            $count = 0;
            foreach ($positions as $n) {
                $pos = array();
                for ($j = 0; $j < 4; $j++) {
                    $pos[] = $cube[$j] + $n[$j];
                }
                if ($active->contains($pos)) {
                    $count++;
                } else {
                    $neighbors->add($pos);
                }
            }
            if ($count === 2 || $count === 3) {
                $next->add($cube);
            }
        }
        foreach ($neighbors as $cube) {
            $count = 0;
            foreach ($positions as $n) {
                $pos = array();
                for ($j = 0; $j < 4; $j++) {
                    $pos[] = $cube[$j] + $n[$j];
                }
                if ($active->contains($pos)) {
                    $count++;
                }
            }
            if ($count === 3) {
                $next->add($cube);
            }
        }
        $active = $next;
    }
    return count($active);
}

function solve_part_one() {
    global $grid;
    return simulate($grid, 3, 6);
}

function solve_part_two() {
    global $grid;
    return simulate($grid, 4, 6);
}

echo solve_part_one() . PHP_EOL;
echo solve_part_two() . PHP_EOL;
