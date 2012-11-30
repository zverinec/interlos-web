// compile: javac opt.java
// run:     java -cp . opt

import java.util.Scanner;

/**
 * Computation of a really sophisticated function.
 * Programmed by the Interlos organizing team.
 * All rights reserved.
 */
public class opt
{
    public static void main(String[] args)
    {
        int A, B, C;
        Scanner in = new Scanner(System.in);
        A = in.nextInt();
        B = in.nextInt();
        C = in.nextInt();
        in.close();
        System.out.println(f(A, B, C));
    }


    static int f(int A, int B, int C)
    {
        int s = 0;
        for (int i = 0; i < C; i++) {
            s = (s + A * g(B, i, h(i))) % h(i+1);
        }
        return s % h(C);
    }


    static int g(int a, int b, int c)
    {
        int r = 1;
        for (int i = 0; i < b; i++) {
            r = (r * a) % c;
        }
        return r;
    }


    static int h(int i)
    {
        int[] p = new int[10000];
        int pc = 1;
        p[1] = 2;
        do {
            int n = p[pc];
            boolean ok = false;
            do {
                boolean thisOk = true;
                n++;
                for (int j = 1; j <= pc; j++) {
                    if (n % p[j] == 0) thisOk = false;
                }
                if (thisOk) ok = true;
            } while (!ok);

            pc++;
            p[pc] = n;
        } while (pc < 9999);
        return p[ m(i) ];
    }


    static int m(int i)
    {
        int k = 6543 ^ i;
        for (int n = 0; n < (3456 & i); n++) {
            for (int j = 0; j < i; j++) {
                k = k ^ j;
            }
        }
        return i ^ k;
    }
}
