import sys
import math

def validate_byr(byr):
    return 1920 <= int(byr) <= 2002
def validate_iyr(iyr):
    return 2010 <= int(iyr) <= 2020
def validate_eyr(eyr):
    return 2020 <= int(eyr) <= 2030
def validate_hgt(hgt):
    val = hgt[:-2]
    if not val.isnumeric():
        return False
    unit = hgt[-2:]
    if unit == 'cm' and 150 <= int(val) <= 193:
        return True
    if unit == 'in' and 59 <= int(val) <= 76:
        return True
    return False
def validate_hcl(hcl):
    if len(hcl) != 7 or hcl[0] != '#':
        return False
    from string import hexdigits
    return all(c in hexdigits for c in hcl[1:])
def validate_ecl(ecl):
    return ecl in ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth']
def validate_pid(pid):
    return pid.isnumeric() and len(pid) == 9

required_fields = {
    'byr': validate_byr, 
    'iyr': validate_iyr, 
    'eyr': validate_eyr, 
    'hgt': validate_hgt, 
    'hcl': validate_hcl, 
    'ecl': validate_ecl, 
    'pid': validate_pid
}

all_fields = [field for field in required_fields] + ['cid']    

def solve_part_one(passports):
    def valid(passport):
        for field in required_fields:
            if field not in passport:
                return False
        return True
    return len([
        passport for passport in passports if valid(passport)
    ])

def solve_part_two(passports):
    def valid(passport):
        for field in required_fields:
            if field not in passport:
                return False
            val_index = passport.index(field) + 1
            if val_index == len(passport):
                return False
            if not required_fields[field](passport[val_index]):
                return False
        return True
    return len([
        passport for passport in passports if valid(passport)
    ])

def main():
    with open('input.txt', 'r') as f:
        if f.mode == 'r':
            passports = []
            passport = []
            for line in f.readlines():
                line = line.strip()
                if line == '':
                    passports.append(passport)
                    passport = []
                    continue
                for param in line.replace(':', ' ').split():
                    passport.append(param)
            if passport: passports.append(passport)

            print(solve_part_one(passports))
            print(solve_part_two(passports))

if __name__ == '__main__':
    main()
