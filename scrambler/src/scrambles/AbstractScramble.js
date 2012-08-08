jPlex.provide('scrambles.AbstractScramble', {
    initialize: function() {},
    colorList: new Array(
            'y', "yellow", "yellow",
            'b', "blue", "blue",
            'r', "red", "red",
            'w', "white", "white",
            'g', "green", "green",
            'o', "#ffA040", "orange", // 'orange' is not an official html colour name
            'p', "purple", "purple",
            '0', "gray", "grey"      // used for unrecognised letters, or when zero used.
            ),

    setColor: function(colorString) {
        // expand colour string into 6 actual html color names
        this.colors = $A();
        for (var k = 0; k < 6; k++) {
            this.colors[k] = this.colorList.length - 3;	// gray
            for (var i = 0; i < this.colorList.length; i += 3) {
                if (colorString.charAt(k) == this.colorList[i]) {
                    this.colors[k] = i;
                    break;
                }
            }
        }
    },
    scramble: function() {
    },
    scramblestring: function() {
    },
    /**
     * Builds an HTML Element that represents the scramble
     * Returns null if not available
     */
    imagestring: function() {
    },
    description: function() {
    }
});