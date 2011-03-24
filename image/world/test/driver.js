$(document).ready(function() {
    scripts = [ ".../lib/jQuery/lib/Tip/jQuery/1.3.2-wjh/test/1/js" ];
    for(var i = 0; i < scripts.length; i++) { alert(scripts[i]); $.getScript(scripts[i]); }});
