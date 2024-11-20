#!/usr/bin/env python
import sys, Image
"""
Usage: 
    evaluator.py <input_file>
"""

image_file = Image.open(sys.argv[1])
image_file = image_file.convert('1')
pixels = image_file.load()

width, height = image_file.size
if width != 106 or height != 17:
    print("Wrong image dimension!")
    sys.exit(1)

result = 0
for x in reversed(range(106)):
    for y in reversed(range(17)):
        result = (result << 1) | (pixels[x, y]  > 0)

result *= 17

print result