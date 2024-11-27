FILENAME = "photo.txt"

moose = [
	"# #	# #",
	"###	###",
	"  ######  ",
	"   ####   ",
]
for row in range(len(moose)):
	moose[row] = list(map(lambda x: x == "#", moose[row]))

photo = []
with open(FILENAME) as file:
	for row in file:
		photo.append(list(map(lambda x: x == "#", row[:-1])))

width = len(photo[0])
height = len(photo)

m_width = len(moose[1])
m_height = len(moose)


m_count = 0

for y in range(height - m_height + 1):
	for x in range(width - m_width + 1):
		good = 0
		for dy in range(m_height):
			for dx in range(m_width):
				if photo[y + dy][x + dx] == moose[dy][dx]:
					good += 1

		if good >= 30:
			m_count += 1

print(m_count)