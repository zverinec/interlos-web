FILENAME = "loscilatory_input.txt"

losc = []
matrix = []

with open(FILENAME) as file:
	for line in file:
		matrix.append([abs(int(x)) for x in line.split()])
		losc.append(0)

count = 0

while True:
	for i in range(len(losc)):
		losc[i] = losc[i] + 1
		for j in range(len(losc)):
			losc[i] += losc[j] * matrix[i][j]
		losc[i] = losc[i] % 3

	count += 1

	if not any(losc):
		break

print(count)
