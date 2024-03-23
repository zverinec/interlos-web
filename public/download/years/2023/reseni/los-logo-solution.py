BIG = 20
def load(fil, l_width, l_height):
    with open(fil, encoding="UTF-8") as file:
        los = []
        for line in file:
            los.append(line[:-1].split(";"))

    # transform array
    los = [[los[j][i] for j in range(len(los))] for i in range(len(los[0]))]

    # yield los #for drawing

    # expanding display to real form
    los = [[los[i//BIG][j//BIG] for j in range(len(los[0])*BIG)] for i in range(len(los)*BIG)]
    
    # "scaling" logo
    for i in range(l_width, len(los)):
        for j in range(l_height, len(los[0])):
            if los[i][j] == "X":
                for ii in range(1, l_width + 1):
                    if los[i-ii][j] == "X":
                        break
                    los[i-ii][j] = "X"
    for i in range(l_width, len(los)):
        for j in range(l_height, len(los[0])):
            if los[i][j] == "X":
                for jj in range(1, l_height + 1):
                    if los[i][j-jj] == "X":
                        break
                    los[i][j-jj] = "X"

    # yield los #for drawing
    return los

def direct(v):
    if v > 0:
        return 1
    return -1

def simulate(los, x, y, v_x, v_y):
    if v_x < 0: # if v_x is negative, it theoretically starts in the cell before
        x -= 1
    if v_y < 0: # if v_y is negative, it theoretically starts in the cell before
        y -= 1
    x = (x, 1) # x + 1/v_y
    y = (y, 1) # y + 1/v_x
    start = x, y, v_x, v_y
    bounces = 0
    while True:
        # yield (x, y, v_x, v_y, bounces)
        if x[1] == abs(v_y) and y[1] == abs(v_x): # 
            if los[x[0]+direct(v_x)][y[0]+direct(v_y)] == "X": # XX     _X
                # two situations can be counted as a corner:     LX and L_, L is logo
                if los[x[0]+direct(v_x)][y[0]] == los[x[0]][y[0]+direct(v_y)]:
                    # Change made only for making assignment
                    x = (x[0] + 1 if v_x > 0 else x[0], 0) # opposite operation as in initialization
                    y = (y[0] + 1 if v_y > 0 else y[0], 0) # opposite operation as in initialization
                    v_x *= -1
                    v_y *= -1
                    # yield (x, y, v_x, v_y, bounces)
                    return bounces, x, y, v_x, v_y
                elif los[x[0]+direct(v_x)][y[0]] == "X": # vertical bounce
                    v_x *= -1
                    bounces += 1
                    x = (x[0], 0)
                    y = (y[0] + direct(v_y), 0)
                elif los[x[0]][y[0]+direct(v_y)] == "X": # horizontal bounce
                    v_y *= -1
                    bounces += 1
                    x = (x[0] + direct(v_x), 0)
                    y = (y[0], 0)
            else: # we operate with logo with zero size, so it can "sneak" through empty corner: X_ XL
                x = (x[0] + direct(v_x), 0)                                                  # LX _X
                y = (y[0] + direct(v_y), 0)

        elif x[1] == abs(v_y):
            if los[x[0]+direct(v_x)][y[0]] == "X": # vertical bounce
                v_x *= -1
                bounces += 1
                x = (x[0], 0)
            else:
                x = (x[0] + direct(v_x), 0)
        elif y[1] == abs(v_x):
            if los[x[0]][y[0]+direct(v_y)] == "X": # horizontal bounce
                v_y *= -1
                bounces += 1
                y = (y[0], 0)
            else:
                y = (y[0] + direct(v_y), 0)

        if los[x[0]][y[0]] == "X":
            print(bounces, x, y, v_x, v_y, los[x[0]][y[0]])
            assert False

        x = (x[0], x[1] + 1)
        y = (y[0], y[1] + 1)
        if bounces > 50000 or (x, y, v_x, v_y) == start: #for checking error states
            return bounces, x, y, v_x, v_y, los[x[0]][y[0]]


los = load("logo.csv.", 43, 43)
print(simulate(los, 365, 342, -8, -5))
