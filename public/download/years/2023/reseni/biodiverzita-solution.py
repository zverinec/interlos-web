
R = C = 8

inner = [0] * (1 << C)
for i in range(1 << C):
    s = f"{i:0{C}b}"
    for j in range(1, len(s)):
        inner[i] += -1 if s[j] == s[j - 1] else 1
freq = [{inner[i]: 1} for i in range(1 << C)]
for row in range(1, R):
    newfreq = [{} for i in range(1 << C)]
    for cur in range(1 << C):
        for prev in range(1 << C):
            change = inner[cur] + sum(2 * int(d) - 1 for d in f"{cur ^ prev:0{C}b}")
            for biodiv, ways in freq[prev].items():
                newfreq[cur][biodiv + change] = newfreq[cur].get(biodiv + change, 0) + ways
    freq = newfreq

def countWays(freq, target):
    return sum(freq[i].get(target, 0) for i in range(1 << C))

assert sum(sum(dic.values()) for dic in freq) == 2 ** (R * C)
for i in range(-(R * (C - 1) + (R - 1) * C), R * (C - 1) + (R - 1) * C + 1):
    ways = countWays(freq, i)
    if ways:
        print(i, "->", ways)