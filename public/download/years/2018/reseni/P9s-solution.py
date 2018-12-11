#!/usr/bin/env python3

def digit_sum(num):
    return str(num - (9 * ((num-1) // 9)))

def tlesk(num, last, last_digit_sum):
    return (num % last) == 0 or last_digit_sum in str(num)

def play(n):
    row = [i + 1 for i in range(n)]
    last_len = 0
    last_num = 3
    while len(row) != last_len:
        last_len = len(row)
        print(len(row), last_num, digit_sum(last_num))
        row = list(filter(lambda num: not tlesk(num, last_num, digit_sum(last_num)), row))
        last_num = last_len - len(row)
    return len(row)

print(play(10000000))
