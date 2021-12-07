number = "%%.:..%.::.%:.:"

def find(l, element):
  try:
    return l.index(element)
  except ValueError:
    return -1

def generateCFirst(listA):
    size = len(str(listA[-1]))
    return list(map(lambda x: x.rjust(size, '.'), listA))

def generateB(listC):
    return list(map(lambda x: ':' + x, reversed(listC)))

def generateCSecond(listC):
    return list(map(lambda x: '%' + x, listC))

def generate(listA):
    while len(listA) < 200000000:
        listC = generateCFirst(listA)
        listB = generateB(listC)
        index = find(listB, number)
        if index >= 0:
            return len(listA) + index
        listC = generateCSecond(listC)
        index = find(listC, number)
        if index >= 0:
            return len(listA) + len(listB) + index
        listA.extend(listB)
        listA.extend(listC)
    print(listA)
    return -1

print(generate(['.', ':', '%']))
