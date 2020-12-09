#include <fstream>
#include <iostream>
#include <vector>
#include <string>
#include <numeric>
#include <bits/stdc++.h>

using namespace std;

typedef unsigned long long ull;

vector<ull> numbers;
int target_number;

ull solve_part_one() {

  for (unsigned i = 25; i < numbers.size(); i++) {
      ull num = numbers[i];
      bool valid = false;
      for (unsigned j = i - 25; j < i - 1; j++) {
          ull a = numbers[j];
          for (unsigned k = j + 1; k < i; k++) {
              ull b = numbers[k];
              if (a + b == num) {
                  valid = true;
                  break;
              }
          }
          if (valid)
            break;
      }
      if (!valid) {
        target_number = num;
        return num;
      }
  }

  return 0;

}

ull solve_part_two() {

  for (size_t i = 0; i < numbers.size() - 1; i++) {

      vector<ull> numset;
      numset.push_back(numbers[i]);
      numset.push_back(numbers[i+1]);
      ull sum;
      size_t j = i + 2;
      while (
        j < numbers.size() &&
        (sum = accumulate(numset.begin(), numset.end(), 0)) < target_number
      ) {
        numset.push_back(numbers[j++]);
      }
      if (sum == target_number) {
        return *min_element(numset.begin(), numset.end()) + *max_element(numset.begin(), numset.end());
      }

  }

  return 0;

}

int main() {

  ifstream f;
  f.open("input.txt");
  string line;

  while (!f.eof()) {
    getline(f, line);
    numbers.push_back(stoull(line));
  }

  cout << solve_part_one() << endl;
  cout << solve_part_two() << endl;

  return 0;

}
