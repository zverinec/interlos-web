import math
import itertools

class TDMap:

	def __init__(self, map, towers):
		self.map = map
		self.width = len(map[0])
		self.height = len(map)
		self.towers = towers

	def findOptimalSolution(self):
		totalMaxDamage = 0
		totalOptimal = None

		for towers in self.towerPermutations(self.towers):
			maxDamage = 0
			optimal = None
			towersPositions = []

			for i in range(len(towers)):
				towersPositions.append(None)
				for pos in self.towerPositionGenrator():
					towersPositions[i] = (towers[i], pos)

					damage = self.calcDamage(towersPositions)
					if(damage > maxDamage):
						maxDamage = damage
						optimal = list(towersPositions)

				if not optimal:
					break

				towersPositions[i] = optimal[i]
				
			if maxDamage > totalMaxDamage:
				totalMaxDamage = maxDamage
				totalOptimal = optimal

		return (totalMaxDamage, totalOptimal)

	def towerPositionGenrator(self):
		pos = (0, 0)
		while(pos[0] != self.width - 1 or pos[1] != self.height - 1):
			yield pos
			pos = self.movePos(pos)
	
	def movePos(self, pos):
		x = pos[0]+1
		y = pos[1]
		if x >= self.width:
			y += 1
			x = 0
		if y >=  self.height:
			x = 0
			y = 0
		return (x, y)

	def calcDamage(self, towers):
		totalDamage = 0

		for t1 in xrange(len(towers)):
			# Cant place on road
			if self.map[towers[t1][1][1]][towers[t1][1][0]]:
				return 0
			# Cant place two towers on same spot
			for t2 in xrange(len(towers)):
				if t1 != t2 and towers[t1][1] == towers[t2][1]:
					return 0

		for y in range(self.height):
			for x in range(self.width):
				damage = 0
				slowdown = 1
				if self.map[y][x]:
					for tower in towers:
						if tower[0] == 'T':
							damage += self.calcTowerDamage(tower[1], (x, y))
						if tower[0] == 'S':
							slowdown *= self.calcTowerDamageSlowdown(tower[1], (x, y))
				totalDamage += damage * slowdown;

		return totalDamage;

	def calcTowerDamage(self, towerPos, pos):
		dx = towerPos[0] - pos[0];
		dy = towerPos[1] - pos[1];
		return 1 if round(math.sqrt(dx*dx+dy*dy)) <= 2 else 0;

	def calcTowerDamageSlowdown(self, towerPos, pos):
		dx = towerPos[0] - pos[0];
		dy = towerPos[1] - pos[1];
		return 2 if round(math.sqrt(dx*dx+dy*dy)) <= 1 else 1;

	def towerPermutations(self, towers):
		return set(itertools.permutations(towers))

def parseMap(string):
	return map(lambda line: map(lambda c: True if c == "#" else False, line), string.split("\n"))

map = parseMap("""...............
...............
...............
.........###...
.........#.##..
.........#..#..
........##..##.
.......##....#.
......##.....##
.....##........
.....#.........
.....#.........
.....##........
......#####....
..........#....""")

tdMap = TDMap(map, ['T', 'T', 'T', 'S'])
result = tdMap.findOptimalSolution()
print "Maximum possible damage is", result[0], "when towers are placed at ", result[1]
