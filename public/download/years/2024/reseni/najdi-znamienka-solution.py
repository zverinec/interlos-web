def solveImpl(arr, i, res, path):
    if sum(arr[:i + 1]) > res:
        return
    if i == 0:
        if arr[0] == res:
            yield path[::-1]
        return
    if res % arr[i] == 0:
        path.append("*")
        yield from solveImpl(arr, i - 1, res // arr[i], path)
        path.pop()
    if res - arr[i] > 0:
        path.append("+")
        yield from solveImpl(arr, i - 1, res - arr[i], path)
        path.pop()

def solve(arr, res):
    path = []
    return len(list(solveImpl(arr, len(arr) - 1, res, path)))

print(solve([7, 3, 4, 3, 5, 3, 3, 7, 4, 5, 3, 4, 4, 2, 3, 6, 2, 3, 5, 2, 4, 5, 6, 2, 2, 4, 5, 4, 4, 3, 4, 5, 5, 4, 3, 5, 3, 3, 2, 3, 2, 3, 4, 5, 2, 4, 3, 3, 6, 4, 2, 3, 4, 7, 2, 3, 5, 4, 4, 6], 5632358))
