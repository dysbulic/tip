var Tip = {
    propStrings: null,

    alert: function(message) {
        alert(message);
    },

    rally: function() {
        alert(this.getPropertyString('tip.rallyMessage'));
    },

    getPropertyString: function(name) {
        if(this.propStrings == null) {
            this.propStrings = document.getElementById('tip-stringbundle');
        }
        return this.propStrings.getString(name);
    }
}
