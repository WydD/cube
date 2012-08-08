jPlex.provide('scrambles.Megaminx', 'scrambles.AbstractScramble', {
    initialize: function(linelen, linenbr) {
        this.linelen = linelen;
        this.linenbr = linenbr;
    },
    scramble: function() {
        this.seq = new Array();
        for (i = 0; i < this.linenbr * this.linelen; i++) {
            this.seq[i] = Math.floor(Math.random() * 2);
        }
    },
    scramblestring: function() {
        seq = this.seq;
        linelen = this.linelen;
        linenbr = this.linenbr;
        var s = "<br>&nbsp;&nbsp;",i,j;
        for (j = 0; j < linenbr; j++) {
            for (i = 0; i < linelen; i++) {
                if (i % 2)
                {
                    if (seq[j * linelen + i]) s += "D++ ";
                    else s += "D-- ";
                }
                else
                {
                    if (seq[j * linelen + i]) s += "R++ ";
                    else s += "R-- ";
                }
            }
            if (seq[(j + 1) * linelen - 1]) s += "U<br>&nbsp;&nbsp;";
            else s += "U'<br>&nbsp;&nbsp;";
        }
        return s;
    },
    description: function() {
        s="Algorithm: Random Moves Generator. Parameters: line length ("+this.linelen+"), lines ("+this.linenbr+"). ";
        s+="Author: Cl&eacute;ment Gallet, based on earlier work by Jaap Scherphuis. Idea by Stefan Pochmann. ";
        return s;
    }
});