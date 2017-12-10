#!/usr/bin/env python3

from functools import lru_cache

class Node:
    """A node that has a left and right child, and a value of it (a list of probabilities)."""

    def __init__(self, value, left=None, right=None):
        self.value = value
        self.left = left
        self.right = right

    @property
    def leaf(self):
        return self.left is None and self.right is None

    def __hash__(self):
        """Make two nodes equal only if they are actually the same object."""
        return id(self)

    def __eq__(self, other):
        """Make eq the same as hash."""
        return self is other

    def __ne__(self, other):
        return not self.__eq__(other)

    def __repr__(self):
        return "Node(%i)<%i>" % (self.value, id(self))

def make_tree(rows):
    """Build a tree of Nodes from the rows. (It's not really a tree but hey, it works to think about it that way)"""
    tree = [[Node(value) for value in row] for row in rows]
    n = len(rows)
    for i in range(n):
        row = tree[i]
        if i < n - 1:
            next_row = tree[i + 1]
            for j in range(len(row)):
                node = row[j]
                node.left = next_row[j]
                node.right = next_row[j + 1]
    return tree

@lru_cache(maxsize=None)
def galton(node):
    """Recursively compute the galton probabilities of a given node. Uses
       LRU cache, and caches on the nodes, so that there is only one instance of a Node in memory
       for a given node in the graph."""
    if node.leaf:
        # Take the leaf values.
        if node.value == -1:
            return [1.0, 0.0]
        elif node.value == 1:
            return [0.0, 1.0]
        else:
            return [0.5, 0.5]
    if node.value == -1:
        # Only take the left subtree probabilities, we cannot go right from this node.
        result = list(galton(node.left))
        result.append(0.0)
        return result
    if node.value == 1:
        # Only take the right subtree probabilities, we cannot go left from this node.
        result = list(galton(node.right))
        result.insert(0, 0.0)
        return result

    # Combine the probabilities.
    left = galton(node.left)
    right = galton(node.right)
    res_left = list(left)
    res_left.append(0.0)
    res_right = list(right)
    res_right.insert(0, 0.0)
    result = [(res_left[i] + res_right[i])/2 for i in range(len(res_left))]
    return result

if __name__ == "__main__":
    n = int(input())
    import sys
    sys.setrecursionlimit(n * 5)
    rows = [list(map(int, input().split())) for _ in range(n)]
    tree = make_tree(rows)
    results = galton(tree[0][0])
    print("".join("{0:.4f}".format(r)[2:5] for r in results))