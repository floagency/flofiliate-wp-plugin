(function ($) {
	"use strict";
	$(function () {
		// Place your public-facing JavaScript here
	console.log(typeof(window.__floff_tracker) );
	
		if( litsic.length && typeof(window.__floff_tracker) != 'undefined'){

			try{
				// if 'litsic' get param is defined in hte URL, then we push it to affiliate API server to track the code 
			  	//console.log(litsic);
				window.__floff_tracker.comm.push(api_push_url, {'f': 1, 'uid': window.__floff_tracker.fn.retrieve(), 'tsic': litsic} ); 
			}catch(err){
			  	//Handle errors here
			  	console.log(err); 
			}
			
		}
	});
}(jQuery));