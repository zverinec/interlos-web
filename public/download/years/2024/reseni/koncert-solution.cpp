#include <iostream>
#include <vector>

using namespace std;

typedef long long int ll;
typedef unsigned int uint;
#define STUDENTS 15
#define INF 1234567890

void fill(vector<bool> &valid, uint state){
    if (valid[state]) {
        return;
    }
    valid[state] = true;
    for (int i=0; i<STUDENTS; i++){
        fill(valid, state | (1 << i));
    }
}

void findValidNew(vector<vector<bool>> &history, vector<bool> &valid, const vector<uint> &clubs, uint state, int i) {
    if (history[state][i]) {
        return;
    }
    history[state][i] = true;
    if (i == clubs.size()){
        // include all supersets, too
        fill(valid, state);
        return;
    }
    bool hasAny = false;
    vector<uint> next;
    for (int j=0; j<STUDENTS; j++){
        if ((clubs[i] & (1 << j))) {
            if (state & (1 << j)) {
                hasAny = true;
            }
            else {
                next.push_back(state | (1 << j));
            }
        }
    }
    if (hasAny) {
        findValidNew(history, valid, clubs, state, i + 1);
    }
    else {
        for (uint nxt: next) {
            findValidNew(history, valid, clubs, nxt, i + 1);
        }
    }
}

int main() {
    vector<bool> valid(1 << STUDENTS, false);
    int n;
    cin  >> n;
    string s;
    vector<uint> clubs(n);
    for (int i=0; i<n; i++){
        cin >> s;
        uint club=0;
        for (char c: s){
            club |= 1 << (c - 'a');
        }
        clubs[i] = club;
    }
    vector<vector<bool>> history(1 << STUDENTS, vector<bool>(n + 1, false));
    findValidNew(history, valid, clubs, 0, 0);
    
    string schedule;
    cin >> schedule;
    int m = schedule.size();
    vector<vector<int>> next(m, vector<int>(STUDENTS, INF));
    for (int i=m-2; i>=0; i--){
        for (int j=0; j<STUDENTS; j++){
            next[i][j] = schedule[i + 1] == 'a' + j ? i + 1 : next[i + 1][j];
        }
    }
    
    ll result = 0;
    vector<ll> lengths;
    for (int i=0; i<m; i++){
        int j = i;
        uint state = 1 << (schedule[i] - 'a');
        while (j < m && !valid[state]) {
            int closest = INF;
            int closestC = 0;
            for (int k=0; k<STUDENTS; k++){
                if ((state & (1 << k)) == 0 && next[i][k] < closest) {
                    closestC = k;
                    closest = next[i][k];
                }
            }
            if (closest != INF){
                state |= 1 << closestC;
            }
            j = closest;
        }
        if (j != INF) {
            lengths.push_back(j - i + 1);
            result += m - j;
        }
    }
    cout << result << '\n';
    return 0;
}
