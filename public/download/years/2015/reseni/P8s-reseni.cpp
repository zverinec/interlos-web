// pro kompilaci použijte tento příkaz:
// g++ -Wall -O2 -std=c++11 -o P8-reseni P8-reseni.cpp

#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

const int N = 200;
const long long Budget = 408130356513LL;

int main()
{
	vector<long long> prices[6];

	for (int i = 0; i < 6; i++) {
		prices[i].resize(N);

		for (int j = 0; j < N; j++) {
			cin >> prices[i][j];
		}
	}

	vector<long long> halfPrices;

	for (int i = 0; i < N; i++) {
		for (int j = 0; j < N; j++) {
			for (int k = 0; k < N; k++) {
				halfPrices.push_back(prices[0][i] + prices[1][j] + prices[2][k]);
			}
		}
	}

	sort(halfPrices.begin(), halfPrices.end());

	long long result = 0;

	for (int i = 0; i < N; i++) {
		for (int j = 0; j < N; j++) {
			for (int k = 0; k < N; k++) {
				long long secondHalfPrice = prices[3][i] + prices[4][j] + prices[5][k];
				long long maxFirstHalfPrice = Budget - secondHalfPrice;
				result += upper_bound(halfPrices.begin(), halfPrices.end(), maxFirstHalfPrice) - halfPrices.begin();
			}
		}
	}

	cout << result << endl;

	return 0;
}
