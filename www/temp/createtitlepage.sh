#!/bin/bash

TITLE=$1
SUBTITLE=$2
AUTHOR=$3
DATE=$4
TYPE=$5
UNIVERSITY=$6
EMAIL=$7
PDFINPUT=$8

# Generate the titlepage using LaTeX
# The document is created in 5 parts: OPTION1, OPTION2 etc.
# CONTENT2 and CONTENT4 hold the user input.
# Eventually they are merged and stored in a file
# Then pdflatex is run against the file.
# Then the new titlepage is merged with a PDF document supplied by the user

CONTENT1="
	\documentclass[12pt]{article}

	\usepackage[a4paper,top=2.5cm,bottom=2.5cm,left=3cm,right=3cm]{geometry}

	\usepackage{setspace}

	\usepackage[ngerman]{babel}
	\usepackage[utf8]{inputenc}
	\usepackage[T1]{fontenc}
	\usepackage{lmodern}
	\usepackage{amsmath}

	\begin{document}

	\begin{titlepage}

		\vfill {"


# Generate the upper left part of the page
# It could look like this:
	#\noindent "$UNIVERSITY"\\
	#\noindent "$AUTHOR"\\
	#\noindent "$EMAIL"\\

CONTENT2=""

if test $UNIVERSITY
then
	CONTENT2=$CONTENT2"
	\noindent "$UNIVERSITY"\\\\
	"
fi

if test $AUTHOR
then
	CONTENT2=$CONTENT2"
	\noindent "$AUTHOR"\\\\
	"
fi

if test $EMAIL
then
	CONTENT2=$CONTENT2"
	\noindent "$EMAIL"\\\\
	"
fi

CONTENT3="
		}

	  \begin{center}
		\vfill
	"

# Generate the centered part of the page
# It could look like this:
	#\Large{\textbf{\textsf{"$TYPE"}}} \\[1cm]
	#\LARGE{\textbf{\textsf{"$TITLE"}}} \\
	#\Large{\textbf{\textsf{"$SUBTITLE"}}} \\[1cm]
	#\Large{\textbf{\textsf{"$DATE"}}}


CONTENT4=""

if test $TYPE
then
	CONTENT4=$CONTENT4"
	\Large{\textbf{\textsf{"$TYPE"}}} \\\\[1cm]
	"
fi

if test $TITLE
then
	CONTENT4=$CONTENT4"
	\LARGE{\textbf{\textsf{"$TITLE"}}}"
		
	# If a subtitle follows leave a smaller vertical space
	if test $SUBTITLE
	then
		CONTENT4=$CONTENT4"\\\\
		"
	else
		CONTENT4=$CONTENT4"\\\\[1cm]
		"
	fi
fi

if test $SUBTITLE
then
	CONTENT4=$CONTENT4"
	\Large{\textbf{\textsf{"$SUBTITLE"}}} \\\\[1cm]
	"
fi

if test $DATE
then
	CONTENT4=$CONTENT4"
	\Large{\textbf{\textsf{"$DATE"}}}
	"
fi

CONTENT5="
	\end{center}

	\end{titlepage}
	\end{document}
	"

# Concatenate the 5 content strings to the final string
FINALCONTENT=$CONTENT1$CONTENT2$CONTENT3$CONTENT4$CONTENT5

echo $CONTENT4
echo $FINALCONTENT
echo $FINALCONTENT > newtitlepage.tex

# Run pdflatex against the newt titlepage to compile it
# pdflatex newtitlepage.tex

# Rename the user's PDF file (with no titlepage)
# mv $PDFINPUT contentpages.tex

# Merge the new titlepage with the user's file and save it under the original name
# pdfunite newtitlepage.pdf contentpages.pdf $PDFINPUT 

# Remove the auxiliary contentpages.pdf and all the newtitlepage.* files (.tex, .aux, .pdf etc.)
#rm newtitlepage.* contentpages.pdf
