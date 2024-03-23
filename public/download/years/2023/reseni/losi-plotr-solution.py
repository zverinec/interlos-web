def letter_to_shift(letter):
    if letter == "U":
        return -1, 0
    if letter == "D":
        return 1, 0
    if letter == "L":
        return 0, -1
    if letter == "R":
        return 0, 1
    assert False, "unreachable, but " + letter


def run(code, width):
    plot = []
    row, col = 0, 0
    for line in code:
        symbol, direction = line[0], line[1]

        assert 0 <= col < width, col
        if row >= len(plot):
            assert row == len(plot)
            plot.append([" " for _ in range(width)])

        plot[row][col] = symbol
        row_shift, col_shift = letter_to_shift(direction)
        row += row_shift
        col += col_shift
    return ["".join(line) for line in plot]


def run_from_file(filename, width):
    with open(filename) as f:
        return run(f.readlines(), width)


def symbol_to_letter(line):
    if line == "   # # ":
        return "#"
    if line == "       ":
        return " "
    if line == "#     #":
        return "U"
    if line == "#####  ":
        return "D"
    if line == "#      ":
        return "L"
    if line == "####   ":
        return "R"
    assert False, f"unreachable, but {line}"


LETTER_HEIGHT = 5
LETTER_WIDTH = 7


def extract_code(lines):
    result = []
    for i, line in enumerate(lines):
        if i % (LETTER_HEIGHT + 1) != 0:
            continue
        symbol, direction = (
            line[:LETTER_WIDTH],
            line[LETTER_WIDTH + 2 : LETTER_WIDTH + 2 + LETTER_WIDTH],
        )
        result.append(f"{symbol_to_letter(symbol)}{symbol_to_letter(direction)}")
    return result


PLOT_WIDTH = 80

if __name__ == "__main__":
    intermediate_plot = run_from_file("losi-plotr.txt", PLOT_WIDTH)
    intermediate_code = extract_code(intermediate_plot)
    result = run(intermediate_code, PLOT_WIDTH)
    print("\n".join(result))
