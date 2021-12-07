def get_decimal(number, i=0):
    if i == len(number):
        return 0
    if number[i] == 1:
        return get_decimal(number, i + 1)
    number_len = len(number) - i - 1
    if number[i] == 2:
        return 2 * 3**number_len - 1 - get_decimal(number, i + 1)
    return 2 * 3**number_len + get_decimal(number, i + 1)


print(get_decimal([ 3, 3, 1, 2, 1, 1, 3, 1, 2, 2, 1, 3, 2, 1, 2 ]))
