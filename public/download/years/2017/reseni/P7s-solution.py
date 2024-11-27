#!/usr/bin/env python3

def knapsack(X, n, w):
  """
  Solve the 01-Knapsack problem(subset sum essentially) in Θ(n*w) time and space.

  Uses dynamic programming algo from:
  https://en.wikipedia.org/wiki/Knapsack_problem#0.2F1_knapsack_problem
  Backtrack algo to get the vector of items used from:
  http://cse.unl.edu/~goddard/Courses/CSCE310J/Lectures/Lecture8-DynamicProgramming.pdf
  
  :param X: List of item weights.
  :param n: Number of items.
  :param w: Size of the knapsack.
  :rtype: int, List[int]
  :return: The amount which can be fitted into the knapsack, and the binary vector of items it consists of.
  """
  m = []
  for i in range(n + 1):
	ml = []
	for j in range(w + 1):
	  ml.append(0)
	m.append(ml)

  for i in range(1, n + 1):
	for j in range(w + 1):
	  if X[i - 1] > j:
		m[i][j] = m[i-1][j]
	  else:
		no = m[i-1][j]
		yes = m[i-1][j-X[i - 1]] + X[i - 1]
		if yes > no:
		  m[i][j] = yes
		else:
		  m[i][j] = no
  i = n
  k = w
  b = [0 for i in range(n)]
  while i > 0 and k > 0:
	if m[i][k] != m[i - 1][k]:
	  b[i - 1] = 1
	  i -= 1
	  k -= X[i]
	else:
	  i -= 1
  return m[n][w], b

if __name__ == "__main__":
  # read input
  X = list(map(int, input().split(" ")))
  C = list(map(int, input().split(" ")))
  n = len(C)
  results = []
  for i in range(n):
	c = C[i]
	k, b = knapsack(X, len(X), c)
	# Check whether we can fill the i-th sack full of items.
	if c == k:
	  # yep
	  results.append(1)
	else:
	  # nope
	  results.append(0)
  print("".join(map(str, results)))
