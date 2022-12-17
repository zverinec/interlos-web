from collections import deque
from heapq import heappush, heappop

DR = [-1, 0, 1, 0]
DC = [0, -1, 0, 1]

class Vertex:
    def __init__(self):
        self.dist = None
        self.next = []

def bfs(maze, si, sj):
    if maze[si][sj] == "#":
        return None
    r = len(maze)
    c = len(maze[0])
    dist = [[None] * c for i in range(r)]
    dist[si][sj] = 0
    q = deque()
    q.append((si, sj))
    while q:
        i, j = q.popleft()
        for dr, dc in zip(DR, DC):
            i2, j2 = i + dr, j + dc
            if 0 <= i2 < r and 0 <= j2 < c and maze[i2][j2] != "#" and dist[i2][j2] == None:
                dist[i2][j2] = dist[i][j] + 1
                q.append((i2, j2))
    return dist

def dijkstra(graph, start):
    pq = [(0, start)]
    while pq:
        dist, v = heappop(pq)
        if graph[v].dist is not None:
            continue
        print(v, "|", dist)
        graph[v].dist = dist
        for s, d in graph[v].next:
            if graph[s].dist is None:
                heappush(pq, (dist + d, s))

def addEdge(graph, v1, v2, dist, checkCoordinate):
    if checkCoordinate(*v1) and checkCoordinate(*v2) and dist is not None:
        graph.setdefault(v1, Vertex()).next.append((v2, dist))
        graph.setdefault(v2, Vertex()).next.append((v1, dist))


def main(filename):
    with open(filename) as f:
        r, c, m, n = [int(x) for x in f.readline().split()]
        maze = [line.strip() + line[0] for line in f]
    maze.append(maze[0])
    assert len(maze) == r + 1
    for row in maze:
        assert len(row) == c + 1
    
    distleft = [bfs(maze, i, 0) for i in range(r + 1)]
    for elem in distleft:
        if elem is not None:
            assert len(elem) == r + 1
            assert len(elem[0]) == c + 1
    distup = [bfs(maze, 0, j) for j in range(c + 1)]
    for elem in distup:
        if elem is not None:
            assert len(elem) == r + 1
            assert len(elem[0]) == c + 1
    distright = [bfs(maze, i, c) for i in range(r + 1)]
    for elem in distright:
        if elem is not None:
            assert len(elem) == r + 1
            assert len(elem[0]) == c + 1
    graph = {(0, 0): Vertex(), (r * m - 1, c * n - 1): Vertex()}
    
    goodRows = [i for i in range(r + 1) if maze[i][c] != "#"]
    goodCols = [j for j in range(c + 1) if maze[r][j] != "#"]
    
    def check(i, j):
        return 0 <= i < r * m and 0 <= j < c * n
    
    for i in range(r, r * m, r):
        for j in range(c * n):
            if distup[j % c] is not None:
                #z hornej na pravu stranu
                for i2 in goodRows:
                    addEdge(graph, (i, j), (i2 + i, j // c * c + c), distup[j % c][i2][c], check)
                #z hornej na dolnu stranu
                for j2 in goodCols:
                    addEdge(graph, (i, j), (i + r, j // c * c + j2), distup[j % c][r][j2], check)
    
    for j in range(c, c * n, c):
        for i in range(r * m):
            if distleft[i % r] is not None:
                #z lavej na dolnu stranu
                for j2 in goodCols:
                    addEdge(graph, (i, j), (i // r * r + r, j2 + j), distleft[i % r][r][j2], check)
                    #z lavej na hornu stranu
                    addEdge(graph, (i, j), (i // r * r, j2 + j), distleft[i % r][0][j2], check)
                #z lavej na pravu stranu
                for i2 in goodRows:
                    addEdge(graph, (i, j), (i // r * r + i2, j + c), distleft[i % r][i2][c], check)
            if distright[i % r] is not None:
                #z pravej na dolnu stranu
                for j2 in goodCols:
                    addEdge(graph, (i, j), (i // r * r + r, j2 + j - c), distright[i % r][r][j2], check)
    
    #zo startu doprava
    for i in range(r + 1):
        if maze[i][c] != "#":
            addEdge(graph, (0, 0), (i, c), distup[0][i][c], check)
    #zo startu dole
    for j in range(c + 1):
        if maze[r][j] != "#":
            addEdge(graph, (0, 0), (r, j), distup[0][r][j], check)
    
    #do ciela zlava
    for i in range(r * m - r, r * m):
        if maze[i % r][0] != "#":
            addEdge(graph, (i, c * n - c), (r * m - 1, c * n - 1), distleft[i % r][r - 1][c - 1], check)
    #do ciela zhora
    for j in range(c * n - c, c * n):
        if maze[0][j % c] != "#":
            addEdge(graph, (r * m - r, j), (r * m - 1, c * n - 1), distup[j % c][r - 1][c - 1], check)
    
    dijkstra(graph, (0, 0))
    return graph[(r * m - 1, c * n - 1)].dist

if __name__ == "__main__":
    filename = "megamaze.txt"
    print(main(filename))