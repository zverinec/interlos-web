from itertools import product


PACKS = {
    "S": lambda n: n,
    "T": lambda n: 3 * n * n,
    "B": lambda n: n * n * n,
    "P": lambda n: n * (n + 1) * (n + 2) // 6,
    "K": lambda n: 2 ** n,
}


def edit(item):
    x, y, z = item.split()
    return int(x), y, int(z)


class LosConstraint:
    def __init__(self, line):
        self.left, self.right = [[edit(item) for item in data.split(" + ")] for data in line.split(" = ")]

    def eval(self, vars):
        total = 0
        for amount, pack, food in self.left:
            total += amount * PACKS[pack](vars[food - 1])
        for amount, pack, food in self.right:
            total -= amount * PACKS[pack](vars[food - 1])
        return total

    def __call__(self, *vars):
        return self.eval(vars) == 0


def load(filename):
    result = []
    with open(filename) as f:
        for line in f:
            if line:
                result.append(LosConstraint(line))
    return result


def solveFast4(constraints, n, limit):
    assert limit % 4 == 0
    mod4 = []
    for item in product(range(1, 5), repeat=n - 1):
        vars = (1, *item)
        if all(c.eval(vars) % 2 == 0 for c in constraints):
            mod4.append(item)
    print(len(mod4), "mod4")
    print("Solutions:")
    for m4 in mod4:
        for item in product(range(limit // 4), repeat=n - 1):
            vars = (1, *(4 * k + l for k, l in zip(item, m4)))
            if all(c(*vars) for c in constraints):
                print(vars)


def solveFast48(constraints, n, limit):
    mod4 = []
    for item in product(range(1, 5), repeat=n - 1):
        vars = (1, *item)
        if all(c.eval(vars) % 2 == 0 for c in constraints):
            mod4.append(item)
    print(len(mod4), "mod4")
    print("Solutions:")
    mod8 = []
    for m4 in mod4:
        for item in product(*[[1, 5, 9] if m == 1 else [m, m + 4] for m in m4]):
            vars = (1, *item)
            if all(c.eval(vars) % 4 == 0 for c in constraints):
                mod8.append(item)
    print(len(mod8), "mod8")
    for m8 in mod8:
        for item in product(*[[1] if m == 1 else range(m, limit + 1, 8) for m in m8]):
            vars = (1, *item)
            if all(c(*vars) for c in constraints):
                print(vars)


if __name__ == "__main__":
    filename = "losadnici.in"
    n = 10
    limit = 20
    constraints = load(filename)
    solveFast48(constraints, n, limit)
    print("Done")
    solveFast4(constraints, n, limit)