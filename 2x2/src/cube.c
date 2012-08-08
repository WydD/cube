/*
 * cube.c
 *
 *  Created on: 12 juil. 2010
 *      Author: petitl
 */

#include "cube.h"
#include <stdio.h>

typedef enum {
	R, W, G, O, Y, B
} Colors;

typedef enum {
	Ri, Up, Fr
} Face;
typedef enum {
	S, D, P
} Mod;

Move stack[11] = { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 }; // Max move is 11 in HTM as proven by Jaap !
int idMove = -1;

char cube[24] = { };

const int const pos[3][8] = { { 1 + W * 4, 1 + G * 4, 1 + Y * 4, 2 + B * 4, 3
		+ W * 4, 3 + G * 4, 3 + Y * 4, 0 + B * 4 }, { 0 + G * 4, 0 + R * 4, 0
		+ B * 4, 0 + O * 4, 1 + G * 4, 1 + R * 4, 1 + B * 4, 1 + O * 4 }, { 2
		+ W * 4, 3 + O * 4, 1 + Y * 4, 0 + R * 4, 3 + W * 4, 1 + O * 4, 0 + Y
		* 4, 2 + R * 4 } };

inline void swap(int i, int j) {
	char tmp = cube[i];
	cube[i] = cube[j];
	cube[j] = tmp;
}
inline void swap3(int i, int j, int k) {
	char tmp = cube[i];
	cube[i] = cube[j];
	cube[j] = cube[k];
	cube[k] = tmp;
}
inline void swap4(int i, int j, int k, int l) {
	char tmp = cube[i];
	cube[i] = cube[j];
	cube[j] = cube[k];
	cube[k] = cube[l];
	cube[l] = tmp;
}

void internalRotateStickers(Face f, Mod mod) {
	if (mod == 0) {
		swap4(pos[f][0], pos[f][1], pos[f][2], pos[f][3]);
		swap4(pos[f][4], pos[f][5], pos[f][6], pos[f][7]);
		swap4(3 + f * 4, 1 + f * 4, 0 + f * 4, 2 + f * 4);
	} else if (mod == 1) {
		swap(pos[f][0], pos[f][2]);
		swap(pos[f][1], pos[f][3]);
		swap(pos[f][4], pos[f][6]);
		swap(pos[f][5], pos[f][7]);
		swap(0 + f * 4, 3 + f * 4);
		swap(1 + f * 4, 2 + f * 4);
	} else if (mod == 2) {
		swap4(pos[f][0], pos[f][3], pos[f][2], pos[f][1]);
		swap4(pos[f][4], pos[f][7], pos[f][6], pos[f][5]);
		swap4(3 + f * 4, 2 + f * 4, 0 + f * 4, 1 + f * 4);
	}
}

int rotateStickers(Move m) {
	stack[++idMove] = m;
	Face f = m / 3;
	Mod mod = m % 3;

	internalRotateStickers(f, mod);
	return 0;
}

Move nextMove(int depth) {
	if (idMove >= depth)
		return -1;
	Move current = stack[idMove + 1];
	++current;
	if (idMove >= 0) {
		Move past = stack[idMove];
		if (past / 3 == current / 3)
			current += 3;
	}
	if (current >= 9) // I've done all the possible stuff
		return -1;
	return current;
}
int undo() {
	Move current = stack[idMove];
	if (idMove < 0 || (idMove == 0 && current == 8))
		return 1;
	Face f = current / 3;
	Mod mod = current % 3;
	if (mod % 2 == 0)
		mod = (mod + 2) % 4;
	internalRotateStickers(f, mod);

	stack[idMove-- + 1] = -1;
	return 0;
}
void printS(int i) {
	if (cube[i] == W)
		printf("W");
	else if (cube[i] == G)
		printf("G");
	else if (cube[i] == R)
		printf("R");
	else if (cube[i] == B)
		printf("B");
	else if (cube[i] == Y)
		printf("Y");
	else if (cube[i] == O)
		printf("O");
}

void printState(int printCube) {
	if (printCube == 1) {
		printf("   ");
		printS(3 + B * 4);
		printS(2 + B * 4);
		printf("\n");
		printf("   ");
		printS(1 + B * 4);
		printS(0 + B * 4);
		printf("\n");
		printS(2 + O * 4);
		printS(0 + O * 4);
		printf(" ");
		printS(0 + W * 4);
		printS(1 + W * 4);
		printf(" ");
		printS(1 + R * 4);
		printS(3 + R * 4);
		printf("\n");
		printS(3 + O * 4);
		printS(1 + O * 4);
		printf(" ");
		printS(2 + W * 4);
		printS(3 + W * 4);
		printf(" ");
		printS(0 + R * 4);
		printS(2 + R * 4);
		printf("\n");
		printf("   ");
		printS(0 + G * 4);
		printS(1 + G * 4);
		printf("\n");
		printf("   ");
		printS(2 + G * 4);
		printS(3 + G * 4);
		printf("\n");
		printf("   ");
		printS(0 + Y * 4);
		printS(1 + Y * 4);
		printf("\n");
		printf("   ");
		printS(2 + Y * 4);
		printS(3 + Y * 4);
		printf("\n\n");
	}
	if (printCube <= 1) {
		printf("Sequence made: ");
		for (int i = 0; i <= idMove; i++) {
			Move m = stack[i];
			Face f = m / 3;
			if (f == 0)
				printf("R");
			else if (f == 1)
				printf("U");
			else if (f == 2)
				printf("F");
			Mod mod = m % 3;
			if (mod == 1)
				printf("2");
			if (mod == 2)
				printf("'");
			printf(" ");
		}
		printf("\n");
	}

	if (printCube == 2) {
		printf("Sequence made: ");
		for (int i = idMove; i >= 0 ; i--) {
			Move m = stack[i];
			Face f = m / 3;
			if (f == 0)
				printf("R");
			else if (f == 1)
				printf("U");
			else if (f == 2)
				printf("F");
			Mod mod = 2-m % 3;
			if (mod == 1)
				printf("2");
			if (mod == 2)
				printf("'");
			printf(" ");
		}
		printf("\n");
	}
}
//R, W, G, O, Y, B,
const int const adj[6][4] = { { 1 + W * 4, 3 + W * 4, 1 + G * 4, 3 + G * 4 }, {
		0 + R * 4, 1 + R * 4, 0 + G * 4, 1 + G * 4 }, { 2 + W * 4, 3 + W * 4, 1
		+ O * 4, 3 + O * 4 }, { 0 + W * 4, 2 + W * 4, 0 + G * 4, 2 + G * 4 }, {
		2 + G * 4, 3 + G * 4, 2 + O * 4, 3 + O * 4 }, { 0 + W * 4, 1 + W * 4, 0
		+ O * 4, 2 + O * 4 } };

int isFaceMade(Criteria crit, ColorNeutral cn) {
	for (Colors i = 0; i < 6; i++) {
		char col = cube[i * 4];
		if (cn == WHITE && col != W)
			continue;
		if (cn == OPPOSITE && !(col == W || col == Y))
			continue;

		if (col == cube[1 + i * 4] && cube[1 + i * 4] == cube[2 + i * 4]
				&& cube[2 + i * 4] == cube[3 + i * 4]) {
			if (crit == ANY)
				return i;
			char c1 = cube[adj[i][0]];
			char c2 = cube[adj[i][1]];
			char c3 = cube[adj[i][2]];
			char c4 = cube[adj[i][3]];
			if (c1 == c2 && c3 == c4) {
				if (crit == FACE || crit == NOTEG2)
					return i;
			} else if (c1 == (c2 + 3) % 6 && c3 == (c4 + 3) % 6) {
				if (crit == EG2)
					return i;
			} else if (crit == EG1 || crit == NOTEG2)
				return i;

			if (crit == CUBE) {
				int j = (i + 1) % 6; // An adjascent side
				if (!(cube[j * 4] == cube[1 + j * 4] && cube[1 + j * 4]
						== cube[2 + j * 4] && cube[2 + j * 4]
						== cube[3 + j * 4]))
					continue;
				j = (j + 1) % 6; // An adjascent side
				if (!(cube[j * 4] == cube[1 + j * 4] && cube[1 + j * 4]
						== cube[2 + j * 4] && cube[2 + j * 4]
						== cube[3 + j * 4]))
					continue;
				j = (j + 1) % 6; // An adjascent side
				if (!(cube[j * 4] == cube[1 + j * 4] && cube[1 + j * 4]
						== cube[2 + j * 4] && cube[2 + j * 4]
						== cube[3 + j * 4]))
					continue;
				return i;
			}
		}
	}
	return -1;
}

const int const corners[7][2] = { { 0 + O * 4, 1 + B * 4 }, { 0 + B * 4, 1 + R
		* 4 }, { 0 + G * 4, 1 + O * 4 }, { 0 + R * 4, 1 + G * 4 }, { 3 + O * 4,
		2 + G * 4 }, { 3 + G * 4, 2 + R * 4 }, { 3 + R * 4, 2 + B * 4 } };

void orientOnePiece(int corner, int type) {
	char color = W;
	if (corner >= 4)
		color = Y;
	int mod = 0;
	if (corner == 6)
		mod = 1;
	if (type == 1)
		swap3(corner % 4 + mod + color * 4, corners[corner][0],
				corners[corner][1]);
	else if (type == 2)
		swap3(corner % 4 + mod + color * 4, corners[corner][1],
				corners[corner][0]);

}
void orientCube(int orient) {
	int parity = 0;
	for (int i = 0; i < 6; i++) {
		parity += orient % 3;
		if (orient % 3 > 0)
			orientOnePiece(i, orient % 3);
		orient /= 3;
	}
	if (parity % 3 != 0)
		orientOnePiece(6, 3 - (parity % 3));
}
void permutCube(int perm) {
	char newCube[24] = { };
	// Fill yellow and white
	for (int i = 0; i < 4; i++)
		newCube[i + Y * 4] = Y;
	for (int i = 0; i < 4; i++)
		newCube[i + W * 4] = W;
	// AND The fixed corner
	newCube[3 + B * 4] = B;
	newCube[2 + O * 4] = O;

	char dispo[7] = { 1, 1, 1, 1, 1, 1, 1 };
	for (int i = 7; i > 0; i--) {
		int res = perm % i;
		int newCorner = -1;
		for (int j = 0; j < 7; j++) {
			if (dispo[j] > 0)
				res--;
			if (res < 0) {
				newCorner = j;
				dispo[j] = 0;
				break;
			}
		}
		newCube[corners[newCorner][0]] = cube[corners[7 - i][0]];
		newCube[corners[newCorner][1]] = cube[corners[7 - i][1]];

		char color = W;
		if (newCorner >= 4)
			color = Y;
		int mod = 0;
		if (newCorner == 6)
			mod = 1;
		newCube[newCorner % 4 + mod + color * 4] = (7 - i) >= 4 ? Y : W;
		perm /= i;
	}
	for (int i = 0; i < 24; i++)
		cube[i] = newCube[i];
}

void initCube(int orient, int perm) {
	for (int color = 0; color < 6; color++)
		for (int i = 0; i < 4; i++)
			cube[i + color * 4] = color;
	for (int i = 0; i < 11; i++)
	stack[i] = -1;
	idMove = -1;
	/*for (int i = 0; i < scrambleLength; i++)
	 internalRotateStickers(scramble[i] / 3, scramble[i] % 3);*/
	permutCube(perm);
	orientCube(orient);
}
int getDepth() {
	return idMove;
}
