(function() {
    console.log('loaded');
    // jQuery().fancybox({
    //     selector : '.tpotyVotingForm img'
    // });
    jQuery().fancybox({
        selector : '.tpotyVotingForm img',
		afterClose: function( instance, current ) {
			jQuery('.tpotyVotingForm img').show();
        }
    });
})();