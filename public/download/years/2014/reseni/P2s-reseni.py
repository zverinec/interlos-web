#!/usr/bin/env python
# coding=utf-8
import math
from copy import deepcopy

zrkadla = []

C = (42,0)
H = 8
def dist(poz1, poz2, smer):
	if smer == 'h' or smer == 'd':
		return abs(poz1[1] - poz2[1])
	else:
		return abs(poz1[0] - poz2[0])

def trafi(poz, smer, z):
	if   smer == "h":
		return poz[0] == z[0] and poz[1] < z[1]
	elif smer == "d":
		return poz[0] == z[0] and poz[1] > z[1]
	elif smer == "p":
		return poz[1] == z[1] and poz[0] < z[0]
	else:
		return poz[1] == z[1] and poz[0] > z[0]

def pretinaZrkadlo(poz, smer):
	trafeneZrkadlo = -1
	d = 0
	for i in range(len(zrkadla)):
		if trafi(poz, smer, zrkadla[i]):
			tmp = dist(zrkadla[i],poz, smer)
			if trafeneZrkadlo == -1 or tmp < d:
				trafeneZrkadlo = i
				d = dist(zrkadla[trafeneZrkadlo], poz, smer)
	return trafeneZrkadlo

def trafiCiel(poz, smer):
	return trafi(poz, smer, C)

def solveA(natocenie, z, smer, path, depth):
	if natocenie[z][0]:
		if natocenie[z][1] == "A":
			path+='A'
			if smer == "h":
				return solve(natocenie, zrkadla[z], "l", path, depth + 1)
			elif smer == "d":
				return solve(natocenie, zrkadla[z], "p", path, depth + 1)
			elif smer == "p":
				return solve(natocenie, zrkadla[z], "d", path, depth + 1)
			else:
				return solve(natocenie, zrkadla[z], "h", path, depth + 1)
	else:
		natocenie[z][0] = True
		natocenie[z][1] = 'A'
		path+='A'
		if smer == "h":
			return solve(natocenie, zrkadla[z], "l", path, depth + 1)
		elif smer == "d":
			return solve(natocenie, zrkadla[z], "p", path, depth + 1)
		elif smer == "p":
			return solve(natocenie, zrkadla[z], "d", path, depth + 1)
		else:
			return solve(natocenie, zrkadla[z], "h", path, depth + 1)

def solveB(natocenie, z, smer, path, depth):
	if natocenie[z][0]:
		if natocenie[z][1] == "B":
			path+='B'
			if smer == "h":
				return solve(natocenie, zrkadla[z], "p", path, depth + 1)
			elif smer == "d":
				return solve(natocenie, zrkadla[z], "l", path, depth + 1)
			elif smer == "p":
				return solve(natocenie, zrkadla[z], "h", path, depth + 1)
			else:
				return solve(natocenie, zrkadla[z], "d", path, depth + 1)
	else:
		natocenie[z][0] = True
		natocenie[z][1] = 'B'
		path+='B'
		if smer == "h":
			return solve(natocenie, zrkadla[z], "p", path, depth + 1)
		elif smer == "d":
			return solve(natocenie, zrkadla[z], "l", path, depth + 1)
		elif smer == "p":
			return solve(natocenie, zrkadla[z], "h", path, depth + 1)
		else:
			return solve(natocenie, zrkadla[z], "d", path, depth + 1)

def solve(natocenie, poz, smer, cesta, hlbka):
	trafeneZ = pretinaZrkadlo(poz, smer)

	if trafiCiel(poz, smer):
		if trafeneZ != -1:
			if dist(poz, C, smer) < dist(poz, zrkadla[trafeneZ], smer):
				return cesta
		else:
			return cesta

	if hlbka < H and trafeneZ != -1:
		A = solveA(deepcopy(natocenie), trafeneZ, smer, deepcopy(cesta), hlbka)
		B = solveB(deepcopy(natocenie), trafeneZ, smer, deepcopy(cesta), hlbka)
		if A == None: A = "X" * (H + 1)
		if B == None: B = "X" * (H + 1)
		if len(A) == len(B):
			return min(A,B)
		if len(A) < len(B):
			return A
		else:
			return B
	else:
		return "X" * (H + 1)

if __name__ == "__main__":
	f = open("P2-input.txt","r")
	for line in f:
		a = map(int, line.split())
		if len(a) > 0:
			zrkadla.append(a)
	f.close()
	natocenie = [[False, "A"] for i in range(len(zrkadla))]
	
	sol = "X"
	while sol[0] == "X":
		sol = solve(natocenie, [0,0], "h", "", 0)
		H += 1
	
	print sol
