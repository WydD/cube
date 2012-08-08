jPlex.provide('scrambles.Cube', 'scrambles.AbstractScramble', {
    initialize: function(size, mult, seqlen, colorString) {
        if(!colorString) colorString = "yobwrg";
        this.size = size;
        this.mult = mult;
        this.seqlen = seqlen;
        //this.cubeorient = cubeorient;

        // build lookup table
        var i;
        var flat2posit = new Array(12 * size * size);
        for (i = 0; i < flat2posit.length; i++) flat2posit[i] = -1;
        for (i = 0; i < size; i++) {
            for (j = 0; j < size; j++) {
                flat2posit[4 * size * (3 * size - i - 1) + size + j  ] = i * size + j;	//D
                flat2posit[4 * size * (  size + i  ) + size - j - 1] = (  size + i) * size + j;	//L
                flat2posit[4 * size * (  size + i  ) + 4 * size - j - 1] = (2 * size + i) * size + j;	//B
                flat2posit[4 * size * (       i  ) + size + j  ] = (3 * size + i) * size + j;	//U
                flat2posit[4 * size * (  size + i  ) + 2 * size + j  ] = (4 * size + i) * size + j;	//R
                flat2posit[4 * size * (  size + i  ) + size + j  ] = (5 * size + i) * size + j;	//F
            }
        }
        this.flat2posit = flat2posit;
        var colorPerm = new Array(); //dlburf face colour permutation for each cube orientation
        colorPerm[ 0] = new Array(0, 1, 2, 3, 4, 5);
        colorPerm[ 1] = new Array(0, 2, 4, 3, 5, 1);
        colorPerm[ 2] = new Array(0, 4, 5, 3, 1, 2);
        colorPerm[ 3] = new Array(0, 5, 1, 3, 2, 4);
        colorPerm[ 4] = new Array(1, 0, 5, 4, 3, 2);
        colorPerm[ 5] = new Array(1, 2, 0, 4, 5, 3);
        colorPerm[ 6] = new Array(1, 3, 2, 4, 0, 5);
        colorPerm[ 7] = new Array(1, 5, 3, 4, 2, 0);
        colorPerm[ 8] = new Array(2, 0, 1, 5, 3, 4);
        colorPerm[ 9] = new Array(2, 1, 3, 5, 4, 0);
        colorPerm[10] = new Array(2, 3, 4, 5, 0, 1);
        colorPerm[11] = new Array(2, 4, 0, 5, 1, 3);
        colorPerm[12] = new Array(3, 1, 5, 0, 4, 2);
        colorPerm[13] = new Array(3, 2, 1, 0, 5, 4);
        colorPerm[14] = new Array(3, 4, 2, 0, 1, 5);
        colorPerm[15] = new Array(3, 5, 4, 0, 2, 1);
        colorPerm[16] = new Array(4, 0, 2, 1, 3, 5);
        colorPerm[17] = new Array(4, 2, 3, 1, 5, 0);
        colorPerm[18] = new Array(4, 3, 5, 1, 0, 2);
        colorPerm[19] = new Array(4, 5, 0, 1, 2, 3);
        colorPerm[20] = new Array(5, 0, 4, 2, 3, 1);
        colorPerm[21] = new Array(5, 1, 0, 2, 4, 3);
        colorPerm[22] = new Array(5, 3, 1, 2, 0, 4);
        colorPerm[23] = new Array(5, 4, 3, 2, 1, 0);
        this.colorPerm = colorPerm;
        this.setColor(colorString);
    },
    scramble: function () {
        var tl = this.size;
        if (this.mult || (this.size & 1) != 0) tl--;
        //set up bookkeeping
        var axsl = new Array(tl);    // movement of each slice/movetype on this axis
        var axam = new Array(0, 0, 0); // number of slices moved each amount
        var la; // last axis moved

        // initialise this scramble
        la = -1;
        seq = new Array(); // moves generated so far
        // reset slice/direction counters
        for (var i = 0; i < tl; i++) axsl[i] = 0;
        axam[0] = axam[1] = axam[2] = 0;
        var moved = 0;

        // while generated sequence not long enough
        while (seq.length + moved < this.seqlen) {

            var ax, sl, q;
            do{
                do{
                    // choose a random axis
                    ax = Math.floor(Math.random() * 3);
                    // choose a random move type on that axis
                    sl = Math.floor(Math.random() * tl);
                    // choose random amount
                    q = Math.floor(Math.random() * 3);
                } while (ax == la && axsl[sl] != 0);		// loop until have found an unused movetype
            } while (ax == la                    // loop while move is reducible: reductions only if on same axis as previous moves
                    && !this.mult                // multislice moves have no reductions so always ok
                    && tl == this.size                // only even-sized cubes have reductions (odds have middle layer as reference)
                    && (
                    2 * axam[0] == tl || // reduction if already have half the slices move in same direction
                    2 * axam[1] == tl ||
                    2 * axam[2] == tl ||
                    (
                            2 * (axam[q] + 1) == tl    // reduction if move makes exactly half the slices moved in same direction and
                                    &&
                            axam[0] + axam[1] + axam[2] - axam[q] > 0 // some other slice also moved
                            )
                    )
                    );

            // if now on different axis, dump cached moves from old axis
            if (ax != la) {
                this.appendmoves(seq, axsl, tl, la);
                // reset slice/direction counters
                for (var i = 0; i < tl; i++) axsl[i] = 0;
                axam[0] = axam[1] = axam[2] = 0;
                moved = 0;
                // remember new axis
                la = ax;
            }

            // adjust counters for this move
            axam[q]++;// adjust direction count
            moved++;
            axsl[sl] = q + 1;// mark the slice has moved amount

        }
        // dump the last few moves
        this.appendmoves(seq, axsl, tl, la);

        // do a random cube orientation if necessary
        seq[seq.length] = 0;
        this.seq = seq;
    },
    scramblestring: function() {
        var s = "",j;
        for (var i = 0; i < this.seq.length - 1; i++) {
            if (i != 0) s += " ";
            var k = this.seq[i] >> 2;

            j = k % 6;
            k = (k - j) / 6;
            if (k && this.size <= 5 && !this.mult) {
                s += "dlburf".charAt(j);	// use lower case only for inner slices on 4x4x4 or 5x5x5
            } else {
                if (this.size <= 5 && this.mult) {
                    s += "DLBURF".charAt(j);
                    if (k) s += "w";	// use w only for double layers on 4x4x4 and 5x5x5
                }
                else {
                    if (k)    s += (k + 1);
                    s += "DLBURF".charAt(j);
                }
            }

            j = this.seq[i] & 3;
            if (j != 0) s += " 2'".charAt(j);
        }
        // add cube orientation
        //        if (this.cubeorient) {
        //            var ori = this.seq[this.seq.length - 1];
        //            s = "Top:" + colorList[ 2 + colors[colorPerm[ori][3]] ]
        //                    + "&nbsp;&nbsp;&nbsp;Front:" + colorList[2 + colors[colorPerm[ori][5]] ] + "<br>" + s;
        //        }
        return s;
    },
    appendmoves: function(sq, axsl, tl, la) {
        for (var sl = 0; sl < tl; sl++) {    // for each move type
            if (axsl[sl]) {                // if it occurs
                var q = axsl[sl] - 1;

                // get semi-axis of this move
                var sa = la;
                var m = sl;
                if (sl + sl + 1 >= tl) { // if on rear half of this axis
                    sa += 3; // get semi-axis (i.e. face of the move)
                    m = tl - 1 - m; // slice number counting from that face
                    q = 2 - q; // opposite direction when looking at that face
                }
                // store move
                sq[sq.length] = (m * 6 + sa) * 4 + q;
            }
        }
    },
    doslice: function(f, d, q) {
        //do move of face f, layer d, q quarter turns
        var size = this.size;
        var posit = this.posit;
        var f1,f2,f3,f4;
        var s2 = size * size;
        var c,i,j,k;
        if (f > 5)f -= 6;
        // cycle the side facelets
        for (k = 0; k < q; k++) {
            for (i = 0; i < size; i++) {
                if (f == 0) {
                    f1 = 6 * s2 - size * d - size + i;
                    f2 = 2 * s2 - size * d - 1 - i;
                    f3 = 3 * s2 - size * d - 1 - i;
                    f4 = 5 * s2 - size * d - size + i;
                } else if (f == 1) {
                    f1 = 3 * s2 + d + size * i;
                    f2 = 3 * s2 + d - size * (i + 1);
                    f3 = s2 + d - size * (i + 1);
                    f4 = 5 * s2 + d + size * i;
                } else if (f == 2) {
                    f1 = 3 * s2 + d * size + i;
                    f2 = 4 * s2 + size - 1 - d + size * i;
                    f3 = d * size + size - 1 - i;
                    f4 = 2 * s2 - 1 - d - size * i;
                } else if (f == 3) {
                    f1 = 4 * s2 + d * size + size - 1 - i;
                    f2 = 2 * s2 + d * size + i;
                    f3 = s2 + d * size + i;
                    f4 = 5 * s2 + d * size + size - 1 - i;
                } else if (f == 4) {
                    f1 = 6 * s2 - 1 - d - size * i;
                    f2 = size - 1 - d + size * i;
                    f3 = 2 * s2 + size - 1 - d + size * i;
                    f4 = 4 * s2 - 1 - d - size * i;
                } else if (f == 5) {
                    f1 = 4 * s2 - size - d * size + i;
                    f2 = 2 * s2 - size + d - size * i;
                    f3 = s2 - 1 - d * size - i;
                    f4 = 4 * s2 + d + size * i;
                }
                c = posit[f1];
                posit[f1] = posit[f2];
                posit[f2] = posit[f3];
                posit[f3] = posit[f4];
                posit[f4] = c;
            }

            /* turn face */
            if (d == 0) {
                for (i = 0; i + i < size; i++) {
                    for (j = 0; j + j < size - 1; j++) {
                        f1 = f * s2 + i + j * size;
                        f3 = f * s2 + (size - 1 - i) + (size - 1 - j) * size;
                        if (f < 3) {
                            f2 = f * s2 + (size - 1 - j) + i * size;
                            f4 = f * s2 + j + (size - 1 - i) * size;
                        } else {
                            f4 = f * s2 + (size - 1 - j) + i * size;
                            f2 = f * s2 + j + (size - 1 - i) * size;
                        }
                        c = posit[f1];
                        posit[f1] = posit[f2];
                        posit[f2] = posit[f3];
                        posit[f3] = posit[f4];
                        posit[f4] = c;
                    }
                }
            }
        }
    },
    imagestring: function() {
        var size = this.size;
        var seq = this.seq;
        var s = "",i,f,d = 0,q;
        this.posit = $A();
        // initialise colours
        for (i = 0; i < 6; i++)
            for (f = 0; f < size * size; f++)
                this.posit[d++] = i;

        // do move sequence
        for (i = 0; i < seq.length - 1; i++) {
            q = seq[i] & 3;
            f = seq[i] >> 2;
            d = 0;
            while (f > 5) {
                f -= 6;
                d++;
            }
            do{
                this.doslice(f, d, q + 1);
                d--;
            } while (this.mult && d >= 0);
        }

        // build string containing cube
        var ori = seq[seq.length - 1];
        d = 0;
        s = "<table border=0 cellpadding=0 cellspacing=0>";
        for (i = 0; i < 3 * size; i++) {
            s += "<tr>";
            for (f = 0; f < 4 * size; f++) {
                if (this.flat2posit[d] < 0) {
                    s += "<td><\/td>";
                } else {
                    var c = this.colorPerm[ori][this.posit[this.flat2posit[d]]];
                    s += "<td bgcolor=" + this.colorList[this.colors[c] + 1] + ">" +
                         "<div style='width:10px; height:10px; border: 1px solid black;'>&nbsp;<\/div><\/td>";
                }
                d++;
            }
            s += "<\/tr>";
        }
        s += "<\/table>";
        return(s);
    },
    description: function() {
        s="Algorithm: Random Moves Generator. Parameters: size ("+this.size+"), length ("+this.seqlen+"). ";
        s+="Author: Jaap Scherphuis. ";
        if(this.size == 3)
            s+="<br/><span style='color:red;'>Warning: "+
                                "Official regulations stipulates that a 3x3x3 cube must be generated from a random " +
                                "position and then solved by Cube Explorer (Kociemba).</span>";
        return s;
    }
});

