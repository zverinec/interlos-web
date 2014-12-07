#!/usr/bin/python
import random

def is_operator(symbol):
    return symbol in list('INTER')

# Function for generation of expressions
def generate_expression(preferedLength = 1000):
    pst = {}
    for s in list('INTERLOS'):
        pst[s] = float(1)/8

    output = ''
    length = 0
    temp = []
    while length < preferedLength or len(temp) != 0:
        s = weighted_choice(pst)
        #print s,
        if s in list('IN'):
            if len(temp) > 0: temp.pop()
            temp.append('X')
            temp.append('X')
        elif s in list('T'):
            if len(temp) > 0: temp.pop()
            temp.append('X')
        elif s in list('R'):
            if len(temp) > 0: temp.pop()
            if len(temp) > 0: temp.pop()            
            temp.append('X')
        elif s in list('E'):
            if len(temp) > 0: temp.pop()
            if len(temp) > 0: temp.pop()
            temp.append('X')
            temp.append('X')
        elif s in list('LOS'):
            if len(temp) > 0: temp.pop()
        output += s
        length += 1
    return output

def weighted_choice(choices):
    items = choices.items()
    total = sum(w for c, w in items)
    r = random.uniform(0, total)
    upto = 0
    for c, w in items:
        if upto + w > r:
            return c
        upto += w

print generate_expression()
