jPlex.provide('scrambles.Clock', 'scrambles.AbstractScramble', {
    initialize: function(size, mult, seqlen, colorString) {
        var moves = new Array();
        moves[0]=new Array(1,1,1,1,1,1,0,0,0,  -1,0,-1,0,0,0,0,0,0);
        moves[1]=new Array(0,1,1,0,1,1,0,1,1,  -1,0,0,0,0,0,-1,0,0);
        moves[2]=new Array(0,0,0,1,1,1,1,1,1,  0,0,0,0,0,0,-1,0,-1);
        moves[3]=new Array(1,1,0,1,1,0,1,1,0,  0,0,-1,0,0,0,0,0,-1);

        moves[4]=new Array(0,0,0,0,0,0,1,0,1,  0,0,0,-1,-1,-1,-1,-1,-1);
        moves[5]=new Array(1,0,0,0,0,0,1,0,0,  0,-1,-1,0,-1,-1,0,-1,-1);
        moves[6]=new Array(1,0,1,0,0,0,0,0,0,  -1,-1,-1,-1,-1,-1,0,0,0);
        moves[7]=new Array(0,0,1,0,0,0,0,0,1,  -1,-1,0,-1,-1,0,-1,-1,0);

        moves[ 8]=new Array(0,1,1,1,1,1,1,1,1,  -1,0,0,0,0,0,-1,0,-1);
        moves[ 9]=new Array(1,1,0,1,1,1,1,1,1,  0,0,-1,0,0,0,-1,0,-1);
        moves[10]=new Array(1,1,1,1,1,1,1,1,0,  -1,0,-1,0,0,0,0,0,-1);
        moves[11]=new Array(1,1,1,1,1,1,0,1,1,  -1,0,-1,0,0,0,-1,0,0);

        moves[12]=new Array(1,1,1,1,1,1,1,1,1,  -1,0,-1,0,0,0,-1,0,-1);
        moves[13]=new Array(1,0,1,0,0,0,1,0,1,  -1,-1,-1,-1,-1,-1,-1,-1,-1);
        this.moves = moves;
    },
    scramble: function() {
        var posit = new Array (0,0,0,0,0,0,0,0,0,  0,0,0,0,0,0,0,0,0);
        var seq = new Array();
        var i,j;


        for( i=0; i<14; i++){
            seq[i] = Math.floor(Math.random()*12)-5;
        }

        for( i=0; i<14; i++){
            for( j=0; j<18; j++){
                posit[j]+=seq[i]*this.moves[i][j];
            }
        }
        for( j=0; j<18; j++){
            posit[j]%=12;
            while( posit[j]<=0 ) posit[j]+=12;
        }

        this.seq = seq;
        this.posit = posit;
    },
    scramblestring: function() {
        var seq = this.seq;
        var s = "<table><tr valign='top'><td width='50'>&nbsp;</td><td><pre>";
        s += ("UU  u=" + seq[0] + "\n");
        s += ("dd  d=" + seq[4] + "\n");
        s += ("" + "\n");
        s += ("dU  u=" + seq[1] + "\n");
        s += ("dU  d=" + seq[5] + "\n");
        s += ("" + "\n");
        s += ("dd  u=" + seq[2] + "\n");
        s += ("UU  d=" + seq[6] + "\n");
        s += ("" + "\n");
        s += ("Ud  u=" + seq[3] + "\n");
        s += ("Ud  d=" + seq[7] + "\n");
        s += ("</pre></td><td width='100'>&nbsp;</td><td><pre>" );
        s += ("dU  u=" + seq[8] + "\n");
        s += ("UU" + "\n");
        s += ("" + "\n");
        s += ("Ud  u=" + seq[9] + "\n");
        s += ("UU" + "\n");
        s += ("" + "\n");
        s += ("UU  u=" + seq[10] + "\n");
        s += ("Ud" + "\n");
        s += ("" + "\n");
        s += ("UU  u=" + seq[11] + "\n");
        s += ("dU" + "\n");
        s += ("</pre></td><td width='100'>&nbsp;</td><td><pre>" );
        s += ("UU  u=" + seq[12] + "\n");
        s += ("UU" + "\n");
        s += ("" + "\n");
        s += ("dd  d=" + seq[13] + "\n");
        s += ("dd" + "\n");
        s += ("" + "\n");
        s += this.prtrndpin() + this.prtrndpin() + "\n";
        s += this.prtrndpin() + this.prtrndpin() + "\n";
        s += "</pre></td></tr></table>";
        return s;
    },
    imagestring: function() {
        posit = this.posit;
        s = ("Front:\n");
        for (i = 0; i < 9; i++) {
            s += this.prt(posit[i]);
            if ((i % 3) == 2) s += ("\n");
        }
        s += ("Back:\n");
        for (i = 0; i < 9; i++) {
            s += this.prt(posit[i + 9]);
            if ((i % 3) == 2) s += ("\n");
        }
        return "<h1><pre>"+s+"</pre></h1>";
    },
    prtrndpin: function() {
        return Math.floor(Math.random() * 2) == 0 ? "U" : "d";
    },
    prt: function(p) {
        var s = "";
        if (p < 10) s += (" ");
        s += (p + " ");
        return s;
    },
    description: function() {
        return "Algorithm: Uniform Random-State Scramble with Random Pins. Author: Jaap Scherphuis";
    }
});