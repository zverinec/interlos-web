def solve(points):
	lines = {}
	atX = {}
	for x, y, m in points:
		atX.setdefault(x, []).append((x, y, m))
	for i, (x1, y1, v1) in enumerate(points):
		for (x2, y2, v2) in points[:i]:
			if x1 != x2:
				slope = (y2 - y1) / (x2 - x1)
				offset = y1 - x1 * slope
				s = lines.setdefault((slope, offset), set())
				s.add((x1, y1, v1))
				s.add((x2, y2, v2))
	best = 0
	for dic in [atX, lines]:
		for pts in dic.values():
			suma = sum(m for x, y, m in pts)
			best = max(best, suma)
	return best


if __name__ == "__main__":
	filename = "line.in"
	with open(filename) as f:
		points = [[int(x) for x in line.split()] for line in f]
	print(solve(points))