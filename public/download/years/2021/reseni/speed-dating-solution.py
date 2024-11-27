from csv import reader
# python -m pip install pulp
from pulp import *

# Parse csv table with individual scores
def parse_csv(fname):
	 with open(fname) as file:
		data = reader(file, delimiter=',')
		girls = data.__next__()[1:]
		data = list(data)
		boys = [row[0] for row in data]
		data = {boy: {girl: int(score) for girl, score in zip(girls, row[1:])} for boy, row in zip(boys, data)}
		return (girls, boys, data)

# Create variables
(girls, boys, girls_assigned) = parse_csv('losice.csv')
(_, _, boys_assigned) = parse_csv('losi.csv')

# Create model
model = LpProblem(name="speeddating", sense=LpMaximize)

# Create pairs
pairs = LpVariable.dicts("Pairs", (boys, girls), lowBound=0, upBound=1, cat=const.LpBinary)

# Add contraint that no boy should have more girls assigned than one and vice versa
for boy, girl in zip(boys, girls):
	model += lpSum([pairs[boy][girl] for girl in girls]) == 1, f"{boy} must have only one girl"
	model += lpSum([pairs[boy][girl] for boy in boys]) == 1, f"{girl} must have only one boy"

# Objective function
total = []
for (boy, row) in pairs.items():
	# Constraint that no one should have score lower than 4
	model += lpDot([boys_assigned[boy][girl] for girl in row], [pairs[boy][girl] for girl in row]) >= 4, ""
	model += lpDot([girls_assigned[boy][girl] for girl in row], [pairs[boy][girl] for girl in row]) >= 4, ""
	# Total score should be as high as possible
	total.extend([boys_assigned[boy][girl] * pairs[boy][girl] for girl in row])
	total.extend([girls_assigned[boy][girl] * pairs[boy][girl] for girl in row])

# Constraint without variable creates objective function
model += lpSum(total)

# Solve using CBC algorithm provided by PuLP
model.solve(PULP_CBC_CMD(warmStart=True))
print(f"Max Score: {model.objective.value()}")