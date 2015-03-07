#!/bin/bash

VAR=$1

if test $VAR -eq 1
then
	echo $VAR' = 1'
else if test $VAR -gt 1
then
	echo $VAR' > 1'
else
	echo $VAR' < 1'
fi
fi
