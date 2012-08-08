jPlex.provide('scrambles.Pocket', 'scrambles.Cube', {
    initialize: function($super) {
        $super(2, false, 0);
        var adj = new Array();
        adj[0] = new Array();
        adj[1] = new Array();
        adj[2] = new Array();
        adj[3] = new Array();
        adj[4] = new Array();
        adj[5] = new Array();
        this.adj = adj;
        mov2fc = new Array()
        mov2fc[0] = new Array(0, 2, 3, 1, 23, 19, 10, 6, 22, 18, 11, 7); //D
        mov2fc[1] = new Array(4, 6, 7, 5, 12, 20, 2, 10, 14, 22, 0, 8); //L
        mov2fc[2] = new Array(8, 10, 11, 9, 12, 7, 1, 17, 13, 5, 0, 19); //B
        mov2fc[3] = new Array(12, 13, 15, 14, 8, 17, 21, 4, 9, 16, 20, 5); //U
        mov2fc[4] = new Array(16, 17, 19, 18, 15, 9, 1, 23, 13, 11, 3, 21); //R
        mov2fc[5] = new Array(20, 21, 23, 22, 14, 16, 3, 6, 15, 18, 2, 4); //F
        this.mov2fc = mov2fc;
        this.sol = new Array();
        this.calcperm();

    },
    scramble: function() {
        this.posit = new Array(
                1, 1, 1, 1,
                2, 2, 2, 2,
                5, 5, 5, 5,
                4, 4, 4, 4,
                3, 3, 3, 3,
                0, 0, 0, 0);
        for (var i = 0; i < 500; i++) {
            var f = Math.floor(Math.random() * 3 + 3) + 16 * Math.floor(Math.random() * 3);
            this.domove(f);
        }
        this.solve();
        this.seq = $A(this.sol).reverse();
        this.seq[this.seq.length] = 0;
    },
    solved: function() {
        for (var i = 0; i < 24; i += 4) {
            c = this.posit[i];
            for (var j = 1; j < 4; j++)
                if (this.posit[i + j] != c) return(false);
        }
        return(true);
    },
    piece: new Array(15, 16, 16, 21, 21, 15, 13, 9, 9, 17, 17, 13, 14, 20, 20, 4, 4, 14, 12, 5, 5, 8, 8, 12,
            3, 23, 23, 18, 18, 3, 1, 19, 19, 11, 11, 1, 2, 6, 6, 22, 22, 2, 0, 10, 10, 7, 7, 0),
    calcadj: function() {
        var posit = this.posit;
        var adj = this.adj;
        var piece = this.piece;

        //count all adjacent pairs (clockwise around corners)
        var a,b;
        for (a = 0; a < 6; a++)for (b = 0; b < 6; b++) adj[a][b] = 0;
        for (a = 0; a < 48; a += 2) {
            if (posit[piece[a]] <= 5 && posit[piece[a + 1]] <= 5)
                adj[posit[piece[a]]][posit[piece[a + 1]]]++;
        }

    },
    calctot: function() {
        //count how many of each colour
        this.tot = new Array(0, 0, 0, 0, 0, 0, 0);
        for (var e = 0; e < 24; e++) this.tot[this.posit[e]]++;
    },

    domove: function(y) {
        posit = this.posit;
        mov2fc = this.mov2fc;
        var q = 1 + (y >> 4);
        var f = y & 15;
        while (q) {
            for (var i = 0; i < mov2fc[f].length; i += 4) {
                var c = posit[mov2fc[f][i]];
                posit[mov2fc[f][i]] = posit[mov2fc[f][i + 3]];
                posit[mov2fc[f][i + 3]] = posit[mov2fc[f][i + 2]];
                posit[mov2fc[f][i + 2]] = posit[mov2fc[f][i + 1]];
                posit[mov2fc[f][i + 1]] = c;
            }
            q--;
        }
    },
    solve: function() {
        this.calcadj();
        adj = this.adj;
        posit = this.posit;
        piece = this.piece;
        var opp = new Array();
        for (a = 0; a < 6; a++) {
            for (b = 0; b < 6; b++) {
                if (a != b && adj[a][b] + adj[b][a] == 0) {
                    opp[a] = b;
                    opp[b] = a;
                }
            }
        }
        //Each piece is determined by which of each pair of opposite colours it uses.
        var ps = new Array();
        var tws = new Array();
        var a = 0;
        for (var d = 0; d < 7; d++) {
            var p = 0;
            for (b = a; b < a + 6; b += 2) {
                if (posit[piece[b]] == posit[piece[42]]) p += 4;
                if (posit[piece[b]] == posit[piece[44]]) p += 1;
                if (posit[piece[b]] == posit[piece[46]]) p += 2;
            }
            ps[d] = p;
            if (posit[piece[a]] == posit[piece[42]] || posit[piece[a]] == opp[posit[piece[42]]]) tws[d] = 0;
            else if (posit[piece[a + 2]] == posit[piece[42]] || posit[piece[a + 2]] == opp[posit[piece[42]]]) tws[d] = 1;
            else tws[d] = 2;
            a += 6;
        }
        //convert position to numbers
        var q = 0;
        for (var a = 0; a < 7; a++) {
            var b = 0;
            for (var c = 0; c < 7; c++) {
                if (ps[c] == a)break;
                if (ps[c] > a)b++;
            }
            q = q * (7 - a) + b;
        }
        var t = 0;
        for (var a = 5; a >= 0; a--) {
            t = t * 3 + tws[a] - 3 * Math.floor(tws[a] / 3);
        }
        if (q != 0 || t != 0) {
            this.sol.length = 0;
            this.other = $A();
            for (var l = this.seqlen; l < 100; l++) {
                if (this.search(0, q, t, l, -1)) break;
            }
        }
    },
  /*  scramblestring2: function() {
        t = "";
        for (q = 0; q < this.other.length; q++) {
            t = "URF".charAt(this.other[q] / 10) + "\'2 ".charAt(this.other[q] % 10) + " " + t;
        }
        return t;
    },  */
    search: function(d, q, t, l, lm) {
        //searches for solution, from position q|t, in l moves exactly. last move was lm, current depth=d
        if (l == 0) {
            if (q == 0 && t == 0) {
                return(true);
            }
        } else {
            if (this.perm[q] > l || this.twst[t] > l) return(false);
            var p,s,a,m;
            for (m = 0; m < 3; m++) {
                if (m != lm) {
                    p = q;
                    s = t;
                    for (a = 0; a < 3; a++) {
                        p = this.permmv[p][m];
                        s = this.twstmv[s][m];
                        this.sol[d] = 4 * (m+3) + (2-a);
                        if (this.search(d + 1, p, s, l - 1, m)) return(true);
                    }
                }
            }
        }
        return(false);
    },

    calcperm: function() {
        //calculate solving arrays
        //first permutation
        var perm = new Array();
        var twst = new Array();
        var permmv = new Array()
        var twstmv = new Array();
        for (var p = 0; p < 5040; p++) {
            perm[p] = -1;
            permmv[p] = new Array();
            for (var m = 0; m < 3; m++) {
                permmv[p][m] = this.getprmmv(p, m);
            }
        }

        perm[0] = 0;
        for (var l = 0; l <= 6; l++) {
            var n = 0;
            for (var p = 0; p < 5040; p++) {
                if (perm[p] == l) {
                    for (var m = 0; m < 3; m++) {
                        var q = p;
                        for (var c = 0; c < 3; c++) {
                            var q = permmv[q][m];
                            if (perm[q] == -1) {
                                perm[q] = l + 1;
                                n++;
                            }
                        }
                    }
                }
            }
        }

        //then twist
        for (var p = 0; p < 729; p++) {
            twst[p] = -1;
            twstmv[p] = new Array();
            for (var m = 0; m < 3; m++) {
                twstmv[p][m] = this.gettwsmv(p, m);
            }
        }

        twst[0] = 0;
        for (var l = 0; l <= 5; l++) {
            var n = 0;
            for (var p = 0; p < 729; p++) {
                if (twst[p] == l) {
                    for (var m = 0; m < 3; m++) {
                        var q = p;
                        for (var c = 0; c < 3; c++) {
                            var q = twstmv[q][m];
                            if (twst[q] == -1) {
                                twst[q] = l + 1;
                                n++;
                            }
                        }
                    }
                }
            }
        }
        this.perm = perm;
        this.twst = twst;
        this.permmv = permmv;
        this.twstmv = twstmv;
        //remove wait sign
    },
    getprmmv: function(p, m) {
        //given position p<5040 and move m<3, return new position number
        var a,b,c,q;
        //convert number into array;
        var ps = new Array()
        q = p;
        for (a = 1; a <= 7; a++) {
            b = q % a;
            q = (q - b) / a;
            for (c = a - 1; c >= b; c--) ps[c + 1] = ps[c];
            ps[b] = 7 - a;
        }
        //perform move on array
        if (m == 0) {
            //U
            c = ps[0];
            ps[0] = ps[1];
            ps[1] = ps[3];
            ps[3] = ps[2];
            ps[2] = c;
        } else if (m == 1) {
            //R
            c = ps[0];
            ps[0] = ps[4];
            ps[4] = ps[5];
            ps[5] = ps[1];
            ps[1] = c;
        } else if (m == 2) {
            //F
            c = ps[0];
            ps[0] = ps[2];
            ps[2] = ps[6];
            ps[6] = ps[4];
            ps[4] = c;
        }
        //convert array back to number
        q = 0;
        for (a = 0; a < 7; a++) {
            b = 0;
            for (c = 0; c < 7; c++) {
                if (ps[c] == a)break;
                if (ps[c] > a)b++;
            }
            q = q * (7 - a) + b;
        }
        return(q)
    },
    gettwsmv: function (p, m) {
        //given orientation p<729 and move m<3, return new orientation number
        var a,b,c,d,q;
        //convert number into array;
        var ps = new Array()
        q = p;
        d = 0;
        for (a = 0; a <= 5; a++) {
            c = Math.floor(q / 3);
            b = q - 3 * c;
            q = c;
            ps[a] = b;
            d -= b;
            if (d < 0)d += 3;
        }
        ps[6] = d;
        //perform move on array
        if (m == 0) {
            //U
            c = ps[0];
            ps[0] = ps[1];
            ps[1] = ps[3];
            ps[3] = ps[2];
            ps[2] = c;
        } else if (m == 1) {
            //R
            c = ps[0];
            ps[0] = ps[4];
            ps[4] = ps[5];
            ps[5] = ps[1];
            ps[1] = c;
            ps[0] += 2;
            ps[1]++;
            ps[5] += 2;
            ps[4]++;
        } else if (m == 2) {
            //F
            c = ps[0];
            ps[0] = ps[2];
            ps[2] = ps[6];
            ps[6] = ps[4];
            ps[4] = c;
            ps[2] += 2;
            ps[0]++;
            ps[4] += 2;
            ps[6]++;
        }
        //convert array back to number
        q = 0;
        for (a = 5; a >= 0; a--) {
            q = q * 3 + (ps[a] % 3);
        }
        return(q);
    },
    description: function() {
        s="Algorithm: Uniform Random-State Scramble. ";
        s+="Author: Tom van der Zanden, based on previous work by Jaap Scherphuis. ";
        return s;
    }
});