def loadPermutation(path):
    perm = []

    with open(path) as f:
        lines = f.readlines()

        n = len(lines[0]) // 2
        for i in range(n):
            for line in lines:
                if i > 0:
                    if line[i*2-1] == "_":
                        i -= 1
                        continue
                if i < n:
                    if line[i*2+1] == "_":
                        i += 1
                        continue
            perm.append(i)
    return perm

def reversePermutation(perm):
    rev = [None for _ in perm]
    for f,t in enumerate(perm):
        rev[t] = f
    return rev

def mix(a, e, perm):
    m = bin(a)[2:].zfill(32) + bin(e)[2:].zfill(32)
    n = list(m)
    for p in range(len(perm)):
        n[perm[p]] = m[p]
    n = "".join(n)
    a = int(n[0:32], 2)
    e = int(n[32:64], 2)
    return a, e

permutation = loadPermutation('P2-input.txt')
permutation = reversePermutation(permutation)

a = 42
e = 42
while a != 16641:
    a, e = mix(a, e, permutation)

print(e)
