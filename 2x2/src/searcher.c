/*
 * searcher.c
 *
 *  Created on: 13 juil. 2010
 *      Author: petitl
 */

#include "searcher.h"

#include <stdio.h>

int alert = 0;
int searchAtLength(int orient, int perm, int limit, Criteria crit,
		ColorNeutral cn) {
	initCube(orient, perm);
	//if(alert)
	//printState(1);
	int limitMax = limit;
	int found = -1;
	int color = 0;
	while (1) {
		color = isFaceMade(crit, cn);
		if (color >= 0) {
			limitMax = getDepth() - 1;
			found = color;

			if (alert) {
				printState(0);
				break;
			}
			//printState(0);
		}
		Move m = nextMove(limitMax);

		while (m < 0) {
			if (undo())
				break;
			else
				m = nextMove();
		}
		if (m < 0)
			break;
		rotateStickers(m);
	}

	if (found >= 0) {
		return limitMax + 2;
	}
	return -1;
}

int search(int orient, int perm, Criteria crit, ColorNeutral cn) {
	int max = 4;
	int result;
	do {
		alert = 0;
		if (max > 6) {
			alert = 1;
			printf("(%d, %d) Searching at depth %d...\n", orient, perm, max + 1);
		}
		result = searchAtLength(orient, perm, max++, crit, cn);
	} while (result < 0 && max < 11);
	if (alert) {
		//FIND OUT WHO IS THIS FUCKER !
		max = 10;
		searchAtLength(orient, perm, max, CUBE, WHITE);
		printState(2);
	}
	return result;
}

