#include <iostream>
#include <set>
#include <string>
#include <vector>

using namespace std;

bool can_fit(unsigned digit, unsigned from, const string &sequence) {
    if (from + digit >= sequence.length()) {
        return false;
    }
    if (sequence[from] == '0' && sequence[from + digit] == '0') {
        return true;
    }
    return false;
}

void go(string sequence, vector<int> remaining, set<string> &found)
{
    if (remaining.empty()) {
        if (found.insert(sequence).second) {
            return;
        }
    }
    for (vector<int>::const_iterator iterator = remaining.begin(), end = remaining.end(); iterator != end; ++iterator) {
        int digit = *iterator;
        for(unsigned j = 0; j < sequence.length(); j++) {
            if (can_fit(digit, j, sequence)) {
                // Clone sequence and update
                string new_sequence = sequence;
                new_sequence[j] = digit + '0';
                new_sequence[j+digit] = digit + '0';
                // Clone and update remaining
                vector<int> new_remaining;;
                for (vector<int>::const_iterator it = remaining.begin(), end = remaining.end(); it != end; ++it ) {
                    if (*it != digit)
                        new_remaining.push_back( *it );
                }
                go(new_sequence, new_remaining, found);
            }
        }
    }
}

int main() {

    // Prepare solutions
    const int n = 8;
    string state(2*n, '0');
    vector<int> remaining;
    for (int i = 0; i < n; i++) {
        remaining.push_back(i+1);
    }
    // Run backtracking
    set<string> found;
    go(state, remaining, found);

    // Print unique and sorted solution
    for (std::set<string>::const_iterator iterator = found.begin(), end = found.end(); iterator != end; ++iterator) {
        cout << *iterator << endl;
    }

    cout << endl
         << "nejmensi reseni: " << *found.begin() << std::endl
         << "pocet reseni: " << found.size() << endl;
    return 0;
}
