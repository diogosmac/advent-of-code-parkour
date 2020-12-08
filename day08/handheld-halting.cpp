#include <fstream>
#include <iostream>
#include <vector>
#include <string>
#include <unordered_set>

using namespace std;

vector<string> instructions;
int accumulator;

typedef enum instruction_tag {
  acc,
  jmp,
  nop,
  invalid
} tag;

tag convert_tag(string tag) {
  if (tag == "acc") return acc;
  if (tag == "jmp") return jmp;
  if (tag == "nop") return nop;
  return invalid;
}

int solve_part_one() {

  unordered_set<size_t> executed;
  accumulator = 0;

  for (size_t i = 0; i < instructions.size(); i++) {
    if (executed.find(i) != executed.end()) {
      return accumulator;
    }
    executed.insert(i);

    string instruction = instructions[i];
    tag tag = convert_tag(
      instruction.substr(
        0, instruction.find(" ")));
    int param = stoi(
        instruction.substr(instruction.find(" ") + 1));

    switch(tag) {
      case acc:
        accumulator += param;
        break;
      case jmp:
        i += param - 1;
        break;
      case nop:
        break;
      default:
        cout << "invalid instruction" << endl;
        return -1;
    }

  }

  cout << "no repeat instructions" << endl;
  return -1;

}

int calculateAcc(vector<string> instructions, unordered_set<size_t> &executed) {

  int accumulator = 0;

  for (size_t i = 0; i < instructions.size(); i++) {
    if (executed.find(i) != executed.end()) {
      return accumulator;
    }
    executed.insert(i);

    string instruction = instructions[i];
    tag tag = convert_tag(
        instruction.substr(
            0, instruction.find(" ")));
    int param = stoi(
        instruction.substr(instruction.find(" ") + 1));

    switch (tag) {
      case acc:
        accumulator += param;
        break;
      case jmp:
        i += param - 1;
        break;
      case nop:
        break;
      default:
        executed.clear();
        return -1;
    }

  }

  return accumulator;

}

int solve_part_two() {

  int accumulator = 0;

  for (size_t i = 0; i < instructions.size(); i++) {
    string instruction = instructions[i];
    tag tag = convert_tag(
      instruction.substr(0, instruction.find(" ")));
    if (tag == acc) continue;
    vector<string> fixed = instructions;
    unordered_set<size_t> executed;
    if (tag == nop) {
      fixed[i] = "jmp" + instruction.substr(instruction.find(" "));      
    } else if (tag == jmp) {
      fixed[i] = "nop" + instruction.substr(instruction.find(" "));
    }

    accumulator = calculateAcc(fixed, executed);
    if (executed.find(fixed.size() - 1) != executed.end()) break;

  }

  return accumulator;

}

int main() {

  ifstream f;
  f.open("input.txt");
  string line;

  while (!f.eof()) {
    getline(f, line);
    instructions.push_back(line);
  }

  cout << solve_part_one() << endl;
  cout << solve_part_two() << endl;

  return 0;

}
