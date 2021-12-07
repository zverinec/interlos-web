# spustte jako python bludiste-solution.py ve slozce se souborem bludiste.txt
# nebo tomu dejte jako argument cestu k souboru. Pro jine slovo dodejte druhy
# argument: python bludiste-priklad.txt losi

from typing import List, Tuple, Set
import sys

alfabet = "INTERLOS" if len(sys.argv) < 3 else sys.argv[2].upper()


def parse() -> List[List[str]]:
    with open(r'bludiste.txt' if len(sys.argv) < 2 else sys.argv[1]) as f:
        lines = [[s for s in line.rstrip('\n\r')] for line in f.readlines()]
    return lines


def is_correct(letters: List[str]) -> bool:
    for letter in alfabet:
        if letters.count(letter) > alfabet.count(letter):
            return False
    return True


def is_accepted(letters: List[str]) -> bool:
    for letter in alfabet:
        if letters.count(letter) != alfabet.count(letter):
            return False
    return True


def in_bound(row: int, col: int, lines: List[List[str]]) -> bool:
    return row >= 0 and row < len(lines) and col >= 0 and col < len(lines[row]) and lines[row][col] != '#'


def solve(
  data: List[List[str]],
  visited: Set[Tuple[int, int]],
  remainder: List[str],
  row: int,
  col: int
) -> List[Tuple[int, int]]:
    if not is_correct(remainder):
        return []
    if row == len(data) - 1 and col == len(data[0]) - 1:
        return [(row, col)]
    if is_accepted(remainder):
        remainder = []
    visited.add((row, col))
    revert_changes: List[Tuple[int, int, str]] = []
    directions: List[Tuple[int, int]] = [(-2, 0), (0, 2), (2, 0), (0, -2)]
    for (diff_row, diff_column) in directions:
        new_row = row + diff_row
        new_col = col + diff_column
        peek_row = row + diff_row // 2
        peek_col = col + diff_column // 2
        if not in_bound(new_row, new_col, data) \
          or data[peek_row][peek_col] == '#' \
          or (new_row, new_col) in visited:
            continue
        remainder.append(data[new_row][new_col].upper())
        prev = remainder.copy()
        path = solve(data, visited, remainder, new_row, new_col)
        if path:
            path.append((row, col))
            return path
        else:
            remainder = prev
            remainder.pop()
            revert_changes.append((peek_row, peek_col, data[peek_row][peek_col]))
            data[peek_row][peek_col] = '#'
    visited.remove((row, col))
    for change_row, change_col, value in revert_changes:
        data[change_row][change_col] = value
    return []


maze = parse()
solution = solve(maze, {(0, 0)}, [alfabet[0]], 0, 0)
print('Full Path:', "".join(maze[i][j] for (i, j) in reversed(solution)), end='\n\n')
print('Solution:', "".join(maze[i][j] for (i, j) in reversed(solution) if maze[i][j].isupper()))
