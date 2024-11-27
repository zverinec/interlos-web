
def count(n):
	fact = [1]
	s = bin(n)[2:]
	m = len(s)
	pascal = [[1]]
	for i in range(1, m+1):
		pascal.append([1] + [x + y for x, y in zip(pascal[-1], pascal[-1][1:])] + [1])
	result = sum(pascal[i - 1][2 * i // 3 - 1] for i in range(3, m, 3))
	if m % 3 == 0:
		need = 2 * m // 3 - 1
		for i in range(1, m):
			if s[i] == "1":
				if need >= 0 and 0 <= m - i - 1 - need:
					result += pascal[m - i - 1][need]
				need -= 1
		result += need == 0
	return result


for n in [12345678987654321, 100, 50]:
	print("There are", count(n), "good numbers <=", n)
