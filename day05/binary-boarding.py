import sys
import math

def binary_resolve(low, high, string):
    lower, upper = 0, 2**len(string) - 1
    for c in string:
        mid = lower + (upper - lower) // 2
        if c == low:
            upper = mid
        elif c == high:
            lower = mid + 1
        else:
            return None
    return lower

def resolve_seat(boarding_pass):
    row_pass = boarding_pass[:7]
    col_pass = boarding_pass[7:]
    return (
      binary_resolve('F', 'B', row_pass), 
      binary_resolve('L', 'R', col_pass)
    )

def seat_id(row, col):
    return row * 8 + col

def pass_id(boarding_pass):
    coords = resolve_seat(boarding_pass)
    return seat_id(coords[0], coords[1])

def solve_part_one(pass_ids):
    return max(pass_ids)

def solve_part_two(pass_ids):
    ids = sorted(pass_ids)
    for ind, seat_id in enumerate(ids):
        if ind > 0:
            if seat_id - ids[ind-1] > 1:
                return seat_id - 1
    return None

def main():
    with open('input.txt', 'r') as f:
        if f.mode == 'r':
            pass_ids = [
              pass_id(line.strip()) for line in f.readlines()
            ]
            print(solve_part_one(pass_ids))
            print(solve_part_two(pass_ids))

if __name__ == '__main__':
    main()
