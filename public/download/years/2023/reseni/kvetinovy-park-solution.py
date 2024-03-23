import math
from typing import List, Tuple

Point = Tuple[float, float]


def is_covered(nodes: List[Point], path_width: int, point: Point) -> bool:
    return is_node_covered(nodes, path_width, point) or is_edge_covered(
        nodes, path_width, point
    )


def is_node_covered(nodes: List[Point], path_width: int, point: Point) -> bool:
    for node in nodes:
        if distance(node, point) <= path_width:
            return True
    return False


def is_edge_covered(nodes: List[Point], path_width: int, point: Point) -> bool:
    for i in range(len(nodes) - 1):
        node1 = nodes[i]
        node2 = nodes[i + 1]
        closest, t = closest_point_to_edge(node1, node2, point)
        if 0 <= t <= 1 and distance(point, closest) <= path_width:
            return True
    return False


def distance(point1: Point, point2: Point) -> float:
    x1, y1 = point2
    x2, y2 = point1
    return math.hypot(x1 - x2, y1 - y2)


def closest_point_to_edge(
    point1: Point, point2: Point, the_point: Point
) -> Tuple[Point, float]:
    x1, y1 = point2
    x2, y2 = point1
    x, y = the_point
    t = (x2 * (x2 - x1) - x * (x2 - x1) + y2 * (y2 - y1) - y * (y2 - y1)) / (
        (x2 - x1) * (x2 - x1) + (y2 - y1) * (y2 - y1)
    )
    return (x2 - (x2 - x1) * t, y2 - (y2 - y1) * t), t


def count(path: List[Point], path_width: int, width: int, height: int) -> int:
    total = 0

    for y in range(height + 1):
        for x in range(width + 1):
            if not is_covered(path, path_width, (x, y)):
                total += 1

    return total


def load_nodes(filename: str) -> List[Point]:
    nodes: List[Point] = []
    with open(filename) as file:
        for line in file:
            one, two = line.strip().split()
            nodes.append((int(one), int(two)))

    return nodes


nodes = load_nodes("park.txt")
print(count(nodes, 5, 256, 256))
