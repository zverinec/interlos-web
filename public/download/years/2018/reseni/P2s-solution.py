from collections import deque
import json

with open('los.json', 'r') as fp:
    data = json.load(fp)

nodes_count = data['nodes_count']
nodes = list(range(nodes_count))
traces = data['traces']
order = data['order']
target = data['target']

dag_dfs_dist = [-1 for _ in range(nodes_count)]

def dag_dfs(graph, start, end):
    if start == end:
        dag_dfs_dist[end] = 0
        return 0

    for idx, is_edge in enumerate(graph[start]):
        if is_edge and order[idx] > order[start]:
            if dag_dfs_dist[idx] != -1:
                dag_dfs_dist[start] = max(dag_dfs_dist[start], dag_dfs_dist[idx] + 1)
            else:
                dis = max(dag_dfs_dist[start], dag_dfs(graph, idx, end) + 1)
                dag_dfs_dist[start] = dis

    return dag_dfs_dist[start]

def dag_bfs(graph, start, end, dag_bfs_seen):
    dag_bfs_seen[start] = True
    queue = deque([(start, 0)])

    while queue:
        u, dist = queue.popleft()

        if u == end:
            return dist

        for v, is_edge in enumerate(graph[u]):
            if is_edge and order[v] > order[u] and not dag_bfs_seen[v]:
                dag_bfs_seen[v] = True
                queue.append((v, dist+1))
    raise "no path exists"

def overapprox():
    # all paths possible
    overapprox_graph = [ [ 1 for _ in range(nodes_count) ] for _ in range(nodes_count) ]
    # except diagonal = no reflexivity
    for i in range(nodes_count):
        overapprox_graph[i][i] = 0

    # overapproximation
    for trace in traces:
        for i in range(len(trace) - 1, -1, -1):
            for j in range(i - 1, -1, -1):
                fr = trace[i]
                to = trace[j]
                overapprox_graph[fr][to] = 0

    return overapprox_graph


def underapprox():
    # none path possible
    underapprox_graph = [ [ 0 for _ in range(nodes_count) ] for _ in range(nodes_count) ]

    # underapproximation
    for trace in traces:
        for i in range(len(trace) - 1):
            fr = trace[i]
            to = trace[i+1]
            underapprox_graph[fr][to] = 1

    return underapprox_graph


def main():
    o = overapprox()
    print("Maximal distance: ")
    print(dag_dfs(o, 0, target))

    u = underapprox()
    print("Minimal distance: ")
    dag_bfs_seen = [False for _ in range(nodes_count)]
    print(dag_bfs(u, 2, target, dag_bfs_seen))


if __name__ == "__main__":
    main()
