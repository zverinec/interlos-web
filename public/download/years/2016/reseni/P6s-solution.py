import sys
from collections import defaultdict


def better(x, y):
	return len(sousedi[x]) > len(sousedi[y]) if len(sousedi[x]) != len(sousedi[y]) else x < y


def dedup(seq):
	seen = set()
	seen_add = seen.add
	return [x for x in seq if not (x in seen or seen_add(x))]


sousedi = defaultdict(lambda: set())

for line in sys.stdin:
	linka, zastavky = line.split(": ")
	zastavky = zastavky.split(" - ")
	if linka == "1":
		linka1 = zastavky
	for i in range(len(zastavky) - 2):
		sousedi[zastavky[i]].add(zastavky[i+1])
		sousedi[zastavky[i+1]].add(zastavky[i])

master = defaultdict(lambda: False)

for zastavka in sousedi.keys():
	if len(sousedi[zastavka]) >= 4:
		master[zastavka] = True

kanonickeJmeno = dict()
for zastavka in sousedi.keys():
	kanonickeJmeno[zastavka] = zastavka

for zastavka in sousedi.keys():
	if master[zastavka]:
		best = kanonickeJmeno[zastavka]
		for soused in sousedi[zastavka]:
			if better(kanonickeJmeno[soused], best):
				best = kanonickeJmeno[soused]
		for soused in sousedi[zastavka]:
			jmeno_obeti = kanonickeJmeno[soused]
			for obet in sousedi.keys():
				if kanonickeJmeno[obet] == jmeno_obeti:
					kanonickeJmeno[obet] = best

print(" - ".join(dedup([kanonickeJmeno[z.strip()] for z in linka1])))
