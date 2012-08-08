jPlex.provide('scrambles.ScrambleFactory', {});
jPlex.extend('scrambles.ScrambleFactory', {
    _scramblerInstances: [],
    get: function(type) {
        // lazy init
        if (!this._scramblerInstances[type])
            this._scramblerInstances[type] = this._createNew(type);
        return this._scramblerInstances[type]
    },
    _createNew: function(type) {
        var inst = null;
        switch(type) {
            case -1: //CLOCK
                jPlex.include('scrambles.Clock',true);
                inst = new scrambles.Clock();
                break;
            case -2: //MEGA
                jPlex.include('scrambles.Megaminx',true);
                inst = new scrambles.Megaminx(10,7);
                break;
            case -3: //SQ1
                jPlex.include('scrambles.Square1',true);
                inst = new scrambles.Square1(40);
                break;
            case -4: //PYRA
                jPlex.include('scrambles.Pyraminx',true);
                inst = new scrambles.Pyraminx();
                break;
            case 2:  //POCKET
                jPlex.include('scrambles.Pocket',true);
                inst = new scrambles.Pocket();
                break;
            case 3:
                jPlex.include('scrambles.Cube',true);
                inst = new scrambles.Cube(3,false,25);
                break;
            case 4:
            case 5:
            case 6:
            case 7:
                jPlex.include('scrambles.Cube',true);
                inst = new scrambles.Cube(type,true,type*20-40);
                break; 
            default:
                alert("Yet to be implemented");
                break;
        }
        return inst;
    }
});