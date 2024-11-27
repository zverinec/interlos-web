import urllib.request
import urllib.error
import re

BASE_LINK = "https://ipfs.io/ipfs/QmXoypizjW3WknFiJnKLwHCnL72vedxjQkDDP1mXWo6uco/wiki/"

savedLinks = {}

def get_links_from_link(link):
	if link in savedLinks:
		return savedLinks[link]

	try:
		html = get_html(link)
		links = get_links(html)

	except urllib.error.HTTPError:
		links = []

	savedLinks[link] = links
	return links

def get_html(link):
	with urllib.request.urlopen(link) as response:
		html = response.read()
	return str(html)

def get_links(html):
	links = re.findall(r"<a href=\"([^(http)#\"].*?)\"", html)
	return links

def iterative_dfs(current, target, depth):
	if current == target:
		print(current)
		return 1
	if depth == 0:
		return 0

	links = get_links_from_link(BASE_LINK + current)
	#print(current, links)

	for link in links:
		if iterative_dfs(link, target, depth - 1) == 1:
			print(current)
			return 1
	return 0

i = 1

while iterative_dfs("Moose.html", "Computer_programming.html", i) != 1:
	i += 1
	print(i)