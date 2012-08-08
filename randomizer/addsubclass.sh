#!/bin/bash
CYCLES=/Users/petitl/Sites/cube/nimagecube/cycles.php
standardize() {
        js -f /Users/petitl/Documents/Programmation/gripper/algmanipulation.js -e "print(standardize(\"$1\"))"
}
invert() {
        js -f /Users/petitl/Documents/Programmation/gripper/algmanipulation.js -e "print(invert_alg(\"$1\"))"
}

SET=$1
OLD=""
echo "var $SET={};" > $SET.js;
for i in `cat $SET.gen | sed 's/ //g' | grep -v '^$'`; do
	STDALG=`standardize "$i"`
	INVALG=`invert "$STDALG" | sed 's/ *$//g'`
	RES=( `php $CYCLES "$INVALG"` )
	if [[ $RES != $OLD ]]; then
		echo "$SET[\""${RES[0]}"\"] = [];";
	fi
	echo -n "$SET[\""${RES[0]}"\"]["${RES[2]}"] = {"
	echo "gen:" \"$INVALG\"", auf:" ${RES[1]} ", premove:" ${RES[3]} "};"
	OLD=${RES[0]};
done >> $SET.js
