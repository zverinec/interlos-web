def find_path(game: list[list[str]], coord: list[int, int],
              goal: str, path: list[str], path_length: int,
              curr_shortest: int) \
              -> list[int, int, list[list[str]]]:
    y = coord[0]
    x = coord[1]
    output = [9999, 0, [path]]

    # UP
    if y > 0 and game[y - 2][x] not in path and \
       path_length + int(game[y - 1][x]) <= curr_shortest:
        adder = path_length + int(game[y - 1][x])

        if game[y - 2][x] == goal:
            if adder == curr_shortest:
                output = [adder, output[1] + 1, [path]]

            else:
                curr_shortest = adder
                output = [adder, 1, [path]]

        else:
            deeper = find_path(game, [y - 2, x], goal,
                               path + [game[y - 2][x]],
                               adder, curr_shortest)

            if deeper[0] <= curr_shortest:
                curr_shortest = deeper[0]

                if deeper[0] == output[0]:
                    output[1] += deeper[1]
                    output[2] += deeper[2]

                else:
                    output = deeper

    # RIGHT
    if x + 2 < len(game[0]) and game[y][x + 2] not in path and \
       path_length + int(game[y][x + 1]) <= curr_shortest:
        adder = path_length + int(game[y][x + 1])

        if game[y][x + 2] == goal:
            if adder == curr_shortest:
                output = [adder, output[1] + 1, [path]]

            else:
                curr_shortest = adder
                output = [adder, 1, [path]]

        else:
            deeper = find_path(game, [y, x + 2], goal,
                               path + [game[y][x + 2]],
                               adder, curr_shortest)

            if deeper[0] <= curr_shortest:
                curr_shortest = deeper[0]

                if deeper[0] == output[0]:
                    output[1] += deeper[1]
                    output[2] += deeper[2]

                else:
                    output = deeper

    # DOWN
    if y + 2 < len(game) and game[y + 2][x] not in path and \
       path_length + int(game[y + 1][x]) <= curr_shortest:
        adder = path_length + int(game[y + 1][x])

        if game[y + 2][x] == goal:
            if adder == curr_shortest:
                output = [adder, output[1] + 1, [path]]

            else:
                curr_shortest = adder
                output = [adder, 1, [path]]

        else:
            deeper = find_path(game, [y + 2, x], goal,
                               path + [game[y + 2][x]],
                               adder, curr_shortest)

            if deeper[0] <= curr_shortest:
                curr_shortest = deeper[0]

                if deeper[0] == output[0]:
                    output[1] += deeper[1]
                    output[2] += deeper[2]

                else:
                    output = deeper

    # LEFT
    if x > 0 and game[y][x - 2] not in path and \
       path_length + int(game[y][x - 1]) <= curr_shortest:
        adder = path_length + int(game[y][x - 1])

        if game[y][x - 2] == goal:
            if adder == curr_shortest:
                output = [adder, output[1] + 1, [path]]

            else:
                curr_shortest = adder
                output = [adder, 1, [path]]

        else:
            deeper = find_path(game, [y, x - 2], goal,
                               path + [game[y][x - 2]],
                               adder, curr_shortest)

            if deeper[0] <= curr_shortest:
                curr_shortest = deeper[0]

                if deeper[0] == output[0]:
                    output[1] += deeper[1]
                    output[2] += deeper[2]

                else:
                    output = deeper

    return output


def check(file: str) -> str:
    game: list[list[str]] = []
    messages: dict[str, int] = {}
    game_input = True

    with open(file) as contents:
        for line in contents:
            line = line.replace("\n", "")

            if line == '':
                game_input = False

            elif game_input:
                row = line.split(",")
                game.append(row)

            else:
                row = line.split(",")

                for elem in row:
                    elem = elem.split(":")
                    messages[elem[0]] = int(elem[1])

    affected_paths: list[list[str]] = []

    for start_goal, min_len in messages.items():
        coords = []
        goal_coords = []
        coords_dif = 0
        split_path = start_goal.split("/")

        for i in range(len(game)):
            if split_path[0] in game[i]:
                coords = [i, game[i].index(split_path[0])]
                break

        for i in range(len(game)):
            if split_path[1] in game[i]:
                goal_coords = [i, game[i].index(split_path[1])]
                break

        while coords[0] != goal_coords[0]:
            if goal_coords[0] < coords[0]:
                coords_dif += int(game[goal_coords[0] + 1][goal_coords[1]])
                goal_coords[0] += 2
            else:
                coords_dif += int(game[goal_coords[0] - 1][goal_coords[1]])
                goal_coords[0] -= 2

        while coords[1] != goal_coords[1]:
            if goal_coords[1] < coords[1]:
                coords_dif += int(game[goal_coords[0]][goal_coords[1] + 1])
                goal_coords[1] += 2
            else:
                coords_dif += int(game[goal_coords[0]][goal_coords[1] - 1])
                goal_coords[1] -= 2

        data = find_path(game, coords, split_path[1],
                         [split_path[0]], 0, coords_dif)

        if data[0] != min_len:
            affected_paths.append(data[2][0])

    bad_element = set(affected_paths[0]).intersection(set(affected_paths[1]))

    for i in range(2, len(affected_paths)):
        bad_element = bad_element.intersection(set(affected_paths[i]))

    return f"{bad_element.pop()}X{len(affected_paths)}"


print(check("./psanicka.csv"))
