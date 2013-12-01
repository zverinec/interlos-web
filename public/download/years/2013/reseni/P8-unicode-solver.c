#include <stdio.h>
#include <wchar.h>
#include <locale.h>

unsigned long long topMask() {
    return 1LLU << (sizeof(unsigned long long) * 8 - 1);
}
/* Just debbuging fuction */
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
}
int countLeadingOnes(unsigned char byte) {
    int count = 0;
    int i = 7;
    while(byte & (1 << i--)) {
        count++;
    }
    return count;
}

void printAsWChar(unsigned long long value) {
    wprintf(L"%C", value);
}

void decode(unsigned long long value, int count) {
    if(count == 0) {
        printAsWChar(value);
        return;
    }
    unsigned long long newValue = 0;
    unsigned long long mask = topMask();
    while (!(value & mask)) {   // Strip leading zeros
        value <<= 1;
    }
    int bytes = 0;
    while ((value & mask) && bytes < 8) { // Read how many bytes
        bytes += 1;
        value <<= 1;
    }
    for(int i = bytes; i < 8; i++) {
        newValue += (value & mask) ? 1 : 0;
        newValue <<= 1;
        value <<= 1;
    }
    for(int i = bytes; i > 1; i--) {
        for(int j = 0; j < 8; j++) {
            if(j != 0 && j != 1) {
                newValue += (value & mask) ? 1 : 0;
                newValue <<= 1;
            }
            value <<= 1;
        }
    }
    decode(newValue >> 1, count - 1);
}

int main() {
    if(sizeof(unsigned long long) < 8) {
        printf("Too small unsigned long long.");
        return 1;
    }
    FILE *f = fopen("unicode-zadani","rb");
    if (f == NULL) {
        printf("No such file.");
        return 1;
    }
    setlocale(LC_ALL, "cs_CZ.UTF-8");
    unsigned char byte = 0;
    unsigned long long buf = 0;
    int toNextSplit = 0;
    int isFirst = 1;
    while(!feof(f)) {
        fread(&byte, sizeof(char), 1, f);
        if(toNextSplit == 0) {
            if(!isFirst) {
                decode(buf, 4);
            }
            buf = 0;
            toNextSplit = countLeadingOnes(byte);
        }
        isFirst = 0;
        buf += byte;
        toNextSplit -= 1;
        if(toNextSplit != 0) buf <<= 8;
    }
    fclose(f);
    return 0;
}
