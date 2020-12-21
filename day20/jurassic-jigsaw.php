#!/usr/bin/php
<?php

function last($array) {
    return end($array);
}

function cartesian($properties) {
    if (!$properties) {
        return [[]];
    }
    $current = array_shift($properties);
    $subset = cartesian($properties);

    $result = [];
    foreach ($current as $value) {
        foreach ($subset as $element) {
            array_unshift($element, $value);
            $result[] = $element;
        }
    }
    return $result;
}

class Tile {

    public $id;
    public $content;

    function __construct($id, $content) {
        $this->id = $id;
        $this->content = $content;
    }

    function flip_vertical() {
        $this->content = array_reverse($this->content);
    }

    function flip_horizontal() {
        $newcontent = [];
        foreach ($this->content as $row) {
            $newcontent[] = array_reverse($row);
        }
        $this->content = $newcontent;
    }

    function get_borders() {
        $top = current($this->content);
        $right = [];
        $bottom = last($this->content);
        $left = [];
        foreach ($this->content as $row) {
            $left[] = current($row);
            $right[] = last($row);
        }
        return [$top, $right, $bottom, $left];
    }

    function get_borders_with_syms() {
        $borders = $this->get_borders();
        $buff = $borders;
        foreach ($buff as $bor) {
            $borders[] = array_reverse($bor);
        }
        return $borders;
    }

    function rotate_clockwise() {
        $rowlen = count(current($this->content));
        $new = [];
        for ($i = 0; $i < $rowlen; $i++) {
            $newrow = [];
            foreach (array_reverse($this->content) as $row) {
                $newrow[] = $row[$i];
            }
            $new[] = $newrow;
        }
        $this->content = $new;
    }

    function complex_transform($param) {
        $newtile = new Tile($this->id, $this->content);
        if ($param[0]) {
            $newtile->flip_horizontal();
        }
        if ($param[1]) {
            $newtile->flip_vertical();
        }
        for ($i = 0; $i < $param[2]; $i++) {
            $newtile->rotate_clockwise();
        }
        return $newtile;
    }

    function trimmed() {
        $trim = [];
        for ($i = 1; $i < count($this->content) - 1; $i++) {
            $trimrow = [];
            for ($j = 1; $j < count($this->content[$i]) - 1; $j++) {
                $trimrow[] = $this->content[$i][$j];
            }
            $trim[] = $trimrow;
        }
        return $trim;
    }

    function flattened() {
        $s = "";
        foreach ($this->content as $row) {
            $r = implode($row);
            $s .= $r;
        }
        return $s;
    }

}

$values = explode("\r\n\r\n", file_get_contents('input.txt'));
$tiles = [];
foreach ($values as $input) {
    $lines = explode("\r\n", $input);
    if (count($lines) != 11) {
        continue;
    }
    $id = intval(
        explode(":", 
            explode("Tile ", current($lines))[1]
        )[0]
    );
    $content = [];
    for ($i = 1; $i < count($lines); $i++) {
        $content[] = str_split($lines[$i]);
    }
    $tiles[] = new Tile($id, $content);
}

function get_border_matches($tile, $tiles) {
    $others = [];
    foreach ($tiles as $t) {
        if ($t->id !== $tile->id) {
            $others[] = $t;
        }
    }
    $borders = $tile->get_borders();
    $result = [];
    foreach ($borders as $k => $v) {
        foreach ($others as $other) {
            foreach ($other->get_borders_with_syms() as $ob) {
                if ($v == $ob) {
                    $result[] = $k;
                    break;
                }
            }
        }
    }
    return $result;
}

function get_corners($tiles) {
    $tileborders = [];
    $checkedtiles = [];
    foreach ($tiles as $tile) {
        $tileborders[$tile->id] = get_border_matches($tile, $tiles);
        $checkedtiles[$tile->id] = $tile;
    }
    $corners = [];
    foreach ($tileborders as $id => $tb) {
        if (count($tb) == 2) {
            $tile = [$checkedtiles[$id], $tileborders[$id]];
            $corners[] = $tile;
        }
    }
    return $corners;
}

function solve_part_one() {
    global $tiles;
    $corners = get_corners($tiles);
    $values = [];
    foreach ($corners as $corner) {
        $values[] = $corner[0]->id;
    }
    return array_product($values);
}

$squareside = floor(sqrt(count($tiles)));

function compatible($current, $tile, $i) {
    global $squareside;
    $row = floor($i / $squareside);
    $col = $i % $squareside;
    $tileborders = $tile->get_borders();

    $left = ($col == 0) || 
            ($current[$i-1]->get_borders()[1] == $tileborders[3]);
    $top  = ($row == 0) || 
            ($current[$i - $squareside]->get_borders()[2] == current($tileborders));
    return $left && $top;
}

$tilemodifications = cartesian([
    [false, true],  // flip horizontally
    [false, true],  // flip vertically
    [0, 1, 2, 3]    // rotate n times (clockwise)
]);

function place_tiles($current, $used, $i) {
    global $tiles, $tilemodifications;
    $available = [];
    foreach ($tiles as $tile) {
        if (!in_array($tile->id, $used)) {
            $available[] = $tile;
        }
    }
    if (!$available) {
        return $current;
    }
    foreach ($available as $tile) {
        foreach ($tilemodifications as $mod) {
            $attempt = $tile->complex_transform($mod);
            if (compatible($current, $attempt, $i)) {
                $res = place_tiles(
                    [...$current, $attempt],
                    [...$used, $attempt->id],
                    $i + 1
                );
                if ($res !== null) {
                    return $res;
                }
            }
        }
    }
    return null;
}

function solve_part_two() {
    global $tiles, $squareside, $tilemodifications;
    $corners = get_corners($tiles);
    $startingcorner = current($corners);
    // commenting this because otherwise I will not
    // know wtf is going on if I ever come back here
    $startingtileoptions = [
        // top and right edges (rotate clockwise once)
        implode([0, 1]) => $startingcorner[0]->complex_transform([false, false, 1]),
        // right and bottom edges (no rotation needed)
        implode([1, 2]) => $startingcorner[0],
        // bottom and left edges (rotate clockwise three times because ofc)
        implode([2, 3]) => $startingcorner[0]->complex_transform([false, false, 3]),
        // top and left edges (rotate [clockwise] twice)
        implode([0, 3]) => $startingcorner[0]->complex_transform([false, false, 2]),
    ];
    // choose rotation depending on edges that match on this corner
    $starting = $startingtileoptions[implode($startingcorner[1])];
    // commenting spree is over, resuming regular level of messy
    $assembled = place_tiles([$starting], [$starting->id], 1);
    $trimmed = [];
    foreach ($assembled as $tile) {
        $trimmed[] = $tile->trimmed();
    }
    $bigcontent = [];
    for ($i = 0; $i < $squareside; $i++) {
        for ($j = 0; $j < count(current($trimmed)); $j++) {
            $bigrow = [];
            for ($k = $squareside * $i; $k < $squareside * ($i+1); $k++) {
                array_push($bigrow, ...$trimmed[$k][$j]);
            }
            $bigcontent[] = $bigrow;
        }
    }
    $big = new Tile(0, $bigcontent);
    $width = strlen(implode(current($big->content)));
    $wrap = $width - 18;
    $monsterregex = "/(?=(#.{" . intval($wrap-1) . "}#(.{4}##){3}#.{" . intval($wrap-1) . "}(#.{2}){6}))/";
    $monster_positions = [0, $wrap, 5, 1, 5, 1, 5, 1, 1, $wrap, 3, 3, 3, 3, 3];
    $res = 0;
    foreach ($tilemodifications as $mod) {
        $flat = $big->complex_transform($mod)->flattened();
        $monsters = [];
        $matches = preg_match_all($monsterregex, $flat, $monsters, PREG_OFFSET_CAPTURE);
        if ($matches > 0) {
            $pos = new \Ds\Set();
            foreach ($monsters[0] as $match) {
                $start = $match[1]; // get position from regex match
                for ($i = 1; $i <= count($monster_positions) + 1; $i++) {
                    $pos->add($start + array_sum(array_slice($monster_positions, 0, $i)));
                }
            }
            $res = substr_count($flat, "#") - count($pos);
            break;
        }
    }
    return $res;
}

echo solve_part_one() . PHP_EOL;
echo solve_part_two() . PHP_EOL;
