// $ g++ -O2 -Wall -std=c++11 P6s-reseni.cpp -o p6s-reseni
// $ ./p6s-reseni <P6-schranky.txt

#include <bitset>
#include <cstdio>
#include <cstdlib>
#include <cstring>
#include <queue>
#include <set>
#include <utility>
#include <vector>

using namespace std;

const int R = 8;
const int C = 8;
const int MaxLabelCount = R*C;

struct Point { int r, c; };

int n;
int labels[R][C];
int sizes[MaxLabelCount];
vector<Point> fields[MaxLabelCount];
int visited[R][C];
int age;
set<unsigned long long> memory;

const int dr[4] = { 1, 0, -1, 0 };
const int dc[4] = { 0, -1, 0, 1 };

bool isValid(Point p) {
    return p.r >= 0 && p.r < R && p.c >= 0 && p.c < C;
}

bool canBeCovered(Point p, int label) {
    if (labels[p.r][p.c] != -1) {
        return false;
    }
    for (int k = 0; k < 4; k++) {
        Point q = (Point) { p.r + dr[k], p.c + dc[k] };
        if (!isValid(q)) {
            continue;
        }
        int l = labels[q.r][q.c];
        if (l != -1 && l != label) {
            return false;
        }
    }
    return true;
}

bool dfs() {
    unsigned long long mask = 0;
    for (int r = 0; r < R; r++) {
        for (int c = 0; c < C; c++) {
            if (labels[r][c] != -1) {
                mask |= (1ULL << (r*C+c));
            }
        }
    }
    if (memory.find(mask) != memory.end()) {
        return false;
    }
    memory.insert(mask);
    int total = 0;
    queue<Point> qq;
    age++;
    for (int r = 0; r < R; r++) {
        for (int c = 0; c < C; c++) {
            if (labels[r][c] == -1) {
                total++;
                if (total == 1) {
                    qq.push((Point) { r, c });
                    visited[r][c] = age;
                }
            }
        }
    }
    int found = 0;
    while (!qq.empty()) {
        Point p = qq.front();
        qq.pop();
        found++;
        for (int k = 0; k < 4; k++) {
            Point q = (Point) { p.r + dr[k], p.c + dc[k] };
            if (isValid(q) && labels[q.r][q.c] == -1 && visited[q.r][q.c] != age) {
                visited[q.r][q.c] = age;
                qq.push(q);
            }
        }
    }
    if (total != found) {
        return false;
    }
    int best = -1;
    for (int i = 0; i < n; i++) {
        if ((int) fields[i].size() == sizes[i]) {
            continue;
        }
        if (best == -1 || sizes[i] < sizes[best]) {
            best = i;
        }
    }
    if (best == -1) {
        return true;
    }
    vector<Point> options;
    age++;
    for (Point p : fields[best]) {
        for (int k = 0; k < 4; k++) {
            Point q = (Point) { p.r + dr[k], p.c + dc[k] };
            if (isValid(q) && visited[q.r][q.c] != age) {
                visited[q.r][q.c] = age;
                if (canBeCovered(q, best)) {
                    options.push_back(q);
                }
            }
        }
    }
    for (Point p : options) {
        labels[p.r][p.c] = best;
        fields[best].push_back(p);
        if (dfs()) {
            return true;
        }
        fields[best].pop_back();
        labels[p.r][p.c] = -1;
    }
    return false;
}

int main() {
    for (int r = 0; r < R; r++) {
        for (int c = 0; c < C; c++) {
            char token[20];
            scanf(" %s", token);
            if (strcmp(token, "-") != 0) {
                labels[r][c] = n;
                sizes[n] = atoi(token);
                fields[n].push_back((Point) { r, c });
                n++;
            } else {
                labels[r][c] = -1;
            }
        }
    }
    if (!dfs()) {
        printf("Impossible\n");
        return 0;
    }
    int s = 0;
    for (int r = 0; r < R; r++) {
        for (int c = 0; c < C; c++) {
            if (labels[r][c] == -1) {
                s++;
            }
            if (s != 0 && (labels[r][c] != -1 || c == C-1)) {
                printf("%d", s);
                s = 0;
            }
        }
    }
    printf("\n");
    return 0;
}
