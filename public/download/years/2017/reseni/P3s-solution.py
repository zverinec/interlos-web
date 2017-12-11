#!/usr/bin/env python3

# run as ./P3-solution.py in the directory with the input files
# (or python3 P3-solution.py)


def load(file):
    rules = {}
    with open(file, 'r') as h:
        rules_done = False
        for line in h.readlines():
            if line.strip() == "":
                rules_done = True
                continue
            if not rules_done:
                l, r = line.split(':')
                st_from, char_from = l.split(',')
                st_to, char_to, direct = r.split(',')
                rules[(int(st_from.strip()), char_from.strip())] = \
                        (int(st_to.strip()), char_to.strip(), direct.strip())
            else:
                return (rules, list(line.strip()))


def sim(rules, tape):
    seen = set()

    p = 0
    st = 0
    i = 0
    while True:
        v = tape[p]
        if (st, v) not in rules:
            print("end after %d iterations (%d, %s)" % (i, st, v))
            return ''.join(tape)
        st, w, d = rules[st, v]
        tape[p] = w
        if d == 'L':
            p -= 1
        else:
            if p + 1 == len(tape):
                tape.append('_')
            p += 1
        conf = (st, p, ''.join(tape))
        if conf in seen:
            print("fix after %d iterations" % i)
            return ''.join(tape)
        seen.add(conf)
        i += 1

if __name__ == "__main__":
    r1, i1 = load("P3-sekvence-a.txt")
    print(sim(r1, i1))

    r2, i2 = load("P3-sekvence-b.txt")
    print(sim(r2, i2))
