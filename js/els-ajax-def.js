jQuery(document).ready(function($){

	$('#ajaxGetData').submit(function(){
		$('#elsSubmit').attr('disabled', true);
		$('.loading').addClass('is-active');
		var showPost = $('#showPost').val();
		data= {
			action: 'els_show_post_results',
			elsnonce: els_var.els_ajax_nonce,
			info: {showPost: showPost}
		};
		$.post(ajaxurl, data, function(response){
          $("#the-list").html(response);
          $('.loading').removeClass('is-active');
          $('#elsSubmit').attr('disabled', false);
	    });

	    return false;

	});
});