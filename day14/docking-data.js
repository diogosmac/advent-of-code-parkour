const fs = require("fs");

let values = fs.readFileSync("input.txt", "utf8").split("\r\n");

let progs = []
let prog = null
for (let val of values) {
    let v = val.split(' =')[0]
    if (v == 'mask') {
        if (prog != null) {
            progs.push(prog)
        }
        prog = [val]
    } else {
        prog.push(val)
    }
}
progs.push(prog)

function solve_part_one() {
    let adds = {}
    for (let prog of progs) {
        let mask = prog[0].split(' = ')[1]
        let orMask = BigInt(parseInt(mask.replace(/X/g, '0'), 2))
        let andMask = BigInt(parseInt(mask.replace(/X/g, '1'), 2))
        for (let i = 1; i < prog.length; i++) {
            let op = prog[i].split(' = ')
            let add = op[0].split('[')[1].split(']')[0]
            let val = +op[1]
            adds[add] = {
                'orMask': orMask,
                'andMask': andMask,
                'val': BigInt(val),
            }
        }
    }
    let sum = 0
    for (let i in adds) {
        let val = (adds[i].val | adds[i].orMask) & adds[i].andMask
        sum += Number(val)
    }
    return Number(sum)
}

function set_str_char(s, i, c) {
    return s.substring(0, i) + c + s.substring(i+1)
}

function dec_to_bin(dec) {
    return (dec >>> 0).toString(2).padStart(36, '0')
}

function solve_part_two() {
    let adds = {}
    for (let prog of progs) {
        let mask = prog[0].split(' = ')[1]
        for (let j = 1; j < prog.length; j++) {
            let op = prog[j].split(' = ')
            let v = op[0]
            let arg = +op[1]
            let [, add] = v.match(/^mem\[(\d+)]$/);
            add = [dec_to_bin(+add)]
            for (let i = 0; i < mask.length; i++) {
                if (mask[i] == '0') {
                    continue
                }
                if (mask[i] == '1') {
                    add = add.map((a) => set_str_char(a, i, '1'))
                    continue
                }
                add = [
                    ...add.map((a) => set_str_char(a, i, '0')),
                    ...add.map((a) => set_str_char(a, i, '1'))
                ]
            }
            for (let a of add) {
                adds[parseInt(a, 2)] = arg
            }
        }
    }
    let sum = 0
    for (let i in adds) {
        sum += adds[i]
    }
    return sum
}

console.log(solve_part_one())
console.log(solve_part_two())
