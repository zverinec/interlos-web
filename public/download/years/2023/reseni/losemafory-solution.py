light_directions = {"-i": 0, "+i": 1, "-j": 2, "+j": 3}
free_moose_indices = {"i": (0, 1), "j": (2, 3)}
ds_to_arrival_direction = {(1, 0): "-i", (-1, 0): "+i", (0, 1): "-j", (0, -1): "+j"}
goal_ds = {"-i": (-1, 0, "+i"), "+i": (1, 0, "-i"), "-j": (0, -1, "+j"), "+j": (0, 1, "-j")}

def advance_lights(lights):
    for j in range(len(lights)):
        for i in range(len(lights[0])):
            light = lights[j][i][0]
            if light[0] == light[1] + 1:
                #print(i, j, light[0], light[1], light[2])
                lights[j][i][0][1] = 0
                lights[j][i][0][2] = "i" if light[2] == "j" else "j"
            else:
                lights[j][i][0][1] += 1


def get_preferences(ds):
    di, dj = ds
    preferences = []
    if di < 0:
        preferences.append((-1, 0))
    if di > 0:
        preferences.append((1, 0))
    if dj < 0:
        preferences.append((0, -1))
    if dj > 0:
        preferences.append((0, 1))
    return preferences

def move_single_moose_on_light(dir_id, cur_i, cur_j, lights, moose_goals, moved_this_turn):
    # does not check if light is green in the direction
    light = lights[cur_j][cur_i]
    moose_id = light[1][dir_id]
    if moose_id == -1 or moose_id in moved_this_turn:
        return
    goal_light_a = moose_goals[moose_id]
    da = (goal_light_a[0] - cur_i, goal_light_a[1] - cur_j)
    preferences = get_preferences(da)
    if len(preferences) == 0:
        goal_di, goal_dj, goal_dir = goal_ds[moose_goals[moose_id][2]]
        try:
            goal_light = lights[cur_j + goal_dj][cur_i + goal_di]
            if goal_light[1][light_directions[goal_dir]] != -1:   # moose can't get to goal as the road is full
                #print("moose", moose_id, "can't get to goal as roaad is full")
                return
        except:
            print("an unexpected error occured")
        lights[cur_j][cur_i][1][dir_id] = -1
        moose_goals[moose_id] = None
        #print("moose", moose_id, "got to goal")
    for di, dj in preferences:
        arrival_dir_id = light_directions[ds_to_arrival_direction[(di, dj)]]
        if lights[cur_j + dj][cur_i + di][1][arrival_dir_id] == -1:
            lights[cur_j + dj][cur_i + di][1][arrival_dir_id] = moose_id
            lights[cur_j][cur_i][1][dir_id] = -1
            closest_ds = da
            #print("moose", moose_id, "moves from", cur_i, cur_j, "to", cur_i + di, cur_j + dj, "(closest goal ", cur_i + closest_ds[0], cur_j + closest_ds[1], ")")
            moved_this_turn.add(moose_id)
            return

def move_all_moose_on_light(cur_i, cur_j, lights, moose_goals, moved_this_turn):
    free_dirs = free_moose_indices[lights[cur_j][cur_i][0][2]]
    for dir_id in free_dirs:
        move_single_moose_on_light(dir_id, cur_i, cur_j, lights, moose_goals, moved_this_turn)


with open("semafory.txt") as file:
    i, j = list(map(int, file.readline().split(" ")))
    lights = []
    for rown in range(j):
        rawrow = file.readline().rstrip("\n").split(" ")
        row = []
        for coln in range(i):
            rawdata = rawrow[coln]
            light_data = [list(map(int, rawdata.split(","))) + ["i"], [-1, -1, -1, -1]]
            # the list is of id of waiting moose in order -i dir, +i dir, -j dir, +j dir
            row.append(light_data)
        lights.append(row)
    m = int(file.readline().rstrip("\n"))
    moose_goals = []
    for moose_id in range(m):
        rawdata = file.readline().rstrip("\n").split(" ")
        lights[int(rawdata[1])][int(rawdata[0])][1][light_directions[rawdata[2]]] = moose_id
        goal_a = (int(rawdata[3]), int(rawdata[4]), rawdata[5])
        moose_goals.append((goal_a))


t = 0
moved_this_turn = set()
while len(list(filter(lambda m: m != None, moose_goals))) > 0:
    #print("turn", t + 1)
    for cur_j in range(len(lights)):
        for cur_i in range(len(lights[0])):
            move_all_moose_on_light(cur_i, cur_j, lights, moose_goals, moved_this_turn)
    advance_lights(lights)
    t += 1
    moved_this_turn = set()
print("finished simulation in", t)


