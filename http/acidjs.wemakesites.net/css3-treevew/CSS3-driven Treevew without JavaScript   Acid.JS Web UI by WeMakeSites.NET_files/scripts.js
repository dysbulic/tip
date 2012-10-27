site.page = {
    init: function(){
        site._getBody().bind(site.EVENTS.rendered, site.page._init);
    },

    _init: function(){
        site._highlightExperimentsTab();
    }
}

site.page.init();