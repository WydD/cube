var coll={};
coll["h"] = [];
coll["h"][0] = {gen: "R U R' U R U' R' U R U2 R'", auf: 0 , premove: 0 };
coll["h"][2] = {gen: "L' B2 D L2 U' L2 D' L R' U2 R", auf: 0 , premove: 2 };
coll["h"][3] = {gen: "B U' F' U B' U F U F' U F", auf: 0 , premove: 0 };
coll["h"][4] = {gen: "L' B2 D L2 U' L2 D' L R' U2 R", auf: 2 , premove: 0 };
coll["h"][5] = {gen: "B U' F' U B' U F U F' U F", auf: 2 , premove: 2 };
coll["h"][1] = {gen: "B2 L' F2 L B2 U2 B2 R' D2 R B2", auf: 0 , premove: 1 };
coll["p"] = [];
coll["p"][0] = {gen: "R' U2 R2 U R2 U R2 U2 R'", auf: 0 , premove: 2 };
coll["p"][3] = {gen: "F' U' L F2 D R' F' R F2 D' L' F2", auf: 1 , premove: 1 };
coll["p"][5] = {gen: "F2 L D F2 R' F R D' F2 L' U F", auf: 1 , premove: 1 };
coll["p"][4] = {gen: "B L' B' R B L2 U' L' U2 B' U' R'", auf: 1 , premove: 0 };
coll["p"][2] = {gen: "B U B2 R2 D' F2 D' F2 D2 R2 B", auf: 1 , premove: 1 };
coll["p"][1] = {gen: "B F D' L D B' F2 U' R' F R2 U2 R'", auf: 1 , premove: 2 };
coll["t"] = [];
coll["t"][0] = {gen: "B U L' B D' B D B2 L2 U' L' B'", auf: 0 , premove: 0 };
coll["t"][1] = {gen: "R' U R2 D L' B2 L D' R2 U' R", auf: 0 , premove: 1 };
coll["t"][3] = {gen: "F2 R2 F L2 F' R2 F L2 F", auf: 0 , premove: 3 };
coll["t"][5] = {gen: "B' R2 D2 L' F' L D2 R' B R'", auf: 0 , premove: 0 };
coll["t"][4] = {gen: "B' R' F' R B R' F R", auf: 0 , premove: 3 };
coll["t"][2] = {gen: "R' F R B' R' F' R B", auf: 0 , premove: 1 };
coll["u"] = [];
coll["u"][5] = {gen: "F U F' R' F2 D F' U' F D' F2 U R", auf: 0 , premove: 0 };
coll["u"][3] = {gen: "R B2 R F2 R' B2 R F2 R2", auf: 0 , premove: 3 };
coll["u"][1] = {gen: "F U' R' U R U F' R' U2 R", auf: 0 , premove: 0 };
coll["u"][0] = {gen: "B' R' U' R2 B2 D B D' B R' U B", auf: 0 , premove: 0 };
coll["u"][4] = {gen: "R' U2 R' D' R U2 R' D R2", auf: 0 , premove: 3 };
coll["u"][2] = {gen: "L U2 L D R' F2 R D' L2", auf: 0 , premove: 1 };
coll["l"] = [];
coll["l"][4] = {gen: "B2 D' B U2 B' D B U2 B", auf: 2 , premove: 3 };
coll["l"][5] = {gen: "L2 D R' F2 R D' L' U2 L'", auf: 2 , premove: 1 };
coll["l"][0] = {gen: "R F' D2 F R' U2 R F' D2 F R'", auf: 2 , premove: 0 };
coll["l"][3] = {gen: "B' R' F R B R' F' R", auf: 0 , premove: 3 };
coll["l"][2] = {gen: "R' F' R B' R' F R B", auf: 3 , premove: 2 };
coll["l"][1] = {gen: "R' F2 L2 D' L' D L' F2 R", auf: 2 , premove: 1 };
coll["s"] = [];
coll["s"][5] = {gen: "R U B' U' B R' U' B' U2 B", auf: 3 , premove: 3 };
coll["s"][3] = {gen: "L' B U2 B' L B L' U2 L B'", auf: 3 , premove: 2 };
coll["s"][4] = {gen: "B' U F U' B U F'", auf: 3 , premove: 3 };
coll["s"][2] = {gen: "R U2 R' U' B' R U' R' U B", auf: 3 , premove: 3 };
coll["s"][1] = {gen: "F' U' F U' B U' F' U B' U2 F", auf: 3 , premove: 2 };
coll["s"][0] = {gen: "L' U' L U' L' U2 L", auf: 3 , premove: 3 };
coll["a"] = [];
coll["a"][3] = {gen: "R B' U2 B R' B' R U2 R' B", auf: 1 , premove: 2 };
coll["a"][5] = {gen: "R' U' F U F' R U F U2 F'", auf: 3 , premove: 3 };
coll["a"][2] = {gen: "B U' F' U B' U' F", auf: 1 , premove: 1 };
coll["a"][4] = {gen: "L' U2 L U B L' U L U' B'", auf: 1 , premove: 1 };
coll["a"][1] = {gen: "F U F' U B' U F U' B U2 F'", auf: 1 , premove: 0 };
coll["a"][0] = {gen: "R U R' U R U2 R'", auf: 1 , premove: 1 };
coll["n"] = [];
coll["n"][1] = {gen: "F2 D R2 U R2 D' R' U' R F2 R' U R", auf: 0 , premove: 2 };
coll["n"][2] = {gen: "R B' R F2 R' B R F2 R2", auf: 2 , premove: 3 };
coll["n"][3] = {gen: "R B' R F2 R' B R F2 R2", auf: 3 , premove: 2 };
coll["n"][4] = {gen: "R B' R F2 R' B R F2 R2", auf: 0 , premove: 1 };
coll["n"][5] = {gen: "R B' R F2 R' B R F2 R2", auf: 1 , premove: 0 };
