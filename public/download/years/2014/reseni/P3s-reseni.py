#!/usr/bin/python
# Example solution of InterLoS P3 CalcuLoS
# Idea: simple prefix evaluator evaluating simple math expressions in modulo class Z3
# Values: 
#   L = 0
#   O = 1
#   S = 2
# Operators:
#   I = *
#   N = +
#   T = inverse
#   E = flip
#   R = duplicate
# Note: due to usage of list not much effective implementations but enough for this size of input
# There are two solutions:
#  - Evaluation from end of the expression
#  - Recursive evaluation from beginning of the expression
# Both expect valid string with postfix expression given in CalcuLoS notation

T = {'L': 'S', 'O': 'O', 'S':'L'}

def is_operator(symbol):
    return symbol in list('INTER')
    
def operator_T(value):
    return T[value]

def operator_I(left, right):
    if (left == 'S' and right == 'S') or (left == 'O' and right == 'O'):
        return 'O'
    elif left == 'L' or right == 'L':
        return 'L'
    return 'S'

def operator_N(left, right):
    if left == 'L':
        return right
    elif right == 'L':
        return left
    elif left == 'O' and right == 'O':
        return 'S'
    elif left == 'S' and right == 'S':
        return 'O'
    return 'L'

def evaluate_expression(expression):
    if len(expression) == 0:
        raise Exception('Invalid (empty) expression')
    stack = list(expression)
    pos = len(stack) - 1
    while pos != -1:
        # Find rightmost operator
        op_pos = pos
        while not is_operator(stack[op_pos]) and op_pos != -1:
            op_pos -= 1
        # Perform it if found
        if op_pos != -1:
            operator = stack[op_pos]
            stack.pop(op_pos)
            #print "Evaluation of operator: " + operator
            if operator == 'I':
                arg = stack.pop(op_pos)
                arg2 = stack.pop(op_pos)
                stack.insert(op_pos, operator_I(arg, arg2))
                pass
            elif operator == 'N':
                arg = stack.pop(op_pos)
                arg2 = stack.pop(op_pos)
                stack.insert(op_pos, operator_N(arg, arg2))
                pass
            elif operator == 'T':
                arg = stack.pop(op_pos)
                stack.insert(op_pos, operator_T(arg))
            elif operator == 'E':
                arg = stack.pop(op_pos)
                arg2 = stack.pop(op_pos)
                stack.insert(op_pos, arg)
                stack.insert(op_pos, arg2)
            elif operator == 'R':
                arg = stack.pop(op_pos)
                stack.insert(op_pos, arg)
                stack.insert(op_pos, arg)
            else:
                raise Exception('Unsupported operator: ' + operator)
        # Move on
        pos = op_pos
    return ''.join(stack)

def evaluate_expression_recursive(expression):
    if len(expression) == 0:
        raise Exception('Invalid (empty) expression')
    stack = list(expression)
    eer_helper(stack, 0)
    return ''.join(stack)

def eer_helper(stack, start_pos):
    if len(stack) == 0:
        return
    while start_pos < len(stack):
        symbol = stack[start_pos]
        if symbol in list('LOS'):
            start_pos += 1
        elif symbol == 'I':
            eer_helper(stack, start_pos + 1)
            arg = stack.pop(start_pos + 1)
            arg2 = stack.pop(start_pos + 1)
            stack[start_pos] = operator_I(arg, arg2)
        elif symbol == 'N':
            eer_helper(stack, start_pos + 1)
            arg = stack.pop(start_pos + 1)
            arg2 = stack.pop(start_pos + 1)
            stack[start_pos] = operator_N(arg, arg2)
        elif symbol == 'T':
            eer_helper(stack, start_pos + 1)
            arg = stack.pop(start_pos + 1)
            stack[start_pos] = operator_T(arg)
        elif symbol == 'E':
            eer_helper(stack, start_pos + 1)
            arg = stack.pop(start_pos + 1)
            arg2 = stack.pop(start_pos + 1)
            stack[start_pos] = arg2
            stack.insert(start_pos + 1, arg)
        elif symbol == 'R':
            eer_helper(stack, start_pos + 1)
            stack[start_pos] = stack[start_pos + 1]
        else:
            raise Exception('Unsupported symbol: ' + operator)
    
