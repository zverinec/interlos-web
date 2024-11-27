if (int(acc1[1:3]) == 8):
	acc1='00' + acc1 + '|A01'
	push(acc1)

	while not empty():
		acc1 = pop()
		acc2 = int(acc1[0:2])
		acc1 = acc1[2:]
		if ((acc2 + 1) < 8):
			push(int_to_str(acc2 + 1) + acc1)
		push(acc1)
		push(acc1[(len(acc1) - 3):])

		if (acc1[0]) == 'J':
			acc1 = [-(2*8-1), -(2*8+1), -(8+2), -(8-2), (8-2), (8+2), (2*8-1), (2*8+1)]
			acc1 = acc1[acc2]
		elif (acc1[0]) == 'K':
			acc1 = [-(8 - 1), -(8), -(8 + 1), -1, 1, (8 - 1), (8), (8 + 1)]
			acc1 = acc1[acc2]	

		acc2 = pop()
		acc2 = 8 * (char_to_num(acc2[0]) - 1) + int(acc2[1:]) - 1

		if (((acc2 + acc1) >= 0) and ((acc2 + acc1) < 8 * 8) and (abs(acc2 // 8 - (acc2 + acc1) // 8) == ( 0 if (acc1 <= 1) else (1 if (acc1 <= (8 + 2)) else 2)))):
			acc2 = acc1 + acc2
			acc1 = acc2
			acc2 = num_to_char(1 + acc2 // 8) + int_to_str(acc2 % 8 + 1)
			if (acc1 == (8 * 8 - 1)):
				acc1 = pop()
				output(acc1[(acc1.find('|') + 1):] + acc2)
				break
			acc1 = pop()
			if not acc2 in acc1:
				push('00' + acc1 + acc2)
		else:
			acc1 = pop()

elif (int(acc1[1:3]) == 9):
	acc1='00' + acc1 + '|A01'
	push(acc1)

	while not empty():
		acc1 = pop()
		acc2 = int(acc1[0:2])
		acc1 = acc1[2:]
		if ((acc2 + 1) < 8):
			push(int_to_str(acc2 + 1) + acc1)
		push(acc1)
		push(acc1[(len(acc1) - 3):])

		if (acc1[0]) == 'J':
			acc1 = [-(2*9-1), -(2*9+1), -(9+2), -(9-2), (9-2), (9+2), (2*9-1), (2*9+1)]
			acc1 = acc1[acc2]
		elif (acc1[0]) == 'K':
			acc1 = [-(9 - 1), -(9), -(9 + 1), -1, 1, (9 - 1), (9), (9 + 1)]
			acc1 = acc1[acc2]	

		acc2 = pop()
		acc2 = 9 * (char_to_num(acc2[0]) - 1) + int(acc2[1:]) - 1

		if (((acc2 + acc1) >= 0) and ((acc2 + acc1) < 9 * 9) and (abs(acc2 // 9 - (acc2 + acc1) // 9) == ( 0 if (acc1 <= 1) else (1 if (acc1 <= (9 + 2)) else 2)))):
			acc2 = acc1 + acc2
			acc1 = acc2
			acc2 = num_to_char(1 + acc2 // 9) + int_to_str(acc2 % 9 + 1)
			if (acc1 == (9 * 9 - 1)):
				acc1 = pop()
				output(acc1[(acc1.find('|') + 1):] + acc2)
				break
			acc1 = pop()
			if not acc2 in acc1:
				push('00' + acc1 + acc2)
		else:
			acc1 = pop()

elif (int(acc1[1:3]) == 10):
	acc1='00' + acc1 + '|A01'
	push(acc1)

	while not empty():
		acc1 = pop()
		acc2 = int(acc1[0:2])
		acc1 = acc1[2:]
		if ((acc2 + 1) < 8):
			push(int_to_str(acc2 + 1) + acc1)
		push(acc1)
		push(acc1[(len(acc1) - 3):])

		if (acc1[0]) == 'J':
			acc1 = [-(2*10-1), -(2*10+1), -(10+2), -(10-2), (10-2), (10+2), (2*10-1), (2*10+1)]
			acc1 = acc1[acc2]
		elif (acc1[0]) == 'K':
			acc1 = [-(10 - 1), -(10), -(10 + 1), -1, 1, (10 - 1), (10), (10 + 1)]
			acc1 = acc1[acc2]	

		acc2 = pop()
		acc2 = 10 * (char_to_num(acc2[0]) - 1) + int(acc2[1:]) - 1

		if (((acc2 + acc1) >= 0) and ((acc2 + acc1) < 10 * 10) and (abs(acc2 // 10 - (acc2 + acc1) // 10) == ( 0 if (acc1 <= 1) else (1 if (acc1 <= (10 + 2)) else 2)))):
			acc2 = acc1 + acc2
			acc1 = acc2
			acc2 = num_to_char(1 + acc2 // 10) + int_to_str(acc2 % 10 + 1)
			if (acc1 == (10 * 10 - 1)):
				acc1 = pop()
				output(acc1[(acc1.find('|') + 1):] + acc2)
				break
			acc1 = pop()
			if not acc2 in acc1:
				push('00' + acc1 + acc2)
		else:
			acc1 = pop()
