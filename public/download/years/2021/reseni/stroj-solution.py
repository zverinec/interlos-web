# Spustte python stroj-solution.py cesta_k_programu.los cesta_ke_vstupu.txt
# program je zaroven prevodnikem vstupu do kodovani pro stroj (s argumentem
# geninput), tato cast se nachazi za funkci main a nebyla relevantni k reseni.

from __future__ import annotations
from typing import List, Set, Tuple, Dict, Optional
from collections import defaultdict
import sys
import re
import enum

class Dir(enum.Enum):
	Left = -1
	Stay = 0
	Right = 1

	@staticmethod
	def parse(val: str) -> Dir:
		if val == 'L':
			return Dir.Left
		if val == 'R':
			return Dir.Right
		if val == 'S':
			return Dir.Stay
		assert False, "unreachable"

class Mode(enum.Enum):
	All = enum.auto()
	Exists = enum.auto()
	Accept = enum.auto()
	Reject = enum.auto()

	@staticmethod
	def parse(val: str) -> Mode:
		return {'A': Mode.All,
				'E': Mode.Exists,
				'a': Mode.Accept,
				'n': Mode.Reject
				}[val]


Rule = Tuple[Tuple[str, str],
			 Mode,
			 List[Tuple[int, str, str, Dir, Dir]]]
Rules = Dict[int, List[Rule]]


class Machine:
	def __init__(self, rules: Rules) -> None:
		self.rules = rules

	@staticmethod
	def get(tape: List[str], idx: int) -> str:
		if idx < len(tape):
			assert idx >= 0, f"{idx} < 0"
			return tape[idx]
		return '_'

	@staticmethod
	def set(tape: List[str], idx: int, val: str) -> None:
		if idx == len(tape):
			tape.append(val)
		tape[idx] = val

	@staticmethod
	def match(key, tape: List[str], aidx: int, bidx: int) -> bool:
		a, b = key
		return (a == '*' or Machine.get(tape, aidx) == a) \
			and (b == '*' or Machine.get(tape, bidx) == b)

	def _apply(self, act, tape: List[str], aidx: int, bidx: int) -> Tuple[int, int, int, List[str]]:
		dst, wa, wb, ma, mb = act

		if wa != '*':
			Machine.set(tape, aidx, wa)
		if wb != '*':
			Machine.set(tape, bidx, wb)

		return (dst, aidx + ma.value, bidx + mb.value, tape)

	def execute(self: Machine, st: int, aidx: int, bidx: int,
				tape: List[str]) -> bool:
		while True:
			# START:
			matched = False
			for key, mode, acts in self.rules[st]:
				if Machine.match(key, tape, aidx, bidx):
					matched = True
					try:
						if mode == Mode.Accept:
							return True
						if mode == Mode.Reject:
							return False

						# work around small recursion limit in python
						if len(acts) == 1:
							st, aidx, bidx, tape = self._apply(acts[0], tape.copy(), aidx, bidx)
							break  # goto START

						if mode == Mode.All:
							agg = all
						elif mode == Mode.Exists:
							agg = any

						return agg(map(lambda act: self.execute(
													*self._apply(act, tape.copy(), aidx, bidx)),
									   acts))
					except:
						print(f"{st} {aidx} {bidx} → {key} {mode} {acts} ({tape})")
						raise
			assert matched, f"No match for {st} {Machine.get(tape, aidx)} {Machine.get(tape, bidx)} ({aidx} {bidx} {tape})"


	def __call__(self: Machine, data: str) -> bool:
		tape = ['>'] + list(data)
		return self.execute(0, 0, 0, tape)


def parse_machine(handle) -> Machine:
	rules = defaultdict(list)
	for line in filter(None, map(str.strip, handle.readlines())):
		key, right = line.split(':')
		mode, *acts = right.strip().split(' ')

		st, a, b = key.split(',')
		racts = []
		for dst, wa, wb, ma, mb in map(lambda x: x.strip().split(','), acts):
			racts.append((int(dst), wa, wb, Dir.parse(ma), Dir.parse(mb)))

		rules[int(st)].append(((a, b), Mode.parse(mode), racts))
	return Machine(rules)

def main():
	if len(sys.argv) <= 2:
		print(f"usage: python {sys.argv[0]} program.los input.txt")
		return
	with open(sys.argv[1]) as h_machine:
		machine = parse_machine(h_machine)

	out = ""
	with open(sys.argv[2]) as h_inputs:
		for line in map(str.strip, h_inputs.readlines()):
			res = machine(line)
			print(f"{line} → {res}", file=sys.stderr)
			out += str(int(res))

		print(out)


QCNF = Tuple[Dict[str, str], List[List[str]]]


def un(n: int) -> str:
	return ''.join(['a' for _ in range(n + 1)])


def read_cnf(line: str) -> QCNF:
	print(line, file=sys.stderr)
	quals, cnf = line.split('.')
	vars_ = re.findall(r'[∀∃][a-z]+', quals)
	out_quals = dict(map(lambda x: (x[1:], x[0]), vars_))

	out_cnf = []
	for cube in map(lambda x: x.strip('()'), map(str.strip, cnf.split('∧'))):
		out_cnf.append(list(map(str.strip, cube.split('∨'))))
	# print(quals, vars_, out_quals, out_cnf, file=sys.stderr)
	return out_quals, out_cnf



def format_cnf(qcnf: QCNF) -> str:
	quals, cnf = qcnf
	varidxs = dict(map(lambda t: (t[1], t[0]), enumerate(quals.keys())))
	# print(varidxs, file=sys.stderr)
	out = ""
	for cube in cnf:
		out += "k"
		for lit in cube:
			if lit.startswith("¬"):
				out += f"s{un(varidxs[lit[1:]])}"
			else:
				out += f"d{un(varidxs[lit])}"
	for qual in quals.values():
		if qual == '∀':
			out += "v"
		else:
			assert qual == '∃'
			out += "n"
	return out

def geninput():
	for line in map(str.strip, sys.stdin.readlines()):
		out = format_cnf(read_cnf(line.split(' ', 1)[1]))
		print(f"{line} → {out}", file=sys.stderr)
		print(out)


if __name__ == "__main__":
	if sys.argv[1:2] == ["geninput"]:
		geninput()
	else:
		main()
