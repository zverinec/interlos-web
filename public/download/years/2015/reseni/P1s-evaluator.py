#!/usr/bin/env python
"""
Usage: 
	evaluator.py input_key

	Outputs: "result.png"
"""
import sys, Image

k = int(sys.argv[1]) 

image = Image.new("1", (106, 17))
pixels = image.load()

for y in reversed(range(17)):
	for x in range(106):
		yy = k + y
		exp = 17 * x + (yy % 17)
		yy = yy // 17
		pixels[x, y] = 1/42 < (yy // (1 << exp)) % 2

image.save("result.png")
