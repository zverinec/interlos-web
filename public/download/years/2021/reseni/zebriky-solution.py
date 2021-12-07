# Solver that will return all best paths, not just first
# Parameters:
#   * board - dictionary with interesting spots on board
#   * board_size - total number of spots on board
#   * position - current piece position
#   * steps - previous steps of piece
#   * step_size - how for can piece reach
#   * best_length - what is current best attempt at solving
def solve_recursive(board, board_size, position, steps, step_size, best_length):
    # If we are worse than current best
    if len(steps) > best_length:
        return []

    # If we are not on a free spot then we must follow the ladder or a snake
    if position in board and board[position] != 0:
        return solve_recursive(board, board_size, board[position], steps, step_size, best_length)

    # If we stepped on moose, higher our moving capability
    if position in board and board[position] == 0:
        step_size += 1

    # If we are close to end
    steps_remaining = board_size - position
    if step_size >= steps_remaining:
        current_steps = steps.copy()
        current_steps.append(steps_remaining)
        return [current_steps]

    # Else try all move possibilities
    result = []
    for step in range(1, step_size + 1):
        steps.append(step)  # Try current step
        solutions = solve_recursive(board, board_size, position + step, steps, step_size, best_length)
        steps.pop()  # Remove current step for next try
        score = len(solutions[0]) if len(solutions) > 0 else 0
        if score == best_length and score > 0:  # Solutions are as good as our best
            result.extend(solutions)
        elif score < best_length and score > 0:  # Solutions are better than our best
            result = solutions
            best_length = score
    return result


# Solve Snakes Ladders Mooses Problem
def solve():
    # Define board as a dictionary, all interesting positions are saved as keys,
    # their values are either 0 meaning it is a moose or next position if it is
    # a ladder or a snake. Blank spots are from dictionary omitted.
    board = {
        2: 0, 3: 18, 4: 1, 6: 23,
        11: 0, 14: 0, 15: 30,
        21: 0, 22: 7,
        26: 12, 27: 39, 29: 0,
        34: 49, 35: 19,
        37: 0, 38: 53,
        40: 24, 45: 42,
        48: 32,
        50: 0, 51: 0,
    }

    # We know, that path with 24 steps is possible, let's try to find shorter
    solutions = solve_recursive(board, 55, 0, [], 1, 24)
    print('All solutions:', solutions)

    # From all shortest paths, find the one with highest score
    minimum = sum(solutions[0])
    for solution in solutions[1:]:
        score = sum(solution)
        if score < minimum:
            minimum = score
    return minimum


# Print the result
print('Result:', solve())
