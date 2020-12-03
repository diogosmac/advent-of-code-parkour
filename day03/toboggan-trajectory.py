import sys
import math

def solve_part_one(area_map, slope):
    height = len(area_map)
    width = len(area_map[0])
    pos = [0, 0]
    trees = 0
    while pos[0] < height:
        if area_map[pos[0]][pos[1] % width] == '#':
            trees += 1
        pos = [pos[0] + slope[0], pos[1] + slope[1]]
    return trees

def solve_part_two(area_map, slopes):
    return math.prod(
        [solve_part_one(area_map, slope) for slope in slopes]
    )

def main():
    with open('input.txt', 'r') as f:
        if f.mode == 'r':
            area_map = [ 
                line.strip()
                for line in f.readlines()
            ]
            print(solve_part_one(area_map, (1, 3)))
            slopes = [
                (1, 1),
                (1, 3),
                (1, 5),
                (1, 7),
                (2, 1)
            ]
            print(solve_part_two(area_map, slopes))

if __name__ == '__main__':
    main()
