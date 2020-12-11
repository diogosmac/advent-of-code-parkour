# run with 'py template.py'

import sys
import math

def solve_part_one(values):
    return 0

def solve_part_two(values):
    return 0

def main():
    with open('input.txt', 'r') as f:
        if f.mode == 'r':
            values = [line.strip() for line in f.readlines()]
            print(solve_part_one(values))
            print(solve_part_two(values))

if __name__ == '__main__':
    main()
