import sys
import math

def solve_part_one(expenses):
    for e in expenses:
        if 2020 - e in expenses:
            print('Part One:', e * (2020 - e))
            return

def solve_part_two(expenses):
    for e in expenses:
        r = 2020 - e
        for e2 in expenses:
            if e == e2:
                pass
            if r - e2 in expenses:
                print('Part Two:', e * e2 * (r - e2))
                return

def main():
    with open('input.txt', 'r') as f:
        if f.mode == 'r':
            expenses = [int(line.strip()) for line in f.readlines()]
            solve_part_one(expenses)
            solve_part_two(expenses)

if __name__ == '__main__':
    main()
