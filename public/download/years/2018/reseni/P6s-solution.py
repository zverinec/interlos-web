#!/usr/bin/env python3

from functools import lru_cache

def overlaps(a: str, b: str):
	"""
	Compute the overlaps of two words.
	"""
	if a == b:
		return 0, 0
	prefix = 0
	suffix = 0
	for i in range(1, min(len(a), len(b))):
		if suffix == 0 and a[-i:] == b[:i]:
			suffix = i
		if prefix == 0 and a[:i] == b[-i:]:
			prefix = i
		if suffix != 0 and prefix != 0:
			break
	return prefix, suffix 

words = None			#The list of words.
lengths = None		  #The list of the lengths of words.
overlap_matrix = None   #The overlap matrix. [i][j] gives the overlap between the i-th and j-th words, in that order.

@lru_cache(maxsize=53000)
def longest(prefix_index, suffix_index, usable_words):
	"""
	Find the longest sequences of words from `usable_words` that can be prepended and
	appended to a word beginning with word at `prefix_index` and ending with the word at
	`suffix_index`.
	
	E.g. longest(1,1, <all the words but 1>) is the longest constructable word
	that contains the word at index 1.
	"""
	max_len = 0
	longest_prefixes = ()
	longest_suffixes = ()
	if usable_words == 0:
		# We cannot prepend or append anything, bail out.
		return longest_prefixes, longest_suffixes, max_len

	for word_index in range(len(words)):
		if ((1 << word_index) & usable_words) == 0:
			continue
		# See if we can prepend or append word at word_index.
		prefix_match = overlap_matrix[prefix_index][word_index]
		suffix_match = overlap_matrix[word_index][suffix_index]
		if prefix_match == 0 and suffix_match == 0:
			continue
		this_len = lengths[word_index]
		if prefix_match != 0:
			# We can prepend this word, see if we get something longer than current max.
			result_prefixes, result_suffixes , result_len = longest(word_index, suffix_index, usable_words ^ (1 << word_index))
			if max_len == 0 or result_len + this_len - prefix_match > max_len:
				max_len = result_len + this_len - prefix_match
				longest_prefixes = result_prefixes + (word_index,)
				longest_suffixes = result_suffixes
		if suffix_match != 0:
			# We can append this word, see if we get something longet than current max.
			result_prefixes, result_suffixes, result_len = longest(prefix_index, word_index, usable_words ^ (1 << word_index))
			if max_len == 0 or result_len + this_len - suffix_match > max_len:
				max_len = result_len + this_len - suffix_match
				longest_prefixes = result_prefixes
				longest_suffixes = (word_index,) + result_suffixes
	return longest_prefixes, longest_suffixes, max_len

def reconstruct(parts):
	"""
	Reconstruct the word from indices of its overlaping parts.
	"""
	word = words[parts[0]]
	for i in range(1, len(parts)):
		p_word = parts[i - 1]
		n_word = parts[i]
		word = word + words[n_word][overlap_matrix[n_word][p_word]:]
	return word

def solve(strings):
	global words, lengths, overlap_matrix
	# Sort by length, to get on the right path faster
	words = sorted(strings, key=len, reverse=True)
	lengths = [len(word) for word in words]
	n = len(words)
	# Construct overlap matrix
	overlap_matrix = [[0 for _ in range(n)] for _ in range(n)]
	for i, start in enumerate(words):
		for j, end in enumerate(words):
			prefix, suffix = overlaps(start, end)
			overlap_matrix[i][j] = prefix
			overlap_matrix[j][i] = suffix

	# Solve recursively
	max_word = None
	max_len = 0
	for i in range(n):
		prefixes, suffixes, length = longest(i, i, ((1 << n) - 1) ^ (1 << i))
		complete_length = length + lengths[i]
		if prefixes:
			complete_length = complete_length - overlap_matrix[prefixes[-1]][i]
		if suffixes:
			complete_length = complete_length - overlap_matrix[i][suffixes[0]]
		if max_len == 0 or complete_length > max_len:
			parts = prefixes + (i,) + suffixes
			max_word = reconstruct(parts)
			max_len = complete_length
			print("new longest word: {}, {}".format(max_word, max_len))

	return max_word

if __name__ == "__main__":
	num_lines = int(input())
	lines = [input() for _ in range(num_lines)]
	result = solve(lines)
	print(result)
