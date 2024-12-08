import sys


filename = sys.argv[1]
start = sys.argv[2]
end = sys.argv[3]


class Station:
    def __init__(self, price, free_minutes, price_per_minute):
        self.price = price
        self.free_minutes = free_minutes
        self.price_per_minute = price_per_minute

    def __repr__(self):
        return f"Station({self.price}, {self.free_minutes}, {self.price_per_minute})"


with open(filename) as f:
    stations = []
    connections = {}
    for line in f:  # nacitani stanic
        if line.strip() == "":  # konec vyctu stanic
            break

        price, free_minutes, price_per_minute = line.split(" ")
        stations.append(Station(int(price), int(free_minutes), int(price_per_minute)))

    for line in f:  # nacitani cest
        x, y, distance = line.split(" ")
        if int(x) not in connections:
            connections[int(x)] = []
        if int(y) not in connections:
            connections[int(y)] = []

        connections[int(x)].append((int(y), int(distance)))


import heapq


def dijkstra(start, end, stations, connections):
    queue = [  # fronta prozkoumanych cest; cesta je seznam dvojic (cislo stanice, cislo stanice kola)
        (
            stations[start].price,
            [(start, start)],
            stations[start].free_minutes,
            stations[start].price_per_minute,
        )
    ]

    while queue:
        (
            current_price,
            current_path,
            current_free_minutes,
            current_price_per_minute,
        ) = heapq.heappop(queue)

        current_node, current_bike = current_path[-1]

        if current_node == end:  # jsme na konci
            return current_price, current_path

        for neighbor, time in connections[current_node]:  # bez presednuti na nove kolo
            if current_free_minutes < time:
                new_current_free_minutes = 0
                time = time - current_free_minutes
            else:
                new_current_free_minutes = current_free_minutes - time
                time = 0

            neighbor_price = current_price + time * current_price_per_minute

            heapq.heappush(
                queue,
                (
                    neighbor_price,
                    current_path + [(neighbor, current_bike)],
                    new_current_free_minutes,
                    current_price_per_minute,
                ),
            )

        if current_bike != current_node:  # presednuti na nove kolo
            new_current_price = current_price + stations[current_node].price
            heapq.heappush(
                queue,
                (
                    new_current_price,
                    current_path + [(current_node, current_node)],
                    stations[current_node].free_minutes,
                    stations[current_node].price_per_minute,
                ),
            )

    return float("inf"), []


cost, path = dijkstra(int(start), int(end), stations, connections)
print(cost, len(set([bike for _, bike in path])), path)
