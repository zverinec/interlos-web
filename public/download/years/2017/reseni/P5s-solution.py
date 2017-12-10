#!/usr/bin/env python3

def find_lr(word):
    for i in range(1, len(word)):
        if word[i] != word[0]:
            return i;
    return len(word)

def find_rl(word):
    for i in range(len(word) - 2, -1, -1):
        if word[i] != word[-1]:
            return i + 1;
    return 0

def _reducible(context, word):
    if word in context:
        return context[word]
    if len(word) == 0:
        context[word] = True
        return True
    if len(word) == 1:
        context[word] = False
        return False
    a = find_lr(word)
    b = find_rl(word)
    if word[0] == word[-1] and _reducible(context, word[a:b]):
        context[word] = True
        return True
    for i in range(1, len(word)):
        if _reducible(context, word[:i]) and _reducible(context, word[i:]):
            context[word] = True
            return True
    context[word] = False
    return False

def reducible(word):
    context = {}
    return _reducible(context, word)