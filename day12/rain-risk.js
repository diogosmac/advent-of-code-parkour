const fs = require("fs");

let values = fs.readFileSync("input.txt", "utf8").split("\r\n");

const delta = {
    N: [0, 1],
    E: [1, 0],
    S: [0, -1],
    W: [-1, 0],
}

function turn(direction, degrees, facing) {
    let directions = Object.keys(delta)
    let current = directions.indexOf(facing)
    if (direction == 'R') {
        return directions[
            (current+(degrees/90)) % directions.length]
    } else if (direction == 'L') {
        return directions[
            (current-(degrees/90) + directions.length) % directions.length]
    }
}

function move(pos, amount, direction) {
  return [
      pos[0] + amount * delta[direction][0],
      pos[1] + amount * delta[direction][1]
  ]
}

function solve_part_one() {
    let directions = Object.keys(delta)
    let facing = 'E'
    let pos = [0, 0]
    for (let value of values) {
        let action = value[0]
        let amount = parseInt(value.substring(1))
        if (directions.includes(action)) {
            pos = move(pos, amount, action)
        } else if (action == 'F') {
            pos = move(pos, amount, facing)
        } else {
            facing = turn(action, amount, facing)
        }
    }
    return Math.abs(pos[0]) + Math.abs(pos[1])
}

function move_towards_waypoint(pos, waypoint, amount) {
    return [
        pos[0] + (amount * waypoint[0]),
        pos[1] + (amount * waypoint[1])
    ]
}

function rotate_waypoint(pos, waypoint, action, amount) {
    if (amount == 180) {
        return [
            -waypoint[0],
            -waypoint[1]
        ]
    } else if ((action == 'L' && amount == 90) || (action == 'R' && amount == 270)) {
        return [
            -waypoint[1],
            waypoint[0]
        ]
    } else if ((action == 'R' && amount == 90) || (action == 'L' && amount == 270)) {
        return [
            waypoint[1],
            -waypoint[0]
        ]
    } else {
      console.log('Invalid Rotation:', action+amount)
      return waypoint
    }
}

function solve_part_two() {
    let directions = Object.keys(delta)
    let pos = [0, 0]
    let waypoint = [10, 1]
    for (let value of values) {
      let action = value[0]
      let amount = parseInt(value.substring(1))
      if (directions.includes(action)) {
        waypoint = move(waypoint, amount, action)
      } else if (action == 'F') {
        pos = move_towards_waypoint(pos, waypoint, amount)
      } else {
        waypoint = rotate_waypoint(pos, waypoint, action, amount)
      }
    }
    return Math.abs(pos[0]) + Math.abs(pos[1])
}

console.log(solve_part_one())
console.log(solve_part_two())
