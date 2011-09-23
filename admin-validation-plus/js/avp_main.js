jQuery(function($){
	var total_masks = maskedinputs.length;
   
	for ( i = 0; i < total_masks ; i++) {
		$(maskedinputs[i].selector).mask(maskedinputs[i].value);
	}   
});