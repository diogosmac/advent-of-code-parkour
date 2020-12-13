const fs = require("fs");

let values = fs.readFileSync("input.txt", "utf8").split("\r\n");
const earliest = parseInt(values[0])
const buses = values[1].split(',')

function solve_part_one() {
    let min = earliest
    let id = null
    for (let bus of buses) {
        let waiting = bus - (earliest % bus)
        if (waiting < min) {
            min = waiting
            id = bus
        }
    }
    return min * id
}

function solve_part_two() {
    let time = 1, step = 1
    for (let i = 0; i < buses.length; i++) {
        let bus = buses[i]
        if (bus == 'x') {
            continue
        }
        while ((time + i) % bus != 0) {
            time += step
        }
        step *= bus
    }
    return time
}

console.log(solve_part_one())
console.log(solve_part_two())
