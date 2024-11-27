import pygame as pg
import importlib  
logo = importlib.import_module("los-logo-solution")

def draw_frame(screen, frame, Big=logo.BIG, start_x=0, start_y=0, color=(0, 0, 0)):
	for i in range(len(frame)):
		for j in range(len(frame[i])):
			if frame[i][j] == "X":
				pg.draw.rect(screen, color, [start_x + i*Big, start_y + j*Big, Big, Big])


def draw_logo(screen, position, color=(122, 122, 122)):
	pg.draw.rect(screen, color, [position[0][0], position[1][0], l_size[0], l_size[1]])


def sim(l_pos, l_size, path, direct):
	pg.init()
	frame = [x for x in logo.load(path, l_size[0], l_size[1])]
	size = (len(frame[1]) + 1, len(frame[1][0]) + 1)
	screen = pg.display.set_mode(size)
	simul = logo.simulate(frame[1], l_pos[0], l_pos[1], direct[0], direct[1])
	go = True
	next_ = False
	clock = pg.time.Clock()
	screen.fill(W)
	draw_frame(screen, frame[0])
	last = ((l_pos[0], 0), (l_pos[1], 0), l_size[0], l_size[1])
	act = last
	while go:
		for event in pg.event.get():
			if event.type == pg.QUIT:
				go = False
			elif event.type == pg.KEYDOWN:
				if next_:
					print(act)
				next_ = not next_
		try:
			if next_:
				act = next(simul)
			draw_logo(screen, last, W)
			
			draw_frame(screen, frame[0], 1, act[0][0], act[1][0], (122, 122, 122))
			pg.draw.rect(screen, B,
						 [act[0][0] + act[0][0] * l_size[0] // size[0], #for logo in logo
						  act[1][0] + act[1][0] * l_size[1] // size[1],
						  l_size[0] * l_size[0] // size[0], l_size[1] * l_size[1] // size[1]])
			
			pg.display.flip()
			last = act
		except:
			pass
		clock.tick(max_ticks_per_second)


W = (255, 255, 255)
B = (0, 0, 0)
size = (1000, 1000)
BIG = logo.BIG
l_size = (43, 43)

l_pos = (365, 342)
direct = (-8, -5)

max_ticks_per_second = 800

sim(l_pos, l_size, "logo.csv", direct)


pg.quit()
