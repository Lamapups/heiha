#!/bin/bash

PDFINPUT=$1

# Extracting all pages except the first page from $PDFINPUT
# and storing them as 2page.pdf, 3page.pdf etc.
pdfseparate -f 2 $PDFINPUT %dpage.pdf;

# Counting the pages that were produced by the pdfseparate
# starting with 1 because the the first page was deleted
PAGECOUNT=1
for page in *page.pdf
do
	((PAGECOUNT++))
done

# if there is only one page left,
# it can be renamed to output and the script is done
if [ $PAGECOUNT -eq 2 ]
then
	mv 2page.pdf output.pdf
fi

if [ $PAGECOUNT -gt 2 ]
then
	# Creating a string containing the names of all pages
	ALLPAGES=""
	for ((INDEX=2; $INDEX <= $PAGECOUNT; INDEX++))
	do
		CURRENTPAGE=$INDEX"page.pdf"
		ALLPAGES="$ALLPAGES $CURRENTPAGE"
	done

	# Uniting all pages
	# The output is the same as the input, only the first page is removed
	pdfunite $ALLPAGES output.pdf

	# Removing all single pages and the input
	rm $ALLPAGES
	rm $PDFINPUT

	# Renaming the output so that it has the name of the original file
	mv output.pdf $PDFINPUT
fi
