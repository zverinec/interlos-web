ROOM_COUNT = 123
MOOSE_COUNT = 1234

moose_locations = [0] * MOOSE_COUNT
who_knows = [False] * MOOSE_COUNT
who_knows[0] = True


def step():
    global moose_locations, who_knows
    for i in range(MOOSE_COUNT):
        moose_locations[i] = (moose_locations[i] + i % 12) % ROOM_COUNT + 1

    new_who_knows = who_knows[:]
    for i in range(MOOSE_COUNT):
        if not who_knows[i]:
            continue

        for j in range(MOOSE_COUNT):
            if i == j:
                continue

            if not new_who_knows[j] and moose_locations[i] == moose_locations[j]:
                new_who_knows[j] = True
                break

    who_knows = new_who_knows


steps = 0
while not all(who_knows):
    steps += 1
    step()

print(steps)
