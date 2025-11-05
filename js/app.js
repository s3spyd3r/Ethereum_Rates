$(function() {
  $.support.cors = true;

	function refreshRates() {
		$.getJSON(base_url + '/api/v1/rates', function(response) {
			$.each(response,function(i, obj) {
				$('*[data-currency-rel="'+obj.code+'"]').fadeOut().text(" "+ obj.rate_formatted).fadeIn();
			});
		});
	}
	
	$(document).on('keyup',"#filter", function() {
	  var filter = $(this).val(), count = 0;
		$(".cur-list li").each(function() {
		  if ($(this).text().search(new RegExp(filter, "i")) < 0) {
				$(this).hide();
			} else {
				$(this).show();
				count++;
			}
		});
	});
	
	$(document).on('submit','#calculate-form', function(e) {
	  var form = $(this), data = form.serializeArray(), loader = $('#calc-loader');
		
		loader.show();
		
		$.getJSON(base_url + '/api/v1/calculate/'+data[0]['value']+'/'+data[1]['value'], function(response) {
		  loader.hide();
			var template = '<p><span class="calc-amount">'+response.amount+'</span> ETH = <span class="calc-calculation">'+response.calc+'</span> <span class="calc-currency">'+response.currency+'</span></p><br/>'
			
			$('#calc-response').hide().html(template).fadeIn();
			
		})
		e.preventDefault();
	});
	
	$('.selectpicker').selectpicker({
	  style: 'btn-default',
	  size: 14
	});

	$('.selectpicker').selectpicker('val', current_currency);

	$('#calculate-modal').on('hidden.bs.modal', function () {
		$('#calculate-form')[0].reset();
		$('#calc-response').html('');
		$('#calc-loader').hide();
	})
	
	setInterval(function() {
		refreshRates();
	},10000);
	
});
