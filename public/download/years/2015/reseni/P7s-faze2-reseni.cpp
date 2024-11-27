#include <cstdio>
#include <cstring>

char codes[13][20] = {
	"nula",
	"jedna",
	"dve",
	"tri",
	"ctyri",
	"pet",
	"sest",
	"sedm",
	"osm",
	"devet",
	"uvozovka",
	"dvojtecka",
	"mezera",
};

char word[1048576];

int main()
{
	while (scanf("%s", word) == 1) {
				for (int i = 0; i < 13; i++) {
			if (strcmp(word, codes[i]) == 0) {
							if(i<10)
								printf("%d", i);
							else if(i==10)
								printf("\"");
							else if(i==11)
								printf(":");
							else
								printf(" ");
			}
		}
	}
	printf("\n");

	return 0;
}
