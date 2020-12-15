const fs = require("fs");

let values = fs.readFileSync("input.txt", "utf8").split("\r\n");
let input = values[0].split(',')

function win_game(N) {
    let nums = new Map()
    let sequence = [...input.map((a) => +a)]
    for (let i = 0; i < N; i++) {
        let curr = sequence[i]
        if (!nums.has(curr)) {
            nums.set(curr, i)
        }
        if (i + 1 == sequence.length) {
            sequence.push(i - nums.get(curr))
        }
        nums.set(curr, i)
    }
    return sequence[N-1]
}

function solve_part_one() {
    return win_game(2020)
}

function solve_part_two() {
    return win_game(30000000)
}

console.log(solve_part_one())
console.log(solve_part_two())
