#include <fstream>
#include <iostream>
#include <vector>
#include <string>
#include <algorithm>
#include <unordered_map>

using namespace std;

vector<int> joltages;

int solve_part_one() {

  int oneDiffs = 0, threeDiffs = 0;

  for (size_t i = 1; i < joltages.size(); i++) {
    int diff = joltages[i] - joltages[i-1];
    if (diff == 1) oneDiffs++;
    else if (diff == 3) threeDiffs++;
  }

  return oneDiffs * threeDiffs;

}

typedef unsigned long long ull;

ull solve_part_two() {

  int len = joltages[joltages.size() - 1] + 1;
  ull counter[len] = { 0 };
  counter[0] = 1;

  for (int i : joltages) {
    for (int j = 1; j <= 3; j++) {
      if (i-j >= 0) {
        counter[i] += counter[i-j];
      }
    }
  }

  return counter[len - 1];

}

int main() {

  ifstream f;
  f.open("input.txt");
  string line;

  while (!f.eof()) {
    getline(f, line);
    joltages.push_back(stoi(line));
  }
  sort(joltages.begin(), joltages.end());
  joltages.insert(joltages.begin(), 0);
  joltages.push_back(joltages[joltages.size() - 1] + 3);

  cout << solve_part_one() << endl;
  cout << solve_part_two() << endl;

  return 0;

}
