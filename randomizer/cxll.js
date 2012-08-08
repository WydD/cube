var cxll = {
	h: [
{gen: "R U R' U R U' R' U R U2 R'", 	auf: 0, premove: 0}, // pure
{gen: "F2 R D2 R' F2 U2 F2 L B2 L' F2", auf: 0, premove: 1}, // y
{gen: "F R2 U' R2 U R2 U F' B U2 B'", 	auf: 1, premove: 1}, // 
{gen: "R' F R' F' R2 U2 B' R B R'", 	auf: 2, premove: 1}, // y
{gen: "F R2 U' R2 U R2 U F' B U2 B'", 	auf: 3, premove: 3}, // 
{gen: "R' F R' F' R2 U2 B' R B R'", 	auf: 0, premove: 3}],
	p: [
{gen: "R U2 R2 U' R2 U' R2 U2 R", 		auf: 0, premove: 2}, // pure
{gen: "F R U R' U F' U' F U' F'", 		auf: 1, premove: 2}, // y
{gen: "F U F2 L2 D' B2 D' B2 D2 L2 F", 	auf: 3, premove: 3}, // diagdiag
{gen: "F U R U' R2 F' R U2 R U2 R'", 	auf: 0, premove: 3}, // dup
{gen: "R B' R' B U2 R2 F R F' R", 		auf: 0, premove: 1}, // barbar
{gen: "R U2 R' U2 R' F R2 U R' U' F'", 	auf: 3, premove: 2} // down
	],
	s:[
{gen: "R U2 R' U' R U' R'", 			auf: 0, premove: 2}, // pure
{gen: "R U2 R' F R' F' R U' R U' R'", 	auf: 2, premove: 3}, // y
{gen: "R U2 R' U2 R' F R F'", 			auf: 0, premove: 3}, // contre-gg
{gen: "F' L U2 L' F L F' U2 F L'", 		auf: 0, premove: 1}, // rude
{gen: "L' U R U' L U R'", 				auf: 0, premove: 2}, // niklas
{gen: "F' L F R' F2 L' F2 R", 			auf: 0, premove: 3}], // gg
	a:[
{gen: "R U R' U R U2 R'", 				auf: 1, premove: 1}, // pure
{gen: "R U R' U R' F R F' R U2 R'", 	auf: 2, premove: 3}, // y
{gen: "R U' L' U R' U' L", 				auf: 0, premove: 2}, // niklas
{gen: "L F' U2 F L' F' L U2 L' F", 		auf: 3, premove: 0}, // rude
{gen: "R' F2 L F2 R F' L' F", 			auf: 0, premove: 1}, // contre-gg
{gen: "F R' F' R U2 R U2 R'", 			auf: 0, premove: 1}], // gg
	l:[
{gen: "R F' U2 B L' U2 L B' U2 F R'", 	auf: 2, premove: 0}, // pure
{gen: "R U2 R' F R' F' R2 U2 R'", 		auf: 0, premove: 3}, // y
{gen: "R' F' R B' R' F R B", 			auf: 3, premove: 2}, // gg-reverse
{gen: "F' U' F U F R' F' R", 			auf: 0, premove: 3}, // gg-nat
{gen: "R2 D' R U2 R' D R U2 R", 		auf: 1, premove: 0}, // rude-f
{gen: "R2 D R' U2 R D' R' U2 R'", 		auf: 0, premove: 3}, // rude-b
],	u:[
{gen: "R' F2 R2 U2 F U F' U R2 F2 R", 	auf: 2, premove: 0}, // pure
{gen: "R' U' F' U F R", 				auf: 0, premove: 3}, // fruruf
{gen: "R U2 R D R' U2 R D' R2",			auf: 2, premove: 3}, // diag-droit
{gen: "R B2 R F2 R' B2 R F2 R2", 		auf: 0, premove: 3}, // diag-diag
{gen: "R' U2 R' D' R U2 R' D R2", 		auf: 0, premove: 3}, // diag-gauche
{gen: "R' F2 D' F U' F' D F2 U R", 		auf: 1, premove: 0}], // barbare
	t: [
{gen: "R2 F' U F U' F' U' F2 R F' R", 	auf: 1, premove: 0}, //pure
{gen: "F2 R2 F L' U' L U F' R2 F2", 	auf: 0, premove: 3}, // y
{gen: "F' L F R' F' L' F R", 			auf: 3, premove: 2}, // droite
{gen: "R' D' F L F L' F2 L F L' D R", 	auf: 2, premove: 0}, // op-barre
{gen: "F R' F' R U R U' R'", 			auf: 1, premove: 2}, // gauche
{gen: "R U' L U R' L' U2 L U L'", 		auf: 0, premove: 2}], // barre-op
	n: [
{gen:"", auf: 0, premove: 0}, // pure
{gen:"F2 D R2 U R2 D' R' U' R F2 R' U R", auf: 2, premove: 0}, // y
{gen:"R B' R F2 R' B R F2 R2", auf: 2, premove: 3}, 
{gen:"R B' R F2 R' B R F2 R2", auf: 3, premove: 2}, 
{gen:"R B' R F2 R' B R F2 R2", auf: 0, premove: 1}, 
{gen:"R B' R F2 R' B R F2 R2", auf: 1, premove: 0}]	// t
};
