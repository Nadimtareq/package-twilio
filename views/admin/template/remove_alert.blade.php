<script type="text/javascript">
$(document).ready(function() {
	$('.ui.child.checkbox').checkbox({
	     onChecked: function() {
	     	$('#removeButton').removeClass("disabled");
	     	$('#personButton').removeClass("disabled");
	     	$('#openButton').removeClass("disabled");
	     	$('#closeButton').removeClass("disabled");
		}
	});

  	$('.invoices.send.button').click(function(event) {
  		event.preventDefault();

  		var redirectUrl = $(this).attr('href');

  		swal({   
			title: "Weet u het zeker?",   
			text: "Dat u deze factuur wilt versturen naar het e-mailadres: " + $(this).data('email') + ".",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",  
			cancelButtonText: "Nee",   
			confirmButtonText: "Ja, ik weet het zeker!",   
			closeOnConfirm: false 
		}, function() { 
			window.location.href = redirectUrl;
		});

		return false;
  	});

  	$('#exampleButton').click(function(event) {
  		swal({   
			title: "Weet u het zeker?",   
			text: "Het beluisteren van een voorbeeld bericht bedraagt €0,25 per bericht.",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",  
			cancelButtonText: "Nee",   
			confirmButtonText: "Ja, ik weet het zeker!",   
			closeOnConfirm: false 
		}, function() { 
			
		});

		return false;
  	});

  	$('.loginAs').click(function(event) {
  		event.preventDefault();

  		var redirectUrl = $(this).attr('href');

  		swal({   
			title: "Weet u het zeker?",   
			text: "Weet u zeker dat u wil inloggen op het account van het gekozen restaurant?",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",  
			cancelButtonText: "Nee",   
			confirmButtonText: "Ja, ik weet het zeker!",   
			closeOnConfirm: false 
		}, function() { 
			window.location.href = redirectUrl;
		});
  	});

  	$('.removeButton').click(function(event) {
  		var redirectUrl = $(this).attr('href');

		event.preventDefault();
  		swal({   
				title: "Weet u het zeker?",   
				text: "Weet u zeker dat u deze selectie wil verwijderen?",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",  
				cancelButtonText: "Nee",   
				confirmButtonText: "Ja, ik weet het zeker!",   
				closeOnConfirm: false 
			}, function() { 
			 window.location.href = redirectUrl;
		});
  	});

  	$('#loseButton').click(function(event) {
  		$(this).removeClass('disabled');
  		$('#actionMan').val($(this).val());
  		if ($('[name="id[]"]:checked').length == 0) {
  			swal({   
				title: "Er is een fout opgetreden",   
				text: "U bent vergeten om een optie te selecteren.",   
				type: "warning"
			});
		} else {
			swal({   
				title: "Weet u het zeker?",   
				text: "Weet u zeker dat u deze selectie wil oplslaan als LOSE?",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",  
				cancelButtonText: "Nee",   
				confirmButtonText: "Ja, ik weet het zeker!",   
				closeOnConfirm: false 
			}, function() { 
				$('#formList').submit(); 
				return true;
			});
		}

		return false;
  	});

  	$('#winButton').click(function(event) {
  		$(this).removeClass('disabled');
  		$('#actionMan').val($(this).val());
  		if ($('[name="id[]"]:checked').length == 0) {
  			swal({   
				title: "Er is een fout opgetreden",   
				text: "U bent vergeten om een optie te selecteren.",   
				type: "warning"
			});
		} else {
			swal({   
				title: "Weet u het zeker?",   
				text: "Weet u zeker dat u deze selectie wil oplslaan als WIN?",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",  
				cancelButtonText: "Nee",   
				confirmButtonText: "Ja, ik weet het zeker!",   
				closeOnConfirm: false 
			}, function() { 
				$('#formList').submit(); 
				return true;
			});
		}

		return false;
  	});

  	$('#favoriteButton').click(function(event) {
  		$(this).removeClass('disabled');
  		$('#actionMan').val($(this).val());
  		if ($('[name="id[]"]:checked').length == 0) {
  			swal({   
				title: "Er is een fout opgetreden",   
				text: "U bent vergeten om een optie te selecteren.",   
				type: "warning"
			});
		} else {
			swal({   
				title: "Weet u het zeker?",   
				text: "Weet u zeker dat u deze selectie wil oplslaan als favoriet?",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",  
				cancelButtonText: "Nee",   
				confirmButtonText: "Ja, ik weet het zeker!",   
				closeOnConfirm: false 
			}, function() { 
				$('#formList').submit(); 
				return true;
			});
		}

		return false;
  	});

  	$('#removeButton').click(function() {
  		$('#actionMan').val($(this).val());
  		if ($('[name="id[]"]:checked').length == 0) {
  			swal({   
				title: "Er is een fout opgetreden",   
				text: "U bent vergeten om een optie te selecteren.",   
				type: "warning"
			});
		} else {
			swal({   
				title: "Weet u het zeker?",   
				text: "Weet u zeker dat u deze selectie wil verwijderen?",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",  
				cancelButtonText: "Nee",   
				confirmButtonText: "Ja, ik weet het zeker!",   
				closeOnConfirm: false 
			}, function() { 
				$('#formList').submit(); 
				return true;
			});
		}

		return false;
	});

  	$('#noShow, #yesShow').click(function() {
  		$('#actionMan').val($(this).val());
  		if ($('[name="id[]"]:checked').length == 0) {
  			swal({   
				title: "Er is een fout opgetreden",   
				text: "U bent vergeten om een optie te selecteren.",   
				type: "warning"
			});
		} else {
			swal({   
				title: "Weet u het zeker?",   
				text: "Weet u zeker dat u deze selectie op " + ($(this).val() == 'noshow' ? 'no' : '') + " show wil zetten?",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",  
				cancelButtonText: "Nee",   
				confirmButtonText: "Ja, ik weet het zeker!",   
				closeOnConfirm: false 
			}, function() { 
				$('#formList').submit(); 
				return true;
			});
		}

		return false;
	});

  	$('#personButton').click(function() {
  		$('#actionMan').val($(this).val());
  		if ($('[name="id[]"]:checked').length == 0){
  			swal({   
				title: "Er is een fout opgetreden",   
				text: "U bent vergeten om een optie te selecteren.",   
				type: "warning"
			});
		} else {
			var optionsArray = [];
			$('[name="id[]"]:checked').each(function(key, value) {
				var parents = $(this).parent().parent().parent().find('.personReserved');

				if(parents.data('count') >= 1) {
					optionsArray.push(key);
				}
			});

			swal(
				{   
					title: "Weet u het zeker?",   
					showCancelButton: true,   
					closeOnConfirm: false,
					confirmButtonColor: "#DD6B55",  
					cancelButtonText: "Nee",   
					confirmButtonText: "Ja, ik weet het zeker!",   
					text: 'Alle geselecteerde plaatsen (optioneel):  ' + (optionsArray.length >= 1 ? '<strong>Er ' + (optionsArray.length == 1 ? 'is' : 'zijn') +' ' + optionsArray.length +' data geselecteerd met nog reserveringen erin.</strong>' : ''),     
					type: "input",   
					inputPlaceholder: "",
					html: true
				},
				function(inputValue){    
					 if (inputValue != '') {   
						$('input:checkbox:checked').closest('tr').each(function() {
							var personInput = $(this).find('.personInput');
							personInput.find('input').val(inputValue);
						});
					}

					$('#formList').submit(); 
					return true;
				},
				function() {
					$('#formList').submit(); 
					return true;
				}
			);
		}

		return false;
	});
	
  	$('#openButton').click(function() {
  		$('#actionMan').val($(this).val());
  		if ($('[name="id[]"]:checked').length == 0) {
  			swal({   
				title: "Er is een fout opgetreden",   
				text: "U bent vergeten om een optie te selecteren.",   
				type: "warning"
			});
 		} else {
			swal({   
				title: "Weet u het zeker?",   
				text: ($(this).attr('data-text') ? $(this).data('text') : "Weet u zeker dat u deze selectie wil openen?"),   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",  
				cancelButtonText: "Nee",   
				confirmButtonText: "Ja, ik weet het zeker!",   
				closeOnConfirm: false 
			}, function() { 
				$('#formList').submit(); 
				return true;
			});
		}

		return false;
	});

  	$('#closeButton').click(function() {
  		$('#actionMan').val($(this).val());
  		if ($('[name="id[]"]:checked').length == 0) {
  			swal({   
				title: "Er is een fout opgetreden",   
				text: "U bent vergeten om een optie te selecteren.",   
				type: "warning"
			});
		} else {
			var optionsArray = [];
			var nameArray = [];
			$('[name="id[]"]:checked').each(function(key, value) {
				var parents = $(this).parent().parent().parent().find('.personReserved');

				if(parents.data('count') >= 1) {
					optionsArray.push(key);
					nameArray.push($(value).data('date'));
				}
			});

			swal({   
				title: "Weet u het zeker?",   
				text: '<span style="color: #C41010;">Weet u zeker dat u deze selectie wil sluiten? ' + (optionsArray.length >= 1 ? '<strong>Er ' + (optionsArray.length == 1 ? 'is' : 'zijn') +' ' + optionsArray.length +' data geselecteerd met nog reserveringen erin.</strong><br /><br /></span>' + nameArray.join() : ''),   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",  
				cancelButtonText: "Nee",   
				confirmButtonText: "Ja, ik weet het zeker!",   
				closeOnConfirm: false,
				html: true
			}, function() { 
				$('#formList').submit(); 
				return true;
			});
		}

		return false;
	});

	$('.personInput input').keypress(function() {
		$(this).closest('tr').each(function() {
			var personInput = $(this).find('input[name="id[]"]');
			personInput.prop('checked', true);
			
	     	$('#personButton').removeClass("disabled");
		});
	});
});
</script>