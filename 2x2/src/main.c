/*
 * main.c
 *
 *  Created on: 12 juil. 2010
 *      Author: petitl
 */

#include <stdio.h>
#include <stdlib.h>

#include "cube.h"
#include "searcher.h"


Move* parseArgs(char* argv[], int length) {
	Move* scramble = (Move*) malloc((length-1) * sizeof(Move));
	for(int i = 1 ; i < length ; i++) {
		if(argv[i][0] == 'R')
			scramble[i-1] = 0;
		else if(argv[i][0] == 'U')
			scramble[i-1] = 3;
		else if(argv[i][0] == 'F')
			scramble[i-1] = 6;
		else {
			printf("Erreur parsing...");
			free(scramble);
			exit(2);
		}
		if(argv[i][1] == '2')
			scramble[i-1] += 1;
		else if(argv[i][1] == '\'')
			scramble[i-1] += 2;
		else if(argv[i][1] == '\0')
			continue;
		if(argv[i][2] != '\0')
			printf("Ignoring trailing characters...");

		//printf("Parsing arg: %s %d\n", argv[i], scramble[i-1]);
	}
	return scramble;
}
// R' F2 R2 U F' R F2 R U2 F' U'
int main(int argc, char *argv[]) {
	//Move* scramble = parseArgs(argv, argc);
	int result[10] = {0,0,0,0,0,0,0,0,0,0};
	int solves = 0;
	int min = 0;
	int max = 4000000;
	for(int perm = 0 ; perm < 5040 ; perm++) {
		for(int orient = 0 ; orient < 729 ; orient++) {
			if(solves++ < min) continue;
			if(solves >= max) break;
			int res = search(orient, perm, ANY, WHITE);
			if(res >= 0)
				result[res]++;
			if(solves % 20000 == 19999) //break;
				printf("%.2f%%\n", ((float)solves*100)/3674160);
		}
		if(solves >= max) break;
		//if(solves % 20000 == 19999)
			//break;
	}

	for(int i = 0 ; i < 10 ; i++) {
		printf("%d\t %d\n", i, result[i]);
	}
	//free(scramble);

}
