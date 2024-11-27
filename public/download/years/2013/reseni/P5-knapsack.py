import sys
import math

def knapsack(items, maxweight):
	# Vytvoreni (N+1) krat (W+1) 2-D pole obsahujiciho prubezne nejlepsi vysledky naplnene algoritmem.
	#
		# Pole obsahuje N+1 radku, protoze musime pocitat s moznosti vyberu od 0 az do N (vcetne) prvku.
		# Pole obsahuje W+1 sloupcu, protoze musime pocitat s moznosti "kapacit" od 0 az do maximalni kapacity W vcetne.
	bestvalues = [[0] * (maxweight + 1)
				  for i in range(len(items) + 1)]

	# Iterujeme skrz prvky a plnime tabulku nejlepsich hodnot (bestvalues)
	for i, (value, weight, name) in enumerate(items):
		# Zvetsime i o jednicku, protoze prvni radek (index 0) je pripad kdy neni vybrana zadna polozka a tim padem je uz nainicializovan.
		i += 1
		for capacity in range(maxweight + 1):
			# Vyreseni pripadu, kdy "vaha" aktualni polozky je vyssi nez aktualni volna kapacita -> nemuzeme ho pridat do batuzku.
			if weight > capacity:
				bestvalues[i][capacity] = bestvalues[i - 1][capacity]
			else:
								# V jinem pripade musime vybrat mezi 2 moznymi kandidaty:
								#  1) Aktualni hodnota batuzku bez pridane aktualni polozky
								#  2) Hodnota aktualni polozky sectena s hodnotama predtim ulozenych polozek
				candidate1 = bestvalues[i - 1][capacity]
				candidate2 = bestvalues[i - 1][capacity - weight] + value

				# Ulozime vetsi z hodnot
				bestvalues[i][capacity] = max(candidate1, candidate2)

	# Nalezeni nejlepsi posloupnosti
		# Iterujeme skrz tabulku hodnot a kontroluje, ktery ze dvou kandidatu byl vybran. 
		# Toto lze to provest porovnanim, jestli je hodnota stejna jako hodnota v predchozim radku - 
		# jestli ano, pak rekneme, ze polozka nebyla ulozena do batuzku a posuneme se o radek vys. 
		# Jinak pridame polozky do listu a odecteme jeji hmotnost od zbyvajici kapacity batuzku. 
		# Az dosahneme nuly, tak jsme hotovi...
	reconstruction = []
	i = len(items)
	j = maxweight
	while i > 0:
		if bestvalues[i][j] != bestvalues[i - 1][j]:
			reconstruction.append(items[i - 1])
			j -= items[i - 1][1]
		i -= 1

	# Zmenime poradi listu tak, aby byl v poradi v jakem byly dany jednotlive prvky
	reconstruction.reverse()

	# Vracime nejlepsi hodnotu a list
	return bestvalues[len(items)][maxweight], reconstruction

if __name__ == '__main__':
	# Celkova kapacita "batuzku" (tzn. maximalni cena nakupu)
	capacity = 140.9

	print('Celkova kapacita batohu: ' + str(capacity))

	# Nacteni jednotlivych polozek ze souboru input.txt
	# Format: priorita cena nazev
	items = []
	for l in open('input.txt', 'r'):
		l = l.strip()
		tokens = l.split(' ', 2)
		# Cenu nasobime 100, abychom se "zbavili" haleru
		price = float(tokens[1])
		items.append((int(tokens[0]), int(price*100), tokens[2]))
	
	print('Pocitam...\n')

	# Spusteni vypoctu a ziskani vysledku
	res = knapsack(items, int(capacity*100))
	# Vypocitani konecne ceny
	price = sum( [x[1] for x in res[1] ]) 
	
	# Vypis vysledku na obrazovku
	print('Nejvyssi soucet priorit: ' + str(res[0]) + ' => HESLO: ' + str(res[0]))
	print('Maximalni cena: ' + str(capacity))
	print('Celkova cena: ' + str(price/100))
	print()
	print('Produkty:')
	print('Priorita\tCena\t\tNazev')
	for p in res[1]:
		print(str(p[0]) + '\t\t' + str(p[1]/100) + '\t\t' + p[2])
