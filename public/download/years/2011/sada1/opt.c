/**
 * Computation of a really sophisticated function.
 * Programmed by the Interlos organizing team.
 * All rights reserved.
 */
#include <stdio.h>

int f(int A, int B, int C);
int g(int a, int b, int c);
int h(int i);
int m(int i);

int main()
{
    int A, B, C;
    scanf("%d %d %d", &A, &B, &C);
    printf("%d\n", f(A, B, C));
    return 0;
}


int f(int A, int B, int C)
{
    int i;
    int s = 0;
    for (i = 0; i < C; i++) {
        s = (s + A * g(B, i, h(i))) % h(i+1);
    }
    return s % h(C);
}


int g(int a, int b, int c)
{
    int i;
    int r = 1;
    for (i = 0; i < b; i++) {
        r = (r * a) % c;
    }
    return r;
}


int h(int i)
{
    int p[10000];
    int pc = 1;
    p[1] = 2;
    do {
        int n = p[pc];
        int ok = 0;
        do {
            int j;
            int thisOk = 1;
            n++;
            for (j = 1; j <= pc; j++) {
                if (n % p[j] == 0) thisOk = 0;
            }
            if (thisOk) ok = 1;
        } while (!ok);

        pc++;
        p[pc] = n;
    } while (pc < 9999);
    return p[ m(i) ];
}


int m(int i)
{
    int k = 6543 ^ i;
    int j, n;
    for (n = 0; n < (3456 & i); n++) {
        for (j = 0; j < i; j++) {
            k = k ^ j;
        }
    }
    return i ^ k;
}
