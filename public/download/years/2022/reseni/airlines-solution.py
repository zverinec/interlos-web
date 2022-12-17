HOME = "BRQ"
MID = "AKL"
PAUSE = "HNL"
PAUSE_LENGTH = 1440


def get_index(code):
    return 26 * 26 * (ord(code[0]) - ord("A")) + 26 * (ord(code[1]) - ord("A")) + ord(code[2]) - ord("A")


def solve(lines, pause_length=PAUSE_LENGTH):
    conn = [(get_index(f), get_index(t), int(start), int(end), int(price))
            for f, t, start, end, price in lines]
    for f, t, start, end, price in lines:
        if t == PAUSE:
            conn.append((get_index(f), get_index(t), int(start), int(end) + pause_length, int(price)))
    cheapest = [[[] for i in range(26 ** 3)] for i in range(4)]
    cheapest[0][get_index(HOME)].append((0, 0))
    conn.sort(key=lambda t: t[3])
    
    for f, t, start, end, price in conn:
        for i in range(4):
            startPrices = cheapest[i][f]
            j = i
            if t == get_index(MID):
                j |= 2
            if t == get_index(PAUSE) and end - start > pause_length:
                j |= 1
            lo = 0
            hi = len(startPrices) - 1
            while hi >= 0 and lo < hi:
                mid = (lo + hi + 1) // 2
                if startPrices[mid][0] > start:
                    hi = mid - 1
                else:
                    lo = mid
            if hi < 0: #unreachable at this flight's start time
                continue
            newPrice = startPrices[lo][1] + price
            if not cheapest[j][t] or newPrice < cheapest[j][t][-1][1]:
                if cheapest[j][t] and cheapest[j][t][-1][0] == end:
                    cheapest[j][t].pop()
                cheapest[j][t].append((end, newPrice))
    
    results = cheapest[3][get_index(HOME)]
    return None if not results else results[-1][1]

m = int(input())
conn = [input().split() for i in range(m)]
print(solve(conn))