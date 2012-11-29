if (!window.console) console = {};
console.log = console.log ||
function () {};
console.warn = console.warn ||
function () {};
console.error = console.error ||
function () {};
console.info = console.info ||
function () {};

/**
 * usage: $.preloadImages(images_url+'/ajaxloader.gif', images_url+'/image2.gif');
 **/
jQuery.preloadImages = function(){
    for(var i = 0; i<arguments.length; i++){
        jQuery("<img>").attr("src", arguments[i]);
    }
};
// preload images
$.preloadImages(images_url+'/ajaxloader.gif');

// function to dynamicaly load new CSS/JS files
function loadFile(filename, filetype){
    if (filetype=="js"){ //if filename is a external JavaScript file
        // check if it is not loaded previously
        if (!$("script[src='" + filename + "']").length) {
            var fileref=document.createElement('script')
            fileref.setAttribute("type","text/javascript")
            fileref.setAttribute("src", filename)
            console.log('trying to load js ' + filename);
        }
    }
    else if (filetype=="css"){ //if filename is an external CSS file
        // check if it is not loaded previously
        if (!$("link[href='" + filename + "']").length) {
            console.log('trying to load css ' + filename);
            var fileref=document.createElement("link")
            fileref.setAttribute("rel", "stylesheet")
            fileref.setAttribute("type", "text/css")
            fileref.setAttribute("href", filename)
        }
    }
    if (typeof fileref!="undefined")
    {
        document.getElementsByTagName("head")[0].appendChild(fileref)
    }
}

/* block UI interface initialization */
bui = {
	overlayCSS: {backgroundColor:'#000', opacity:'0.15'},
	// css: { color:'#777', backgroundColor:'#DDD', border:'2px solid #AAA', padding:0, margin:0},
	css: { color:'#555', backgroundColor:'#CCC', border:'2px solid #DDD',  padding:0, margin:0},
	baseZ:10000,
	fadeOut:10,
	message:'<img src="'+images_url+'/ajaxloader.gif" border=0/><br/>'+"Loading..."
};
$(document).ajaxStart(function(){
	$.blockUI(bui);
});
//$.ajaxSetup({dataFilter:preprocessHTML});
$(document).ajaxStop(function(){
	$.unblockUI();
});
/* END block UI interface initialization */