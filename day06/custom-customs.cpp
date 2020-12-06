#include <fstream>
#include <iostream>
#include <vector>
#include <string>
#include <math.h>
#include <algorithm>

using namespace std;

int count_unique_answers(vector<string> group) {
  string answers = "";
  for (string answer : group) {
    for (char c : answer) {
      if (answers.find(c) == string::npos) {
        answers += c;
      }
    }
  }
  return answers.length();
}

int solve_part_one(vector<vector<string>> groups) {
  int count = 0;
  for (vector<string> group : groups) {
    count += count_unique_answers(group);
  }
  return count;
}

int count_common_answers(vector<string> group) {
  int answers[26] = {0};
  for (string answer : group) {
    for (char c : answer) {
      answers[(c - 'a')]++;
    }
  }
  int count = 0;
  for (int letter : answers) {
    if (letter == group.size()) {
      count ++;
    }
  }
  return count;
}

int solve_part_two(vector<vector<string>> groups) {
  int count = 0;
  for (vector<string> group : groups) {
    count += count_common_answers(group);
  }
  return count;
}

int main() {

  ifstream f;
  f.open("input.txt");
  string line;

  vector<vector<string>> groups;
  vector<string> group;

  while (!f.eof()) {

    getline(f, line);

    if (line.length() == 0) {
      groups.push_back(group);
      group.clear();
    }
    else {
      group.push_back(line);
    }

  }

  cout << solve_part_one(groups) << endl;
  cout << solve_part_two(groups) << endl;

  return 0;

}
