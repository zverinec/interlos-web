#include <iostream>
#include <vector>
#include <unordered_map>
#include <string>

typedef long long int ll;
using namespace std;

#define UNKNOWN (-2)
#define IMPOSSIBLE (-1)

struct Solver {
    int n;
    vector<char> A;
    vector<char> B;
    unordered_map<int, char> dpA;
    unordered_map<int, char> dpB;
    vector<vector<char>> inA;
    
    Solver(const vector<char> &a, const vector<char> &b) : n(a.size()),
             A(a), B(b), inA(256) {
        for (int i=0; i<n; i++){
            inA[A[i]].push_back(i);
        }
        dpB[(1 << (2*n)) - 1] = -3;
    }
    
    bool solve(int state, int a){
        if (dpB.count(state))
            return dpB[state] != IMPOSSIBLE;
        for (int i=0; i<n; i++){
            if (!(state & (1 << i))){
                for (int j: inA[B[i] + a]){
                    if (!(state & (1 << (j+n))) && solve(
                            state | (1 << i) | (1 << (j+n)), a + 1)){
                        dpB[state] = i;
                        dpA[state] = j;
                        return true;
                    }
                }
            }
        }
        dpB[state] = IMPOSSIBLE;
        return false;
    }
    
    bool hasSolution(){
        return dpB[0] != IMPOSSIBLE && dpB[0] != UNKNOWN;
    }
    
    vector<char> getSolution(){
        vector<char> result;
        int state = 0;
        while (state != (1 << (2*n)) - 1){
            result.push_back(B[dpB[state]]);
            state = state | (1 << dpB[state]) | (1 << (dpA[state]+n));
        }
        return result;
    }
};

int main() {
    //vstupne pocty prevedene do ASCII
    string before = "COATBCNSQCQPNC";
    string after = "N]IYE_W]SLBQI\\";
    vector<char> a(after.begin(), after.end());
    vector<char> b(before.begin(), before.end());
    Solver solver(a, b);
    solver.solve(0, 1);
    
    if (solver.hasSolution()) {
        for (char p: solver.getSolution()) {
            cout << p;
        }
    }
    return 0;
}