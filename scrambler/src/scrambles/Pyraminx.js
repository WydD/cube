jPlex.provide('scrambles.Pyraminx', 'scrambles.AbstractScramble', {
    initialize: function(colorString) {
        if (!colorString) colorString = "gryb";
        this.layout = new Array(
                1, 2, 1, 2, 1, 0, 2, 0, 1, 2, 1, 2, 1,
                0, 1, 2, 1, 0, 2, 1, 2, 0, 1, 2, 1, 0,
                0, 0, 1, 0, 2, 1, 2, 1, 2, 0, 1, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 1, 2, 1, 2, 1, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 1, 2, 1, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0
                );
        this.edges = [2,11, 1,20, 4,31, 10,19, 13,29, 22,28];

        this.movelist = [];
        this.movelist[0 ] = [0, 18,9,   6, 24,15,  1, 19,11,  2, 20,10];  //U
        this.movelist[1 ] = [23,3, 30,  26,7, 34,  22,1, 31,  20,4, 28];  //L
        this.movelist[2 ] = [5, 14,32,  8, 17,35,  4, 11,29,  2, 13,31];  //R
        this.movelist[3 ] = [12,21,27,  16,25,33,  13,19,28,  10,22,29];  //B
        this.perm = [];   // pruning table for edge permutation
        this.twst = [];   // pruning table for edge orientation+twist
        this.permmv = []; // transition table for edge permutation
        this.twstmv = []; // transition table for edge orientation+twist
        this.sol = [];
        this.setColor(colorString);
    },
    scramble: function() {
        var i, j, ls, t;

        this.initbrd();
        this.calcperm();
        this.dosolve();

        scramblestring = "";

        this.colmap = [
            1,1,1,1,1,0,2,0,3,3,3,3,3,
            0,1,1,1,0,2,2,2,0,3,3,3,0,
            0,0,1,0,2,2,2,2,2,0,3,0,0,
            0,0,0,0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,4,4,4,4,4,0,0,0,0,
            0,0,0,0,0,4,4,4,0,0,0,0,0,
            0,0,0,0,0,0,4,0,0,0,0,0,0];
        for (i = 0; i < this.sol.length; i++) {
            scramblestring += ["U","L","R","B"][this.sol[i] & 7] + ["","'"][(this.sol[i] & 8) / 8] + " ";
            this.picmove([3,0,1,2][this.sol[i] & 7], 1 + (this.sol[i] & 8) / 8);
        }
        var tips = ["l","r","b","u"];
        for (i = 0; i < 4; i++) {
            var j = Math.floor(Math.random() * 3);
            if (j < 2) {
                scramblestring += tips[i] + ["","'"][j] + " ";
                this.picmove(4 + i, 1 + j);
            }
        }
        this.scramblestr = scramblestring;
    },
    initbrd: function() {
        this.posit = [0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,2,2,2,2,2,2,2,2,2,3,3,3,3,3,3,3,3,3];
        this.sol.length = 0;
    },
    solved: function() {
        var posit = this.posit;
        for (var i = 1; i < 9; i++) {
            if (posit[i   ] != posit[0 ]) return(false);
            if (posit[i + 9 ] != posit[9 ]) return(false);
            if (posit[i + 18] != posit[18]) return(false);
            if (posit[i + 27] != posit[27]) return(false);
        }
        return(true);
    },
    domove: function(m) {
        var movelist = this.movelist;
        var posit = this.posit;
        for (var i = 0; i < movelist[m].length; i += 3) {
            var c = posit[movelist[m][i]];
            posit[movelist[m][i  ]] = posit[movelist[m][i + 2]];
            posit[movelist[m][i + 2]] = posit[movelist[m][i + 1]];
            posit[movelist[m][i + 1]] = c;
        }
    },
    dosolve: function() {
        var a,b,c,l,t = 0,q = 0;
        // Get a random permutation and orientation.
        var parity = 0;
        var temp;
        var pcperm = [0,1,2,3,4,5];
        for (var i = 0; i < 4; i++) {
            var other = i + Math.floor((6 - i) * Math.random());
            temp = pcperm[i];
            pcperm[i] = pcperm[other];
            pcperm[other] = temp;
            if (i != other) parity++;
        }
        if (parity % 2 == 1) {
            temp = pcperm[4];
            pcperm[4] = pcperm[5];
            pcperm[5] = temp;
        }
        parity = 0;
        var pcori = [];
        for (i = 0; i < 5; i++) {
            pcori[i] = Math.floor(2 * Math.random());
            parity += pcori[i];
        }
        pcori[5] = parity % 2;
        for (i = 6; i < 10; i++) {
            pcori[i] = Math.floor(3 * Math.random());
        }

        for (a = 0; a < 6; a++) {
            b = 0;
            for (c = 0; c < 6; c++) {
                if (pcperm[c] == a)break;
                if (pcperm[c] > a)b++;
            }
            q = q * (6 - a) + b;
        }
        //corner orientation
        for (a = 9; a >= 6; a--) {
            t = t * 3 + pcori[a];
        }
        //edge orientation
        for (a = 4; a >= 0; a--) {
            t = t * 2 + pcori[a];
        }

        // solve it
        if (q != 0 || t != 0) {
            for (l = 7; l < 12; l++) {  //allow solutions from 7 through 11 moves
                if (this.search(q, t, l, -1)) break;
            }
        }
    },
    search: function(q, t, l, lm) {
        //searches for solution, from position q|t, in l moves exactly. last move was lm, current depth=d
        if (l == 0) {
            if (q == 0 && t == 0) {
                return(true);
            }
        } else {
            if (this.perm[q] > l || this.twst[t] > l) return(false);
            var p,s,a,m;
            for (m = 0; m < 4; m++) {
                if (m != lm) {
                    p = q;
                    s = t;
                    for (a = 0; a < 2; a++) {
                        p = this.permmv[p][m];
                        s = this.twstmv[s][m];
                        this.sol[this.sol.length] = m + 8 * a;
                        if (this.search(p, s, l - 1, m)) return(true);
                        this.sol.length--;
                    }
                }
            }
        }
        return(false);
    },
    picmove: function(type, direction) {
        switch (type) {
            case 0: // L
                this.rotate3(14, 58, 18, direction);
                this.rotate3(15, 57, 31, direction);
                this.rotate3(16, 70, 32, direction);
                this.rotate3(30, 28, 56, direction);
                break;
            case 1: // R
                this.rotate3(32, 72, 22, direction);
                this.rotate3(33, 59, 23, direction);
                this.rotate3(20, 58, 24, direction);
                this.rotate3(34, 60, 36, direction);
                break;
            case 2: // B
                this.rotate3(14, 10, 72, direction);
                this.rotate3(1, 11, 71, direction);
                this.rotate3(2, 24, 70, direction);
                this.rotate3(0, 12, 84, direction);
                break;
            case 3: // U
                this.rotate3(2, 18, 22, direction);
                this.rotate3(3, 19, 9, direction);
                this.rotate3(16, 20, 10, direction);
                this.rotate3(4, 6, 8, direction);
                break;
            case 4: // l
                this.rotate3(30, 28, 56, direction);
                break;
            case 5: // r
                this.rotate3(34, 60, 36, direction);
                break;
            case 6: // b
                this.rotate3(0, 12, 84, direction);
                break;
            case 7: // u
                this.rotate3(4, 6, 8, direction);
                break;
        }
    },
    rotate3: function(v1, v2, v3, clockwise) {
        if (clockwise == 2) {
            this.cycle3(this.colmap, v3, v2, v1);
        } else {
            this.cycle3(this.colmap, v1, v2, v3);
        }
    },

    cycle3: function(arr, i1, i2, i3) {
        var c = arr[i1];
        arr[i1] = arr[i2];
        arr[i2] = arr[i3];
        arr[i3] = c;
    },
    calcperm: function() {
        var c,p,q,l,m,n;
        //calculate solving arrays
        //first permutation
        // initialise arrays
        for (p = 0; p < 720; p++) {
            this.perm[p] = -1;
            this.permmv[p] = [];
            for (m = 0; m < 4; m++) {
                this.permmv[p][m] = this.getprmmv(p, m);
            }
        }
        //fill it
        this.perm[0] = 0;
        for (l = 0; l <= 6; l++) {
            n = 0;
            for (p = 0; p < 720; p++) {
                if (this.perm[p] == l) {
                    for (m = 0; m < 4; m++) {
                        q = p;
                        for (c = 0; c < 2; c++) {
                            q = this.permmv[q][m];
                            if (this.perm[q] == -1) {
                                this.perm[q] = l + 1;
                                n++;
                            }
                        }
                    }
                }
            }
        }
        //then twist
        // initialise arrays
        for (p = 0; p < 2592; p++) {
            this.twst[p] = -1;
            this.twstmv[p] = [];
            for (m = 0; m < 4; m++) {
                this.twstmv[p][m] = this.gettwsmv(p, m);
            }
        }
        //fill it
        this.twst[0] = 0;
        for (l = 0; l <= 5; l++) {
            n = 0;
            for (p = 0; p < 2592; p++) {
                if (this.twst[p] == l) {
                    for (m = 0; m < 4; m++) {
                        q = p;
                        for (c = 0; c < 2; c++) {
                            q = this.twstmv[q][m];
                            if (this.twst[q] == -1) {
                                this.twst[q] = l + 1;
                                n++;
                            }
                        }
                    }
                }
            }
        }
    },
    getprmmv: function (p, m) {
        //given position p<720 and move m<4, return new position number

        //convert number into array
        var a,b,c;
        var ps = [];
        var q = p;
        for (a = 1; a <= 6; a++) {
            c = Math.floor(q / a);
            b = q - a * c;
            q = c;
            for (c = a - 1; c >= b; c--) ps[c + 1] = ps[c];
            ps[b] = 6 - a;
        }
        //perform move on array
        if (m == 0) {
            //U
            this.cycle3(ps, 0, 3, 1);
        } else if (m == 1) {
            //L
            this.cycle3(ps, 1, 5, 2);
        } else if (m == 2) {
            //R
            this.cycle3(ps, 0, 2, 4);
        } else if (m == 3) {
            //B
            this.cycle3(ps, 3, 4, 5);
        }
        //convert array back to number
        q = 0;
        for (a = 0; a < 6; a++) {
            b = 0;
            for (c = 0; c < 6; c++) {
                if (ps[c] == a)break;
                if (ps[c] > a)b++;
            }
            q = q * (6 - a) + b;
        }
        return(q)
    },
    gettwsmv: function(p, m) {
        //given position p<2592 and move m<4, return new position number

        //convert number into array;
        var a,b,c,d = 0;
        var ps = [];
        var q = p;

        //first edge orientation
        for (a = 0; a <= 4; a++) {
            ps[a] = q & 1;
            q >>= 1;
            d ^= ps[a];
        }
        ps[5] = d;

        //next corner orientation
        for (a = 6; a <= 9; a++) {
            c = Math.floor(q / 3);
            b = q - 3 * c;
            q = c;
            ps[a] = b;
        }

        //perform move on array
        if (m == 0) {
            //U
            ps[6]++;
            if (ps[6] == 3) ps[6] = 0;
            this.cycle3(ps, 0, 3, 1);
            ps[1] ^= 1;
            ps[3] ^= 1;
        } else if (m == 1) {
            //L
            ps[7]++;
            if (ps[7] == 3) ps[7] = 0;
            this.cycle3(ps, 1, 5, 2);
            ps[2] ^= 1;
            ps[5] ^= 1;
        } else if (m == 2) {
            //R
            ps[8]++;
            if (ps[8] == 3) ps[8] = 0;
            this.cycle3(ps, 0, 2, 4);
            ps[0] ^= 1;
            ps[2] ^= 1;
        } else if (m == 3) {
            //B
            ps[9]++;
            if (ps[9] == 3) ps[9] = 0;
            this.cycle3(ps, 3, 4, 5);
            ps[3] ^= 1;
            ps[4] ^= 1;
        }
        //convert array back to number
        q = 0;
        //corner orientation
        for (a = 9; a >= 6; a--) {
            q = q * 3 + ps[a];
        }
        //corner orientation
        for (a = 4; a >= 0; a--) {
            q = q * 2 + ps[a];
        }
        return(q);
    },
    scramblestring: function() {
        return this.scramblestr;
    },
    draw_triangle: function(pat, color, val) {
        var s = "";
        if (pat == 1) {
            s += "<table border=0 cellpadding=0 cellspacing=0>";
            s += "<tr><td colspan=12 width=12 height=2 bgcolor=" + this.colorList[this.colors[color] + 2] + ">" + val + "</td></tr>";

            for (var i = 1; i <= 5; i++) {
                s += "<tr>";
                s += "<td colspan=" + i + " width=" + i + " height=2 bgcolor=white></td>";
                s += "<td colspan=" + (12 - i * 2) + " width=" + (12 - i * 2) + " height=2 bgcolor=" + this.colorList[this.colors[color] + 2] + "></td>";
                s += "<td colspan=" + i + " width=" + i + " height=2 bgcolor=white></td>";
                s += "</tr>";
            }

            s += "</table>";
        }
        else if (pat == 2) {
            s += "<table border=0 cellpadding=0 cellspacing=0>";
            for (var i = 5; i >= 1; i--) {
                s += "<tr>";
                s += "<td colspan=" + i + " width=" + i + " height=2 bgcolor=white></td>";
                s += "<td colspan=" + (12 - i * 2) + " width=" + (12 - i * 2) + " height=2 bgcolor=" + this.colorList[this.colors[color] + 2] + "></td>";
                s += "<td colspan=" + i + " width=" + i + " height=2 bgcolor=white></td>";
                s += "</tr>";
            }
            s += "<tr><td colspan=12 width=12 height=2 bgcolor=" + this.colorList[this.colors[color] + 2] + ">" + val + "</td></tr>";
            s += "</table>";
        }
        else {
            s += "&nbsp;";
        }
        return s;
    },
    imagestring: function() {
        var x,y;
        var s = "<table border=0 cellpadding=0 cellspacing=0>";

        for (var y = 0; y < 7; y++) {
            s += "<tr>";
            for (var x = 0; x < 13; x++) {
                s += "<td>";
                s += this.draw_triangle(this.layout[y * 13 + x], this.colmap[y * 13 + x], "");
                s += "</td>";
            }
            s += "</tr>";
        }
        s += "</table>";
        return s;
    },
    description: function() {
        s = "Algorithm: Uniform Random-State Scramble. ";
        s += "Author: Jaap Scherphuis, Syoji Takamatsu, Lucas Garron and Michael Gottlieb. ";
        return s;
    }
});