/*
 * cube.h
 *
 *  Created on: 12 juil. 2010
 *      Author: petitl
 */

#ifndef CUBE_H_
#define CUBE_H_

typedef char Move;
typedef enum {
	ANY, FACE, EG1, EG2, NOTEG2, CUBE
} Criteria;
typedef enum {
	NEUTRAL, WHITE, OPPOSITE
} ColorNeutral;

void initCube(int orient, int perm);
int rotateStickers(Move m);
Move nextMove();
int undo();
void printState();
int isFaceMade(Criteria crit, ColorNeutral cn);
int getDepth();

#endif /* CUBE_H_ */
