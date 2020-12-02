import sys
import math

def solve_part_one(passwords):
    validcount = 0
    for line in passwords:
        minimum = int(line[0])
        maximum = int(line[1])
        letter = line[2]
        password = line[3]
        occ = password.count(letter)
        if int(minimum) <= occ and occ <= int(maximum):
            validcount += 1
    print(validcount)


def solve_part_two(passwords):
    validcount = 0
    for line in passwords:
        first = int(line[0]) - 1
        second = int(line[1]) - 1
        letter = line[2]
        password = line[3]
        if (password[first] == letter) != (password[second] == letter):
            validcount += 1
    print(validcount)

def main():
    with open('input.txt', 'r') as f:
        if f.mode == 'r':
            passwords = [
                line
                .strip()
                .replace('-', ' ', 1)
                .replace(':', '', 1)
                .split()
                for line in f.readlines()
            ]
            solve_part_one(passwords)
            solve_part_two(passwords)

if __name__ == '__main__':
    main()
