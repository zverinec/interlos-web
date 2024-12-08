ROWS = 5


class Piece:
    def __init__(self, letter, lines):
        self.letter = letter
        if type(lines[0]) == str:
            self.intervals = [(line.index("#"), line.rindex("#") + 1) for line in lines]
        else:
            self.intervals = list(lines)
        self.area = sum(b - a for a, b in self.intervals)


def solveImpl(rowsFilled, pieces, free, letters):
    if not pieces:
        assert free == 0
        print("".join(letters))
        return True
        #return False -- use this instead not to stop at first solution - to check there is only one possibility
    for piece in pieces:
        offset = max(filled - a for filled, (a, b) in zip(rowsFilled, piece.intervals))
        empty = sum(offset + a - filled for filled, (a, b) in zip(rowsFilled, piece.intervals))
        if empty > free:
            continue
        newFilled = [offset + b for a, b in piece.intervals]
        letters.append(piece.letter)
        if solveImpl(newFilled, pieces - {piece}, free - empty, letters):
            return True
        letters.pop()
    return False


def loadInput(filename):
    with open(filename) as f:
        inp = iter(f)
        columns = int(next(f))
        nPieces = int(next(f))
        pieces = [Piece(next(f).strip(), [next(f).strip() for j in range(ROWS)])
                  for i in range(nPieces)]
    return columns, pieces


def solve(columns, pieces):
    solution = []
    free = columns * ROWS - sum(p.area for p in pieces)
    if not solveImpl([0] * ROWS, set(pieces), free, solution):
        return "(No solution)"
    return "".join(solution)


print(solve(*loadInput("dlazdenie.in")))
