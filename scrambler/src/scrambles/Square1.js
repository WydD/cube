jPlex.provide('scrambles.Square1', 'scrambles.AbstractScramble', {
    initialize: function(seqlen, colorString) {  
        if(!colorString) colorString = "yobwrg";
        this.seqlen = seqlen;
        //var tb = ["y","y","y","y","y","y","y","y","w","w","w","w","w","w","w","w"];
        this.tb = ["3","3","3","3","3","3","3","3","0","0","0","0","0","0","0","0"];
        this.ty = ["c","e","c","e","c","e","c","e","e","c","e","c","e","c","e","c"];
        this.col = ["51","1","12","2","24","4","45","5","5","54","4","42","2","21","1","15"];
        Raphael.fn.fillPolygon = function(arrx, arry, stroke, fill) {
            path = "";
            for (n = 0; n < arrx.length; n++) {
                path += (n == 0 ? "M" : "L") + arrx[n] + " " + arry[n];
            }
            path += "z";
            this.path(path)
                    .attr("fill", fill)
                    .attr("stroke", stroke);
        }
        this.setColor(colorString);
    },
    scramble: function() {
        this.posit = new Array(0, 0, 1, 2, 2, 3, 4, 4, 5, 6, 6, 7, 8, 9, 9, 10, 11, 11, 12, 13, 13, 14, 15, 15);
        ls = -1;
        this.seq = new Array();
        f = 0;
        for (i = 0; i < this.seqlen; i++) {
            do{
                if (ls == 0) {
                    j = Math.floor(Math.random() * 22) - 11;
                    if (j >= 0) j++;
                } else if (ls == 1) {
                    j = Math.floor(Math.random() * 12) - 11;
                } else if (ls == 2) {
                    j = 0;
                } else {
                    j = Math.floor(Math.random() * 23) - 11;
                }
                // if past second twist, restrict bottom layer
            } while ((f > 1 && j >= -6 && j < 0) || this.domove(j));
            if (j > 0) ls = 1;
            else if (j < 0) ls = 2;
            else {
                ls = 0;
                f++;
            }
            this.seq[i] = j;
        }
    },
    domove: function(m) {
        var i,c,t,f = m;
        posit = this.posit;
        //do move f
        if (f == 0) {
            for (i = 0; i < 6; i++) {
                c = posit[i + 12];
                posit[i + 12] = posit[i + 6];
                posit[i + 6] = c;
            }
        } else if (f > 0) {
            f = 12 - f;
            if (posit[f] == posit[f - 1]) return true;
            if (f < 6 && posit[f + 6] == posit[f + 5]) return true;
            if (f > 6 && posit[f - 6] == posit[f - 7]) return true;
            if (f == 6 && posit[0] == posit[11]) return true;
            t = new Array();
            for (i = 0; i < 12; i++) t[i] = posit[i];
            c = f;
            for (i = 0; i < 12; i++) {
                posit[i] = t[c];
                if (c == 11)c = 0; else c++;
            }
        } else if (f < 0) {
            f = -f;
            if (posit[f + 12] == posit[f + 11]) return true;
            if (f < 6 && posit[f + 18] == posit[f + 17]) return true;
            if (f > 6 && posit[f + 6] == posit[f + 5]) return true;
            if (f == 6 && posit[12] == posit[23]) return true;
            t = new Array();
            for (i = 0; i < 12; i++) t[i] = posit[i + 12];
            c = f;
            for (i = 0; i < 12; i++) {
                posit[i + 12] = t[c];
                if (c == 11)c = 0; else c++;
            }
        }
        return false;
    },
    scramblestring: function() {
        var s = "",i,k,l = -1;
        for (i = 0; i < this.seq.length; i++) {
            k = this.seq[i];
            if (k == 0) {
                if (l == -1) s += "(0,0)  ";
                if (l == 1) s += "0)  ";
                if (l == 2) s += ")  ";
                l = 0;
            } else if (k > 0) {
                s += "(" + (k > 6 ? k - 12 : k) + ",";
                l = 1;
            } else if (k < 0) {
                if (l <= 0) s += "(0,";
                s += (k <= -6 ? k + 12 : k);
                l = 2;
            }
        }
        if(l==1) s+="0";
        if(l!=0) s+=") (0,0)";
        return s + "<br><br>";
    },
    imagestring: function() {
        var top_side = this.remove_duplicates(this.posit.slice(0, 12));
        var bot_side = this.remove_duplicates(this.posit.slice(18, 24).concat(this.posit.slice(12, 18)));
        var eido = top_side.concat(bot_side);
        var shapes = "";
        var b = "";
        var c = "";
        var eq = "_";
        for (var j = 0; j < 16; j++)
        {
            shapes += this.ty[eido[j]];
            eq = eido[j];
            b += this.tb[eido[j]];
            c += this.col[eido[j]];
        }
        var stickers = b.concat(c);
        var canvas = new Element("div", {style: "height:100px; width:200px;"});
        var z = 1.366; // sqrt(2) / sqrt(1^2 + tan(15 degrees)^2)
        var jg = new Raphael(canvas, 200, 100);
        var arrx, arry;

        var margin = 1;
        var sidewid = .15 * 100 / z;
        var centerx = 50;
        var centery = 50;
        this.radius = (centerx - margin - sidewid * z) / z;
        var w = (sidewid + this.radius) / this.radius		// ratio btw total piece width and radius

        this.angles = [0,0,0,0,0,0,0,0,0,0,0,0,0];
        this.angles2 = [0,0,0,0,0,0,0,0,0,0,0,0,0];

        //initialize angles
        for (var foo = 0; foo < 24; foo++) {
            this.angles[foo] = (17 - foo * 2) / 12 * Math.PI;
            shapes = shapes.concat("xxxxxxxxxxxxxxxx");
        }
        for (foo = 0; foo < 24; foo++) {
            this.angles2[foo] = (19 - foo * 2) / 12 * Math.PI;
            shapes = shapes.concat("xxxxxxxxxxxxxxxx");
        }	//fill and outline first layer
        var shapecounter = 0;
        for (foo = 0; shapecounter < 12; foo++) {
            if (shapes.length <= foo) shapecounter = 12;
            if (shapes.charAt(foo) == "x") shapecounter++;
            if (shapes.charAt(foo) == "c") {
                arrx = [centerx, centerx + this.cos1(shapecounter), centerx + this.cos1(shapecounter + 1) * z, centerx + this.cos1(shapecounter + 2)];
                arry = [centery, centery - this.sin1(shapecounter), centery - this.sin1(shapecounter + 1) * z, centery - this.sin1(shapecounter + 2)];
                jg.fillPolygon(arrx, arry, "#000000", this.colorList[this.colors[stickers.charAt(foo)] + 1]);

                arrx = [centerx + this.cos1(shapecounter), centerx + this.cos1(shapecounter + 1) * z, centerx + this.cos1(shapecounter + 1) * w * z, centerx + this.cos1(shapecounter) * w];
                arry = [centery - this.sin1(shapecounter), centery - this.sin1(shapecounter + 1) * z, centery - this.sin1(shapecounter + 1) * w * z, centery - this.sin1(shapecounter) * w];
                jg.fillPolygon(arrx, arry, "#000000", this.colorList[this.colors[stickers.charAt(16 + shapecounter)] + 1]);

                arrx = [centerx + this.cos1(shapecounter + 2), centerx + this.cos1(shapecounter + 1) * z, centerx + this.cos1(shapecounter + 1) * w * z, centerx + this.cos1(shapecounter + 2) * w];
                arry = [centery - this.sin1(shapecounter + 2), centery - this.sin1(shapecounter + 1) * z, centery - this.sin1(shapecounter + 1) * w * z, centery - this.sin1(shapecounter + 2) * w];
                jg.fillPolygon(arrx, arry, "#000000", this.colorList[this.colors[stickers.charAt(17 + shapecounter)] + 1]);

                shapecounter += 2;
            }
            if (shapes.charAt(foo) == "e") {
                arrx = [centerx, centerx + this.cos1(shapecounter), centerx + this.cos1(shapecounter + 1)];
                arry = [centery, centery - this.sin1(shapecounter), centery - this.sin1(shapecounter + 1)];
                jg.fillPolygon(arrx, arry, "#000000", this.colorList[this.colors[stickers.charAt(foo)] + 1]);

                arrx = [centerx + this.cos1(shapecounter), centerx + this.cos1(shapecounter + 1), centerx + this.cos1(shapecounter + 1) * w, centerx + this.cos1(shapecounter) * w];
                arry = [centery - this.sin1(shapecounter), centery - this.sin1(shapecounter + 1), centery - this.sin1(shapecounter + 1) * w, centery - this.sin1(shapecounter) * w];
                jg.fillPolygon(arrx, arry, "#000000", this.colorList[this.colors[stickers.charAt(16 + shapecounter)] + 1]);

                shapecounter += 1;
            }
        }

        //fill and outline second layer
        centerx += 100;
        shapecounter = 0;
        for (shapecounter = 0; shapecounter < 12; foo++) {
            if (shapes.length <= foo) shapecounter = 12;
            if (shapes.charAt(foo) == "x") shapecounter++;
            if (shapes.charAt(foo) == "c") {
                arrx = [centerx, centerx + this.cos2(shapecounter), centerx + this.cos2(shapecounter + 1) * z, centerx + this.cos2(shapecounter + 2)];
                arry = [centery, centery - this.sin2(shapecounter), centery - this.sin2(shapecounter + 1) * z, centery - this.sin2(shapecounter + 2)];
                jg.fillPolygon(arrx, arry, "#000000", this.colorList[this.colors[stickers.charAt(foo)] + 1]);

                arrx = [centerx + this.cos2(shapecounter), centerx + this.cos2(shapecounter + 1) * z, centerx + this.cos2(shapecounter + 1) * w * z, centerx + this.cos2(shapecounter) * w];
                arry = [centery - this.sin2(shapecounter), centery - this.sin2(shapecounter + 1) * z, centery - this.sin2(shapecounter + 1) * w * z, centery - this.sin2(shapecounter) * w];
                jg.fillPolygon(arrx, arry, "#000000", this.colorList[this.colors[stickers.charAt(28 + shapecounter)] + 1]);

                arrx = [centerx + this.cos2(shapecounter + 2), centerx + this.cos2(shapecounter + 1) * z, centerx + this.cos2(shapecounter + 1) * w * z, centerx + this.cos2(shapecounter + 2) * w];
                arry = [centery - this.sin2(shapecounter + 2), centery - this.sin2(shapecounter + 1) * z, centery - this.sin2(shapecounter + 1) * w * z, centery - this.sin2(shapecounter + 2) * w];
                jg.fillPolygon(arrx, arry, "#000000", this.colorList[this.colors[stickers.charAt(29 + shapecounter)] + 1]);

                shapecounter += 2;

            }
            if (shapes.charAt(foo) == "e") {
                arrx = [centerx, centerx + this.cos2(shapecounter), centerx + this.cos2(shapecounter + 1)];
                arry = [centery, centery - this.sin2(shapecounter), centery - this.sin2(shapecounter + 1)];
                jg.fillPolygon(arrx, arry, "#000000", this.colorList[this.colors[stickers.charAt(foo)] + 1]);
                
                arrx = [centerx + this.cos2(shapecounter), centerx + this.cos2(shapecounter + 1), centerx + this.cos2(shapecounter + 1) * w, centerx + this.cos2(shapecounter) * w];
                arry = [centery - this.sin2(shapecounter), centery - this.sin2(shapecounter + 1), centery - this.sin2(shapecounter + 1) * w, centery - this.sin2(shapecounter) * w];
                jg.fillPolygon(arrx, arry, "#000000", this.colorList[this.colors[stickers.charAt(28 + shapecounter)] + 1]);

                shapecounter += 1;
            }
        }
        return canvas;
    },
    description: function() {
        s = "Algorithm: Random Moves Generator. Parameters: length (" + this.seqlen + "). ";
        s += "Author: Jaap Scherphuis. ";
        return s;
    },
    remove_duplicates: function(arr) {
        var out = [];
        var j = 0;
        for (var i = 0; i < arr.length; i++)
        {
            if (i == 0 || arr[i] != arr[i - 1])
                out[j++] = arr[i];
        }
        return out;
    },
    cos1: function(index) {
        return Math.cos(this.angles[index]) * this.radius;
    },
    sin1: function(index) {
        return Math.sin(this.angles[index]) * this.radius;
    },
    cos2: function(index) {
        return Math.cos(this.angles2[index]) * this.radius;
    }      ,
    sin2: function(index) {
        return Math.sin(this.angles2[index]) * this.radius;
    }
});