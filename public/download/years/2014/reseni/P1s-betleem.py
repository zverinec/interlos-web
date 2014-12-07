#!/usr/bin/env python
import argparse # For parsing argument
import os # For system function for any key waiting
from PIL import Image # For working with images
from random import randint # For obsfucation purposes

# Constants
INSTRUCTION_WIDTH = 30
INSTRUCTION_HEIGHT = 30

# Global context
verbose = False
stepping = False
perform_encode = False
input_file = ""
output_file = ""
dimensions = (0,0)
space = []
hidden = []

def prepare_context():
    parser = argparse.ArgumentParser()
    # Input file
    parser.add_argument("inputImage", help="File path to input image in PNG format.", type=str)
    # Output file
    parser.add_argument("outputImage", help="File name with path where output image in PNG format will be stored.", type=str)
    # Debug
    parser.add_argument("--verbose", help="Show debug messages.", action="store_true")
    # Debug stepping
    parser.add_argument("--stepping", help="Enables step-by-step mode.", action="store_true")
    # Encode
    parser.add_argument("--encode", help="Encodes input from hidden.txt", action="store_true")
    
    # Parse parameters
    global verbose, stepping, input_file, output_file, perform_encode
    args = parser.parse_args()
    verbose = args.verbose
    stepping = args.stepping
    perform_encode = args.encode
    input_file = args.inputImage
    output_file = args.outputImage


def load_input():
    global dimensions
    im = Image.open(input_file)
    dimensions = im.size
    if im.size[0] % INSTRUCTION_WIDTH != 0 or im.size[1] % INSTRUCTION_HEIGHT != 0:
        print "Invalid image dimensions."
        exit(1)
    
    for column in range(INSTRUCTION_HEIGHT/2, im.size[1], INSTRUCTION_WIDTH):
        for line in range(INSTRUCTION_WIDTH/2, im.size[0], INSTRUCTION_HEIGHT):
            space.append(im.getpixel((line, column)))
    im.close()

def putInstruction(im, x, y, cell):
    for i in range(y, y+INSTRUCTION_HEIGHT):
        for j in range(x, x+INSTRUCTION_WIDTH):
            im.putpixel((j,i), space[cell])

def store_output(output_file):
    im = Image.new("RGB", dimensions, "white")
    cell = 0
    for y in range(0, dimensions[1], INSTRUCTION_HEIGHT):
            for x in range(0, dimensions[0], INSTRUCTION_WIDTH):
                putInstruction(im, x, y, cell)
                cell += 1
    im.save(output_file, "BMP")
    im.close()

def check_pattern(color, pattern):
    pattern = list(pattern)
    if color[0] % 2 == int(pattern[0]) and color[1] % 2 == int(pattern[1]) and color[2] % 2 == int(pattern[2]):
        return True
    return False

def get_line_width():
    return dimensions[0] / INSTRUCTION_WIDTH

def get_offset(color, steps = 1):
    if steps <= 0:
        print 'Number of steps must be >= 1'
        exit(1);
    if check_pattern(color, '000'): # RIGHT
        return steps
    elif check_pattern(color, '001'): # LEFT
        return (0 - steps)
    elif check_pattern(color, '010'): # UP
        return 0 - (get_line_width() * steps)
    elif check_pattern(color, '011'): # DOWN
        return (get_line_width() * steps)
    elif check_pattern(color, '100'): # UP RIGHT
        return (0 - (get_line_width() * steps)) + steps
    elif check_pattern(color, '101'): # DOWN LEFT
        return (get_line_width() * steps) - steps
    elif check_pattern(color, '110'): # DOWN RIGHT
        return (get_line_width() * steps) + steps
    elif check_pattern(color, '111'): # UP LEFT
        return (0 - (get_line_width() * steps)) - steps
    else:
        print 'Something went wrong'
        exit(1)
        
def eval_look_aside(color):
    if color[0] % 2:
        return (color[1] % 4) + 1
    return 1

def interpret():
    global space
    ip = 0
    while ip >= 0 and ip < len(space):
        current = space[ip]
        steps = 1
        if ip != 0:
            steps = eval_look_aside(space[ip-1])

        space[ip] = (0,0,0) # Mark visited cell
        
        offset = get_offset(current, steps)
        if verbose:
            print 'Position:', ip % get_line_width(), 'x', ip / get_line_width() ,'Color:', current,'steps:', steps,'offset:', offset
        if stepping and not wasNothing:
            print "Step made, press ENTER to continue..."
            raw_input()
        ip += offset
    if verbose: print "Finished."

def encode():
    f = open('hidden.txt')
    for line in f.readlines():
        temp = line.split()
        hidden.append(temp)
    f.close()
    
    if verbose:
        for line in hidden:
            print line
        
    y = 0
    for y in range(len(hidden)):
        for x in range(len(hidden[y])):
            if verbose:
                print "Encoding (" + str(x) + ', ' + str(y) + '): ' + hidden[y][x]
            pos = get_line_width()*y + x
            if y == 0 or x == 0 or y == len(hidden) - 1 or x == len(hidden[y]) - 1:
                randomize = False
            else:
                randomize = hidden[y][x] == '_' and hidden[y][x+1] == '_'
            space[pos] = encode_instruction(space[pos], hidden[y][x], randomize)
        print 
            
def encode_instruction(color, value, randomize):
    if value == '1':
        return construct_pattern(color, '101')
    elif value == '2':
        return construct_pattern(color, '011')
    elif value == '3':
        return construct_pattern(color, '110')
    elif value == '4':
        return construct_pattern(color, '001')
    elif value == '5':
        print '5 is unwanted!'
        exit(1)
    elif value == '6':
        return construct_pattern(color, '000')
    elif value == '7':
        return construct_pattern(color, '111')
    elif value == '8':
        return construct_pattern(color, '010')
    elif value == '9':
        return construct_pattern(color, '100')
    elif value == 'A':
        return construct_look_aside(color, 2)
    elif value == 'B':
        return construct_look_aside(color, 4)
    elif value == 'C':
        return construct_look_aside(color, 3)
    else:
        return construct_safe(color, randomize) # There will be obsfucation

def construct_pattern(color, pattern):
    pattern = list(pattern)
    tr = (color[0] + randint(-12,12)) % 256
    tg = (color[1] + randint(-12,12)) % 256
    tb = (color[2] + randint(-12,12)) % 256
    red = (tr & 254) + int(pattern[0])
    green = (tg & 254) + int(pattern[1])
    blue = (tb & 254) + int(pattern[2])
    return (red, green, blue)

def construct_look_aside(color, value):
    tr = randint(0,6) * (-2)
    tb = randint(-12,12)
    return ((color[0] & 0b11111110) + 1 + tr, (color[1] & 0b11111100) + value - 1, color[2] + tb)

def construct_safe(color, randomize):
    if randomize:
        red = (color[0] + randint(-12,12)) % 256
        green = (color[1] + randint(-12,12)) % 256
        blue = (color[2] + randint(-12,12)) % 256
        return (red, green, blue)
    else:
        tg = randint(-12,12)
        tb = randint(-12,12)
        return ((color[0] & 0b11111110) , color[1] + tg, color[2] + tb)

def main():
    prepare_context()
    load_input()
    print perform_encode
    if perform_encode:
        encode()
    else:
        interpret()
    store_output(output_file)


if __name__ == "__main__":
    main()
