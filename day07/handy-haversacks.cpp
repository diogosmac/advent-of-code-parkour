#include <fstream>
#include <iostream>
#include <string>
#include <unordered_map>

using namespace std;

unordered_map<string, unordered_map<string, int>> rules;
string goal = "shiny gold";

bool check_color(unordered_map<string, bool> &valid, string color) {
  if (valid.find(color) != valid.end()) {
    return valid[color];
  }
  if (rules[color].find(goal) != rules[color].end()) {
    valid[color] = true;
  }
  else {
    valid[color] = false;
    for (auto& rule : rules[color]) {
      if (check_color(valid, rule.first)) {
        valid[color] = true;
      }
    }
  }
  return valid[color];
}

int solve_part_one() {
  unordered_map<string, bool> valid;
  int count = 0;
  for (auto rule : rules) {
    if (check_color(valid, rule.first)) {
      count++;
    }
  }
  return count;
}

int count_bags(unordered_map<string, int> &bags, string color) {
  if (bags.find(color) != bags.end()) {
    return bags[color];
  }
  if (rules[color].size() == 0) {
    bags[color] = 0;
    return 0;
  }
  int count = 0;
  for (auto rule : rules[color]) {
    count += rule.second * (count_bags(bags, rule.first) + 1);
  }
  bags[color] = count;
  return bags[color];
}

int solve_part_two() {
  unordered_map<string, int> bags;
  return count_bags(bags, goal);
}

void parse_rule(string line) {
  line = line.substr(0, line.length() - 1);
  size_t pos;
  string color = line.substr(0, (pos = line.find(" bags contain")));
  pos += 14;
  line = line.substr(pos);
  unordered_map<string, int> um;
  while (pos != string::npos) {
    string num = line.substr(0, (pos = line.find(" ")));
    if (num == "no") {
      break;
    }
    pos += 1;
    string inner_color = line.substr(pos, line.find(" bag", pos) - 2);
    um[inner_color] = stoi(num);
    pos = line.find(',');
    line = line.substr(pos + 2);
  }
  rules[color] = um;
  return;
}

int main() {

  ifstream f;
  f.open("input.txt");
  string line;

  while (!f.eof()) {
    getline(f, line);
    parse_rule(line);
  }

  cout << solve_part_one() << endl;
  cout << solve_part_two() << endl;

  return 0;
}
