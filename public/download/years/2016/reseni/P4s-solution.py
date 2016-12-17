def hated_mooses(moose_order):
    hated_mooses = [0]
    
    for i in range (len(moose_order)):
        for j in range(i - 1, -1, -1):
            if moose_order[i] % moose_order[j] == 0:
                hated_mooses.append(moose_order[j])
                break
            if j == 0:
                hated_mooses.append(0)
                
    return hated_mooses                   

def change_order(moose_order):
    new_moose_order = hated_mooses(moose_order)
    
    for i in range(len(moose_order)):
        if new_moose_order[i] != 0:
            index = moose_order.index(new_moose_order[i])
            moose_order.insert(index, moose_order.pop(i))
            
    return new_moose_order

def run_moose_run(n):
    counter = -1
    check = []
    hated_mooses = []
    
    moose_order = list(range(1, int(n) + 1))
    
    for i in range(len(moose_order)):
        check.append(0)
    
    while hated_mooses != check:
        hated_mooses = change_order(moose_order)
        counter += 1
        
    print(counter)
