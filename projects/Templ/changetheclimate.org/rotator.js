/*
Change the Climate Banner Rotator

Support Change the Climate by putting this banner rotator on your web site!


email questions to webmaster@changetheclimate.org

*/

<!--
if (navigator.appVersion.substring(0,1) >= 4) {
	base_url = "http://www.changetheclimate.org";

	banners = new Array();
	urls = new Array();
	
	var subscript = 8;
}

function init_vars() {
	banners[1] = new Image;
	banners[1].src = base_url+"/images/nevada_banner.gif";
	banners[2] = new Image;
	banners[2].src = base_url+"/images/money.gif";
	banners[3] = new Image;
	banners[3].src = base_url+"/images/crowd.gif";
	banners[4] = new Image;
	banners[4].src = base_url+"/images/politics.jpg";
	banners[5] = new Image;
	banners[5].src = base_url+"/images/cancer.jpg";
	banners[6] = new Image;
	banners[6].src = base_url+"/images/police.jpg";
	banners[7] = new Image;
	banners[7].src = base_url+"/images/kids.jpg";
	banners[8] = new Image;
	banners[8].src = base_url+"/images/supportus-banner.gif";
	
	
	
	urls[1] = base_url+"/campaigns";
	urls[2] = base_url+"/ads/";
	urls[3] = base_url+"/campaigns";
	urls[4] = "https://econtributor.votenet.com/Contribution/Contribution.cfm?AID=QJETGMINZFFD";
	urls[5] = base_url+"/ads/";
	urls[6] = base_url+"/supportus/";
	urls[7] = base_url+"/kids/";
	urls[8] = "https://econtributor.votenet.com/Contribution/Contribution.cfm?AID=QJETGMINZFFD";

	rotate();
}

function rotate() {
        setTimeout("chng_banner()",10000); 
}

function chng_banner() {
        subscript+=1;
        if (subscript > 8) { subscript = 1; } 
        document.banner.src = banners[subscript].src;
        rotate();
} 

function jump() {
        window.location.href=urls[subscript];
}

//-->