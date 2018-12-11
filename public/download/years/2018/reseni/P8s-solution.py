'''
Dequeues queue until it encounter an unprocessed one.
It marks the customer as processed and retunrs him.
'''
def processCustomer(queue):
    customer = queue.pop(0)[0]
    while processed[customer]:
        customer = queue.pop(0)[0]
    processed[customer] = True
    return customer

import csv
import operator

bananaList = []
orangeList = []
trabiList = []

processed = {}

order = [] 

#Reads the input file and aranges the customers into separate queues.
with open("input.csv", "r") as inputfile:
    reader = csv.reader(inputfile, delimiter=';')
    next(reader, None) #skip the header
    tempBanana = []
    tempOrange = []
    tempTrabi = []
    for row in reader:
        tempBanana.append((row[0],row[1]))
        tempOrange.append((row[0],row[2]))
        tempTrabi.append((row[0],row[3]))
        processed[row[0]] = False
    bananaList = sorted(tempBanana, key=lambda item: (item[1]), reverse=True)
    orangeList = sorted(tempOrange, key=lambda item: (item[1]), reverse=True)
    trabiList = sorted(tempTrabi, key=lambda item: (item[1]), reverse=True)

#Reads the file with ordering of goods.
order = [line.rstrip('\n') for line in open('order.txt')]
order.reverse()

#Follows the ordering of goods and process each customer.
while order and True:
    current = order.pop()
    customer = None
    if(current == "banana"):
        customer = processCustomer(bananaList)
    if(current == "orange"):
        customer = processCustomer(orangeList)
    if(current == "trabi"):
        customer = processCustomer(trabiList)
    if not order:
        print(customer)
    # 'Sheena Sarkis' as a last customer is the password
        
