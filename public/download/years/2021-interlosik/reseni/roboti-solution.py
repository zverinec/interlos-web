import json

iteration_number = 0


def get_next_position_r1(current_position):
    return [current_position[0] + 1, current_position[1]]


def get_next_position_r2(current_position):
    return [current_position[0], current_position[1] + 1]


def get_next_position_r3(current_position):
    return [current_position[0] - 1, current_position[1] + 1]


def get_next_position_r4(current_position):
    if iteration_number % 2 == 0:
        return [current_position[0], current_position[1] - 1]
    else:
        return [current_position[0] - 1, current_position[1]]


def get_next_position_r5(current_position):
    if iteration_number % 4 == 0:
        return [current_position[0], current_position[1] - 1]
    elif iteration_number % 2 == 1:
        return [current_position[0] - 1, current_position[1]]
    else:
        return [current_position[0], current_position[1] + 1]


file = open("robots.json")
data = json.load(file)

printer_squares_positions = data["printer-squares"]
robot_positions = data["robots"]
robot_move_definitions = {"r1": get_next_position_r1, "r2": get_next_position_r2, "r3": get_next_position_r3,
                          "r4": get_next_position_r4, "r5": get_next_position_r5}
robot_stop_flags = {"r1": False, "r2": False, "r3": False, "r4": False, "r5": False}


def are_any_robots_moving():
    for robot_stop_flag in robot_stop_flags:
        if not robot_stop_flags[robot_stop_flag]:
            return True
    return False


def is_position_out_of_boundaries(position):
    return position[0] < 1 or position[0] > 100 or position[1] < 1 or position[1] > 100


def is_position_occupied(position):
    for robot in robot_positions:
        if robot_positions[robot] == position:
            return True
    return False


def is_position_of_printer_square(position):
    for printer_square_position in printer_squares_positions:
        if printer_square_position == position:
            return True
    return False


def run_simulation():
    global iteration_number
    while are_any_robots_moving():
        for robot in robot_positions:
            if not robot_stop_flags[robot]:
                position = robot_positions[robot]
                next_position = robot_move_definitions[robot](position)
                if is_position_out_of_boundaries(next_position) or is_position_occupied(next_position):
                    robot_stop_flags[robot] = True
                else:
                    robot_positions[robot] = next_position
                    if is_position_of_printer_square(next_position):
                        print(robot[-1], end="", flush=True)

        iteration_number += 1


run_simulation()
