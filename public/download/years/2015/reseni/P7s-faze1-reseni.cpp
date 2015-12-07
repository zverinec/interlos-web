#include <cstdio>
#include <cstring>

char codes[26][20] = {
	"qlfa",
	"qravo",
	"qharlie",
	"qelta",
	"qcho",
	"qoxtrot",
	"qolf",
	"qotel",
	"qndia",
	"quliett",
	"qilo",
	"qima",
	"qike",
	"qovember",
	"qscar",
	"qapa",
	"quebec",
	"qomeo",
	"qierra",
	"qango",
	"qniform",
	"qictor",
	"qhiskey",
	"qray",
	"qankee",
	"qulu",
};

char word[1048576];

int main()
{
	while (scanf("%s", word) == 1) {
		for (int i = 0; i < 26; i++) {
			if (strcmp(word, codes[i]) == 0) {
				printf("%c", 'a' + i);
			}
		}
	}
	printf("\n");

	return 0;
}
