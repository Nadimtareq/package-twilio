<script>
	
	$(document).ready(function() {

		$('#searchModal').on('click', function() {
			$('#hideForm').show();

			$('.ui.modal').modal('show');
			$('.ui.modal .header').html('Zoeken');
			$('.ui.modal .content').html($('.ajaxSearchForm').html());

			$('.dropdownSearchInput').keypress(function (e) {
			  	if (e.which == 13) {
					var inputValue = encodeURIComponent($('.dropdownSearchInput:last').val());
					window.location.href = $('#ajaxSearchForm').data('url') + '?q=' + inputValue;
			    	return false;    //<---- Add this line
			  	}
			});

			$('.dropdownSearchButton').on('click', function() {
					var inputValue = encodeURIComponent($('.dropdownSearchInput:last').val());
				window.location.href = $('.ajaxSearchForm').data('url') + '?q=' + inputValue;
			});
		});


	});
</script>