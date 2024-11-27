#include <stdio.h>
#include <stdlib.h>
#include <wchar.h>
#include <locale.h>

unsigned long long topMask() {
	return 1LLU << (sizeof(unsigned long long) * 8 - 1);
}

void printByBit(unsigned long long value) {
	printf("%llu: ",value);
	unsigned long long mask = topMask();
	for(int i = sizeof(unsigned long long)*8; i > 0; i--) {
		if(value & mask) {
			printf("1");
		} else {
			printf("0");
		}
		value = value << 1;
		if((i -1) % 8 == 0) printf(" ");

	}
	printf("\n");
}

unsigned long long unicodeToUtf8(unsigned long long value) {
	printf("%llu ", value);
	if (value < 0b100000000000) {	// One -> Two (can hold up to 11 bits)
		return ((0b11000000 | (value >> 6)) << 8) | (0b10000000 | (value & 0b00111111));
	} else if(value < 0b10000000000000000) {	// Two -> Three (can hold up to 16 bits)
		return ((0b11100000 | (value >> 12)) << 16) | ((((value >> 6) & 0b00111111) | (0b10000000)) << 8) | (0b10000000 | ((value) & 0b00111111));
	} else if(value < 0b100000000000000000000000000) {	// Three -> Five (can hold up up to 26 bits)
		return ((0b11111000 | (value >> 24)) << 32) | ((((value >> 18) & 0b00111111) | (0b10000000)) << 24) | ((((value >> 12) & 0b00111111) | (0b10000000)) << 16) | ((((value >> 6)  & 0b00111111) | (0b10000000)) << 8) | (0b10000000 | ((value) & 0b00111111));
	} else if(value < 0b10000000000000000000000000000000000000000) {	// Three -> Five (can hold up up to 42 bits)
		return ((0b11111111ULL << 56)) |
			   ((((value >> 36) & 0b00111111) | (0b10000000)) << 48) |
			   ((((value >> 30) & 0b00111111) | (0b10000000)) << 40) |
			   ((((value >> 24) & 0b00111111) | (0b10000000)) << 32) |
			   ((((value >> 18) & 0b00111111) | (0b10000000)) << 24) |
			   ((((value >> 12) & 0b00111111) | (0b10000000)) << 16) |
			   ((((value >> 6) & 0b00111111) | (0b10000000)) << 8) |
			   (0b10000000 | ((value) & 0b00111111));
	}
	printf("Error: cannot go higher.");
	exit(1);
	return 0;
}

const wchar_t* cipherText = L"Åhöj Èrîků, víš, jàk pömáhámé s ťöů ñàší drůhövöů pröpàgàcí pömöcí ťé îñťérñéťövé söůťěžé örgàñîzövàñé ñěkdé v Brñě... Ťàk jsém sî říkàľ, žé ťö ťvöjé výsľédñé hésľö prö úľöhů P8 sé mî vůbéc ñéľíbí, mysľím, žé bychöm měľî zvöľîť jîñé. Pöůžîj söůsľöví véľmî pödöbñé ñázvů úľöhy, kťéré má söůvîsľösť s Bééťhövéñém à Schîľľérém. Ťàk ťö bůdé ñéjľépší. Zdràví Ťrømös. P.S. ÅSCII SUCKS!";

unsigned long long encode(wchar_t value) {
	unsigned long long temp = (unsigned long long) value;
	wprintf(L"%C %d: ", value, temp);

	for(int i = 4; i > 0; i--) {
		temp = unicodeToUtf8(temp);
		//printByBit(temp);
		wprintf(L"%llu ", temp);
	}
	wprintf(L"\n");
	return temp;
}

int main() {
	if(sizeof(unsigned long long) < 8) {
		printf("Too small unsigned long long.");
		return 1;
	}
	setlocale(LC_ALL, "cs_CZ.UTF-8");
	wprintf(L"Encoding cipher text: %S\n", cipherText);

	FILE *f = fopen("unicode-zadani","wb");
	if (f == NULL) {
		printf("No such file.");
		return 1;
	}

	unsigned long long temp;
	unsigned char buf;
	for(int i = 0; i < wcslen(cipherText); i++) {
		temp = encode(cipherText[i]);
		printByBit(temp);
		for (int i = 8; i > 0; i--) {
			buf = (temp >> (i-1)*8) & 0b11111111;
			if (buf != 0) {
				fwrite(&buf, sizeof(unsigned char), 1, f);
			}
		}
	}

	fclose(f);
	printf("All work done.");
	return 0;
}
