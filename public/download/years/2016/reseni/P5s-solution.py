n_tricks, rope_length, show_length, max_speed = 960, 20, 600, 5

# n_tricks[i][j] = a, v case i na miste j ziska los Os aplaus a
tricks = [[0 for j in range(rope_length + 1)] for i in range(show_length + 1)]
for i in range(n_tricks):
	time, place, applause = map(int, input().split())
	tricks[time][place] = applause

# d[i][j] = a, je-li v i-te sekunde na miste j
# ziska Os za prvnich i sekund v souctu aplaus a
d = [[0 for j in range(rope_length + 1)] for i in range(show_length + 1)]

# Los Os si vybere, kde chce zacit
for j in range(1, rope_length + 1):
	d[0][j] = tricks[0][j]

# V prvnich i sekundach
for i in range(1, show_length + 1):
	# je-li v i-te sekunde na miste j
	for j in range(1, rope_length + 1):
		# si vybere v jake pozici (v dosazitelne vzdalenosti)
		# mel nejlepsi zisk v minule sekunde
		nearby_applause = []
		for x in range(-max_speed, max_speed + 1):
			if 0 < j + x <= rope_length:
				nearby_applause.append(d[i-1][j + x])

		# a prida k nemu aplaus tricks[i][j]
		d[i][j] = max(nearby_applause) + tricks[i][j]

# K poslednimu triku si vybere misto, kde ziska celkove nejvetsi aplaus
print(max([d[show_length][i] for i in range(1, rope_length + 1)]))
