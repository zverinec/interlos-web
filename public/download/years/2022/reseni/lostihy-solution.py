import csv


def dice():
	with open("lostihy-tahy.txt") as file:
		numbers = [int(x) for x in file.read().split()]

	for num in numbers:
		yield num


class Tile:
	def __init__(self):
		pass

	def visit(self, who, roll: int):
		pass


class Moose:
	def __init__(self, name, price, fee):
		self.name = name
		self.price = price
		self.fee = fee

		self.owned_by = None


	def visit(self, who, roll: int):
		if self.owned_by is None:  # nikdo lose nevlastni
			if not who.can_pay(self.price) or not who.want_buy():
				return

			self.owned_by = who
			who.money -= self.price
			who.owned_moose += 1
			return

		if self.owned_by is who:  # na lose vstoupil vlastník
			return

		if not self.owned_by.can_receive():  # vlastnik je podezrely ze sobingu
			return

		fee = self.fee
		if not who.can_pay(fee):  # hrac nema dost penez na zaplaceni
			who.alive = False
			if self.owned_by.alive:
				self.owned_by.money += who.money
			who.money = 0
		else:
			who.money -= fee
			if self.owned_by.alive:
				self.owned_by.money += fee


class Random:
	current = 0

	def __init__(self):
		pass

	def visit(self, who, roll):
		global tiles
		if Random.current == 0:
			who.position -= 3  # tri zpet

		elif Random.current == 1:
			who.position += 3  # tri dopredu

		elif Random.current in [2, 9]:
			who.position = 0  # na start

		elif Random.current == 3:
			if who.position == 35:
				who.money += 4000
			who.position = {
				7: 15,
				22: 25,
				35: 5,
			}[who.position]  # chovatel

		elif Random.current == 4:
			if who.position > 20:
				who.money += 4000

			who.position = 20  # parkoviste

		elif Random.current == 5:
			who.position = 10  # los karlos

		elif Random.current in [6, 8]:
			who.position = {
				7: 2,
				22: 17,
				35: 32,
			}[who.position]  # finance

		elif Random.current == 7:
			who.position = 38

		Random.current = (Random.current + 1) % 10

		tiles[who.position].visit(who, roll)  # vyhodnoceni policka kam byl hrac posunut


finance_fees = []
current_finance = 0
with open("finance.txt") as file:
	for line in file:
		finance_fees.append(int(line.split(":")[-1].strip()[:-1]))

class Finance:
	def __init__(self):
		pass

	def visit(self, who, roll):
		global current_finance
		who.money += finance_fees[current_finance]

		if who.money < 0:  # hrac nemel dost penez na zaplaceni
			who.money = 0
			who.alive = False

		current_finance = (current_finance + 1) % len(finance_fees)


class Chovatel:
	def __init__(self):
		self.price = 4000
		self.fee = 1000

		self.owned_by = None


	def visit(self, who, roll: int):
		if self.owned_by is None:  # chovatele nikdo nevlastni
			if not who.can_pay(self.price) or not who.want_buy():
				return

			self.owned_by = who
			who.money -= self.price
			who.owned_chovatel += 1
			return

		if self.owned_by is who:  # na chovatele vstoupil vlastnik
			return

		if not self.owned_by.can_receive():  # vlastnik chovatele je podezrely ze sobingu
			return

		fee = self.owned_by.owned_chovatel * self.fee
		if not who.can_pay(fee):  # hrac nema dost penez na zaplaceni
			who.alive = False
			if self.owned_by.alive:
				self.owned_by.money += who.money
			who.money = 0
		else:
			who.money -= fee
			if self.owned_by.alive:
				self.owned_by.money += fee


class Veterina:
	def __init__(self):
		self.fee = 1000

	def visit(self, who, roll):
		who.money -= self.fee
		if who.money < 0:  # hrac nemel dost penez na zaplaceni
			who.alive = False
			who.money = 0


class Sobing:
	def __init__(self):
		pass

	def visit(self, who, roll):
		pass


class Sprezeni:
	def __init__(self):
		self.price = 3000
		self.fee = 150
		self.owned_by = None

	def visit(self, who, roll):
		if self.owned_by is None:  # sprezeni nikdo nevlastni
			if not who.can_pay(self.price) or not who.want_buy():
				return

			self.owned_by = who
			who.money -= self.price
			return

		if self.owned_by is who:  # na sprezeni vstoupil vlastnik
			return

		if not self.owned_by.can_receive():  # vlastnik je podezrely ze sobingu
			return

		fee = roll * self.fee
		if not who.can_pay(fee):  # hrac nema dost penez na zaplaceni
			who.alive = False
			who.money = 0
		else:
			who.money -= fee
			if self.owned_by.alive:
				self.owned_by.money += fee


class Karlos:
	def __init__(self):
		pass

	def visit(self, who, roll):
		who.captured = True


class Player:
	def __init__(self, name, money, decision):
		self.alive = True
		self.name = name
		self.money = money
		self.position = 0

		self.owned_moose = 0
		self.owned_chovatel = 0

		self.sobing = False
		self.captured = 0

		self.decision = decision

	def can_pay(self, value):
		return self.money >= value
	
	def can_receive(self):
		global tiles
		return not (isinstance(tiles[self.position], Karlos) or isinstance(tiles[self.position], Sobing))

	def want_buy(self):
		# hrac chce policko koupit, pokud self.decision ma ve dvojkovem zapisu na prvnim miste 0 (=> je sude)
		# posune cislo ve dvojkovem zapisu (celociselne vydeli 2)
		result = self.decision % 2 == 0
		self.decision //= 2
		return result


losi = {}
with open("losi.csv") as file:  # nacte losy ze souboru
	csv_file = csv.reader(file)

	header = csv_file.__next__()
	for line in csv_file:
		losi[line[0]] = line[1:]

tiles = []
with open("policka.txt") as file:
	for line in file:
		tile: str = line.strip()
		if tile == "Los Karlos":
			tiles.append(Karlos())

		elif tile.startswith("Los"):
			los = losi[tile]
			tiles.append(Moose(tile, int(los[0]), int(los[1])))

		elif tile.startswith("Finance"):
			tiles.append(Finance())

		elif tile.startswith("Náhoda"):
			tiles.append(Random())

		elif tile.startswith("Chovatel"):
			tiles.append(Chovatel())

		elif tile.startswith("Veter"):
			tiles.append(Veterina())

		elif tile.startswith("Pode"):
			tiles.append(Sobing())

		elif tile.startswith("Psí"):
			tiles.append(Sprezeni())

		else:
			tiles.append(Tile())

max_money = 0  # nejvetsi zatim dosazene mnozstvi penez

for i in range(2**12):  # 2**12 pokryje vsechny mozne kombinace 12 rozhodnuti (coz je vic nez dost)
	current_n = 0
	players = [Player(1, 40_000, 0), Player(2, 30_000, i), Player(3, 40_000, 0), Player(4, 40_000, 0)]

	the_dice = dice()

	current_finance = 0
	Random.current = 0
	for tile in tiles:
		tile.owned_by = None

	try:
		while sum([x.alive for x in players]) > 1:
			current = players[current_n]
			if not current.alive:  # preskocit vyrazene hrace
				current_n = (current_n + 1)%4
				continue


			if current.captured:  # pokud je hrac zajaty Losem Karlosem
				current.captured = False
				roll = the_dice.__next__()
				if roll != 6:
					current_n = (current_n + 1)%4
					continue

			move_by = 0  # hazeni kostkou
			roll = the_dice.__next__()
			move_by += roll
			while roll == 6:
				roll = the_dice.__next__()
				move_by += roll

			current.position += move_by
			if current.position >= len(tiles):  # hrac prosel startem
				current.money += 4000
				current.position %= len(tiles)

			tiles[current.position].visit(current, roll)

			current_n = (current_n + 1)%4

	except StopIteration:
		pass

	if players[1].money > max_money:
		max_money = players[1].money

print(max_money)
