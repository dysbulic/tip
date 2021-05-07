const REFERRER_URI = Components.Constructor("@mozilla.org/network/standard-url;1", "nsIURI");

var Tip = {
    propStrings: null,

    alert: function(message) {
        alert(message);
    },

    loadURL: function(url, openTab) {
        var REFERRER = new REFERRER_URI;
        REFERRER.spec = 'http://tip.technoanarchy.org/';
        var newTab = getBrowser().addTab(url, REFERRER, null, null);
        getBrowser().selectedTab = newTab;
        //getBrowser().webNavigation.loadURI(url, Components.interfaces.nsIWebNavigation.LOAD_FLAGS_NONE, REFERRER, null, null);
    },

    rally: function() {
        alert(this.getPropertyString('tip.rallyMessage'));
        this.loadURL('chrome://tip/content/main_screen/xul');
    },

    getPropertyString: function(name) {
        if(this.propStrings == null) {
            this.propStrings = document.getElementById('tip-stringbundle');
        }
        return this.propStrings.getString(name);
    }
}
