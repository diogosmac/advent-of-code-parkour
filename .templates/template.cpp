// run with "g++ template.cpp -o template && ./template"

#include <fstream>
#include <iostream>
#include <vector>

using namespace std;

vector<string> values;

int solve_part_one() {
    return 0;
}

int solve_part_two() {
    return 0;
}

int main() {

  ifstream f;
  f.open("input.txt");
  string line;

  while (!f.eof()) {
    getline(f, line);
    values.push_back(line);
  }

  cout << solve_part_one() << endl;
  cout << solve_part_two() << endl;

  return 0;

}
