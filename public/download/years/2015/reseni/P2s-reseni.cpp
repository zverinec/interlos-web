// pro kompilaci použijte tento příkaz:
// g++ -Wall -O2 -o P2-reseni P2-reseni.cpp

#include <cstdio>

int getNthPerson(int n)
{
	for (int count = 1; ; count *= 2) {
		for (int person = 0; person < 5; person++) {
			n -= count;
			if (n <= 0) {
				return person;
			}
		}
	}
}

int main()
{
	int positions[6] = { 10, 100, 1000, 10000, 100000, 1000000 };

	for (int i = 0; i < 6; i++) {
		printf("%c", 'A'+getNthPerson(positions[i]));
	}

	printf("\n");

	return 0;
}
