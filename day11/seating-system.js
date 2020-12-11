const fs = require("fs");

let values = fs.readFileSync("input.txt", "utf8").split("\r\n"); //Reads the values

const directions = [
    [-1,-1],
    [-1, 0],
    [-1, 1],
    [ 0,-1],
    [ 0, 1],
    [ 1,-1],
    [ 1, 0],
    [ 1, 1]
]

function count_occupied(seats) {
    const occupied = seats.reduce((occupied, row) => {
        return (
            occupied + row.split('').reduce((sum, seat) => sum + +(seat === '#'), 0)
            );    
        }, 0);    
        return occupied
    }    
    
function equal_seating(a, b) {
    return (a.join() === b.join())
}

function apply_rules(prev) {
    seats = []
    for (let i = 0; i < prev.length; i++) {
        seats.push([]);
        for (let j = 0; j < prev[0].length; j++) {
            const seat = prev[i][j];
            if (seat === '.') {
                seats[i].push('.');
            } else {
                const adjacents = directions.reduce((adjacents, [dy, dx]) => {
                    return (
                        adjacents + +(!!prev[i + dy] && prev[i + dy][j + dx] === '#')
                    );
                }, 0);
                if (seat === 'L') {
                    seats[i].push(
                        adjacents == 0
                        ? '#'
                        : 'L'
                    )
                } else if (seat === '#') {
                    seats[i].push(
                        adjacents >= 4
                        ? 'L'
                        : '#'
                    )
                }
            }
        }
        seats[i] = seats[i].join('');        
    }
    return seats
}

function solve_part_one() {
    let prev
    let seats = values
    do {
        prev = seats
        seats = apply_rules(seats)
    } while (!equal_seating(prev, seats))
    return count_occupied(seats)
}

function getAdjacents(prev, i, j) {
    return directions.reduce((adjacents, [dy, dx]) => {
        let ci = i + dy
        let cj = j + dx
        let currAdjacents = 0
        while (prev[ci] && prev[ci][cj]) {
            if (prev[ci][cj] == '#') {
                currAdjacents = 1
                break
            } else if (prev[ci][cj] == 'L') {
                break
            }
            ci += dy
            cj += dx
        }
        return adjacents + currAdjacents
    }, 0)
}

function solve_part_two() {
    let prev
    let seats = values
    do {
        prev = seats
        seats = []
        for (let i = 0; i < prev.length; i++) {
            seats.push([]);
            for (let j = 0; j < prev[0].length; j++) {
                const seat = prev[i][j];
                if (seat === '.') {
                    seats[i].push('.');
                } else {
                    const adjacents = getAdjacents(prev, i, j)
                    if (seat === 'L') {
                        seats[i].push(
                            adjacents == 0
                            ? '#'
                            : 'L'
                        )
                    } else if (seat === '#') {
                        seats[i].push(
                            adjacents >= 5
                            ? 'L'
                            : '#'
                        )
                    }
                }
            }
            seats[i] = seats[i].join('');        
        }
    } while (!equal_seating(prev, seats))
    return count_occupied(seats)
}

console.log(solve_part_one())
console.log(solve_part_two())
