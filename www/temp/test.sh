TITLE=$1

if test $TITLE
then
	echo '1a title'
	echo $TITLE
	echo "



	"
#else if test !TITLE
#then
	#echo 'no '$TITLE
else
	echo '1no title'
	echo $TITLE
fi

if test -z $TITLE
then
	echo '2no title'
	echo $TITLE
#else if test !TITLE
#then
	#echo 'no '$TITLE
else
	echo '2a title'
	echo $TITLE
fi

