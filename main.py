# Author: 105635
author = 53
with open('author.sh', 'w') as f:
	limit = 2000
	for i in range(author):
		f.write('nohup php author.php %d %d &\n' % (i * limit, limit))

# Paper: 31594
paper = 32
with open('paper.sh', 'w') as f:
	limit = 1000
	for i in range(paper):
		f.write('nohup php paper.php %d %d &\n' % (i * limit, limit))

# Author-paper link: 128989
paper = 100
with open('author_paper.sh', 'w') as f:
	limit = 2000
	for i in range(paper):
		f.write('nohup php author_paper.php %d %d &\n' % (i * limit, limit))
