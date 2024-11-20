import sys

# Computation of a really sophisticated function.
# Programmed by the Interlos organizing team.
# All rights reserved.

def f(A, B, C):
    s = 0
    for i in range(0, C):
        s = (s + A * g(B, i, h(i))) % h(i+1)
    return s % h(C)

def g(a, b, c):
    r = 1
    for i in range(0, b):
        r = (r * a) % c
    return r

def h(i):
    p = 10000*[None]
    pc = 1
    p[1] = 2
    while True:
        n = p[pc]
        ok = False
        while True:
            thisOk = True
            n += 1
            for j in range(1, pc+1):
                if n % p[j] == 0: thisOk = False
            if thisOk: ok = True

            if ok: break

        pc += 1
        p[pc] = n

        if pc >= 9999: break

    return p[ m(i) ]

def m(i):
    k = 6543 ^ i
    for n in range(0, (3456 & i)):
        for j in range(0, i):
            k = k ^ j
    return i ^ k


inValues = sys.stdin.readline().split(' ')
A = int(inValues[0].strip())
B = int(inValues[1].strip())
C = int(inValues[2].strip())
print(f(A, B, C))
