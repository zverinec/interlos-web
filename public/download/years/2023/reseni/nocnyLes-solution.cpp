#include<vector>
#include<iostream>
#include<fstream>
#include<queue>
#include<tuple>

using namespace std;

std::vector<int> DR = {0, 1, 0, -1};
std::vector<int> DC = {1, 0, -1, 0};
constexpr int INF = 1034567890;

struct Problem {
	int r;
	int c;
	int si;
	int sj;
	int ti;
	int tj;
	std::vector<std::vector<int>> cold;
	int Z;
};

Problem load(const string &filename){
	ifstream f(filename);
	int r, c, si, sj, ti, tj, Z;
	f >> r >> c >> si >> sj >> ti >> tj >> Z;
	vector<vector<int>> cold(r, vector<int>(c));
	for (int i=0; i<r; i++){
		for (int j=0; j<c; j++){
			f >> cold[i][j];
		}
	}
	return Problem {r, c, si, sj, ti, tj, std::move(cold), Z};
}

vector<vector<int>> dijkstra(int r, int c, int ti, int tj, const vector<vector<int>> &cost) {
	priority_queue<tuple<int, int, int>> pq;
	vector<vector<int>> result(r, vector<int>(c, INF));
	pq.emplace(0, ti, tj);
	while (!pq.empty()) {
		auto [dist, i, j] = pq.top();
		pq.pop();
		dist = -dist;
		if (result[i][j] <= dist) {
			continue;
		}
		result[i][j] = dist;
		for (int dd=0; dd<4; dd++){
			int i2 = i + DR[dd];
			int j2 = j + DC[dd];
			if (0 <= i2 && i2 < r && 0 <= j2 && j2 < c && result[i2][j2] > dist + cost[i2][j2]){
				pq.emplace(-(dist + cost[i2][j2]), i2, j2);
			}
		}
	}
	return result;
}

pair<int, vector<vector<pair<int, int>>>> solve(const Problem &prob) {
	auto [r, c, si, sj, ti, tj, cold, Z] = prob;
	queue<tuple<int, int, int, int>> q;
	vector<vector<pair<int, int>>> m(r, vector<pair<int, int>>(c, make_pair(INF, -1)));
	auto minCold = dijkstra(r, c, ti, tj, cold);
	m[si][sj] = make_pair(0, 0);
	q.emplace(0, 0, si, sj);
	while (!q.empty()) {
		auto [t, z, i, j] = q.front();
		q.pop();
		if (i == ti && j == tj && z <= Z) {
			return make_pair(t, m);
		}
		for (int dd=0; dd<4; dd++){
			int i2 = i + DR[dd];
			int j2 = j + DC[dd];
			if (0 <= i2 && i2 < r && 0 <= j2 && j2 < c && z + cold[i][j] + minCold[i2][j2] <= Z && m[i2][j2].first > z + cold[i][j]) {
				m[i2][j2] = make_pair(z + cold[i][j], t + 1);
				q.emplace(t + 1, z + cold[i][j], i2, j2);
			}
		}
	}
	return make_pair(m[ti][tj].second, m);
}

int main() {
	string path = "./";
	Problem problem = load(path + "nocny_les_sample1.in");
	auto [result1, m1] = solve(problem);
	cout << result1 << '\n';
	
	problem = load(path + "nocny_les_sample2.in");
	auto [result2, m2] = solve(problem);
	cout << result2 << '\n';
	
	problem = load(path + "nocny_les.in");
	
	cout << "Solution: " << solve(problem).first;
	
	return 0;
}