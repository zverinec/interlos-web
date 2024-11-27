class Atom(object):
	def __init__(self, t):
		self.type = t
		self.bonds = 0

valences = {
	'C': [4],
	'c': [3],
	'N': [3, 5],
	'O': [2],
	'P': [3, 5],
	'S': [2, 4, 6]
}

bonds = {
	'-': 1,
	'=': 2,
	'#': 3
}


def countHs(smiles):
	paren_stack = []

	last_atom = Atom(smiles[0])
	last_bond = 0

	atoms = []
	atoms.append(last_atom)
	# 0 - get atom
	# 1 - get number
	# 2 - get branch
	# 3 - get bond

	status = 1
	i = 0
	while (i+1) < len(smiles) and smiles[i+1] != '\n':
		i += 1
		c = smiles[i]

		# Atom

		if status == 0 and c in valences:
			last_atom = Atom(c)
			atoms.append(last_atom)
			last_atom.bonds += last_bond
			status = 1
			continue

		# Cisla
		# u tech je nam uplne jedno, kam vedou

		if status == 1:
			if c == '%':
				i += 2  # preskocime dvojcisli
				last_atom.bonds += 1
				continue
			else:
				status = 2

		# Odbocky

		if status == 2:
			if c == '(':
				paren_stack.append(last_atom)
				continue
			else:
				status = 3

		# Vazba / konec odbocky

		if status == 3:
			if c == ')':  # je to konec zavorky
				last_atom = paren_stack.pop()
				status = 2  # hledame dalsi odbocku
				continue
			else:  # je to vazba
				last_bond = bonds[c]
				last_atom.bonds += last_bond
			status = 0
			continue

	# Vypocitame pro kazdy atom jeho pocet vodiku
	hs = 0
	for a in atoms:
		v = [x for x in valences[a.type] if x >= a.bonds][0]
		hs += v - a.bonds

	return hs

line = "x"
while line != "":
	line = raw_input()
	print(countHs(line))
