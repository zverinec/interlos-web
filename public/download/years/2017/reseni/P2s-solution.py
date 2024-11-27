import networkx as nx

n = 1000
w = 5000
G = nx.DiGraph()

G.add_node("source", demand=-w)
G.add_node("target", demand=w)
G.add_nodes_from(range(n))

with open("Pf-lossro.txt") as f:
	for i, available in enumerate(next(f).split()):
		G.add_edge("source", i, capacity=int(available))

	for i, required in enumerate(next(f).split()):
		G.add_edge(i, "target", capacity=int(required))

	for i, line in enumerate(f):
		for j, cost in enumerate(line.split()):
			G.add_edge(i, j, weight=int(cost))

print("minimal cost: ", nx.min_cost_flow_cost(G))
