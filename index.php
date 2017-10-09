<?php
ob_start();

if (!is_file('library/config.php')) {
  die();
}
else
{
	require_once 'library/framework.class.php';
  $frame_work = new FrameWork();

	$main_currency = $frame_work->getMainCurrecyRate(((isset($_GET['currency']))?$_GET['currency']:''));
	$popular_currencies = $frame_work->getPopularCurrencyRates();
	$all_currencies = $frame_work->getAllCurrencyRates();
	$template_color = $frame_work->getTemplateSettings();

	if ((int)$main_currency['error'])
		header('Location: '.BASE_URL.'?404=true');//REDIRECT TO 404 PAGE
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?=META_DESCRIPTION?>">
  <title>
    <?=((isset($_GET['currency'])) ? META_INDIVIDUAL_TITLE.$main_currency['name'] : META_TITLE)?>
  </title>
  <link href="<?=$frame_work->base_url;?>/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=$frame_work->base_url;?>/css/bootstrap-select.min.css" rel="stylesheet">
  <link href="<?=$frame_work->base_url;?>/css/style.min.css" rel="stylesheet">
</head>
<body>
<nav id="top-navigation" class="navbar navbar-default navbar-static-top" style="background-color:<?=$template_color;?>;">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?=$frame_work->base_url?>"><?=BRAND_NAME?></a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#" data-toggle="modal" data-target="#calculate-modal">Calculator</a></li>
      </ul>
    </div>
  </div>
</nav>
<div id="main-banner" class="jumbotron" style="background-color:<?=$template_color;?>;">
  <div class="container">
    <div class="row">
      <? if(isset($_GET['404']) && $_GET['404'] == "true"): header("HTTP/1.0 404 Not Found");?>
      <div class="col-md-12 text-center" id="error-404">
        <h1>Error 404 <br/>
          Could not be found</h1>
        <hr/>
      </div>
      <? endif ?>
      <div class="col-md-6">
        <h3>CURRENT RATE</h3>
        <p><small class="">1 ETH <i class="glyphicon glyphicon-transfer"></i>
          <?=$main_currency['name']?>
          -
          <?=$main_currency['code']?>
        </small></p>
        <h1><img src="<?=$frame_work->base_url;?>/images/flags/<?=$main_currency['code'];?>.png" class="flag-icon"><span data-currency-rel="<?=$main_currency['code']?>">
          <?=$main_currency['rate_formatted']?>
        </span></h1>
        <br/>
      </div>
      <div id="popular-cur" class="col-md-6">
        <h3>POPULAR CURRENCIES</h3>
        <p><small>1 ETH <i class="glyphicon glyphicon-transfer"></i></small></p>
        <ul id="popular-cur-list">
          <?php foreach($popular_currencies as $object):?>
          <li><a href="<?=$frame_work->base_url;?>/currency/<?=$object['code'];?>"><img src="<?=$frame_work->base_url;?>/images/flags/<?=$object['code'];?>.png" class="flag-icon">
            <?=$object['name'];?>
            </a> <span class="pull-right"> <span data-currency-rel="<?=$object['code'];?>">
            <?=$object['rate_formatted'];?>
            </span> </span>
            <?=$object['code'];?>
          </span></li>
          <? endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <span id="cur-filter" class="pull-right">
    <div class="input-group pull-right">
      <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-filter"></i></span>
      <input type="text" class="form-control" id="filter" placeholder="Filter Currencies..." aria-describedby="basic-addon1">
    </div>
  </span>
  <a class=""></a>
  <h3 class="under-line">All Exchange Rates <small>(79 Currencies)</small></h3>
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-4">
      <ul class="cur-list">
        <?php foreach($all_currencies[0] as $object):?>
        <li><a href="<?=$frame_work->base_url;?>/currency/<?=$object['code'];?>"><img src="<?=$frame_work->base_url;?>/images/flags/<?=$object['code'];?>.png" class="flag-icon">
          <?=$object['name'];?>
          </a><span class="pull-right"><span data-currency-rel="<?=$object['code'];?>">
          <?=$object['rate_formatted'];?>
          </span>
          <?=$object['code'];?>
        </span></li>
        <? endforeach; ?>
      </ul>
    </div>
    <div class="col-md-4">
      <ul class="cur-list">
        <?php foreach($all_currencies[1] as $object):?>
        <li><a href="<?=$frame_work->base_url;?>/currency/<?=$object['code'];?>"><img src="<?=$frame_work->base_url;?>/images/flags/<?=$object['code'];?>.png" class="flag-icon">
          <?=$object['name'];?>
          </a><span class="pull-right"><span data-currency-rel="<?=$object['code'];?>">
          <?=$object['rate_formatted'];?>
          </span>
          <?=$object['code'];?>
        </span></li>
        <? endforeach; ?>
      </ul>
    </div>
    <div class="col-md-4">
      <ul class="cur-list">
        <?php foreach($all_currencies[2] as $object):?>
        <li><a href="<?=$frame_work->base_url;?>/currency/<?=$object['code'];?>"><img src="<?=$frame_work->base_url;?>/images/flags/<?=$object['code'];?>.png" class="flag-icon">
          <?=$object['name'];?>
          </a><span class="pull-right"><span data-currency-rel="<?=$object['code'];?>">
          <?=$object['rate_formatted'];?>
          </span>
          <?=$object['code'];?>
        </span></li>
        <? endforeach; ?>
      </ul>
    </div>
  </div>
  <div class="clearfix"></div>
  <br/>
  <br/>
  <hr>
  <div class="modal fade" id="calculate-modal" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="calculate-form" class="form-inline">
          <div class="modal-body text-center">
            <h2 class="modal-title" id="myModalLabel">Convert Ethereums</h2>
            <div id="calc-response"></div>
            <div class="form-group">
              <input type="text" class="form-control" name="amount" autofocus placeholder="Ethereum amount: 1">
            </div>
            <div class="form-group">
              <select class="selectpicker show-menu-arrow" name="currency" data-live-search="true" title="Choose currency.." style="width:100%;">
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
                <option value="JPY">JPY</option>
                <option value="CAD">CAD</option>
                <option value="AUD">AUD</option>
                <option value="CNY">CNY</option>
                <option value="CHF">CHF</option>
                <option value="SEK">SEK</option>
                <option value="NZD">NZD</option>
                <option value="KRW">KRW</option>
                <option value="AED">AED</option>
                <option value="AFN">AFN</option>
                <option value="ALL">ALL</option>
                <option value="AMD">AMD</option>
                <option value="ANG">ANG</option>
                <option value="AOA">AOA</option>
                <option value="ARS">ARS</option>
                <option value="AWG">AWG</option>
                <option value="AZN">AZN</option>
                <option value="BAM">BAM</option>
                <option value="BBD">BBD</option>
                <option value="BDT">BDT</option>
                <option value="BGN">BGN</option>
                <option value="BHD">BHD</option>
                <option value="BIF">BIF</option>
                <option value="BMD">BMD</option>
                <option value="BND">BND</option>
                <option value="BOB">BOB</option>
                <option value="BRL">BRL</option>
                <option value="BSD">BSD</option>
                <option value="BTN">BTN</option>
                <option value="BWP">BWP</option>
                <option value="BYR">BYR</option>
                <option value="BZD">BZD</option>
                <option value="CDF">CDF</option>
                <option value="CLF">CLF</option>
                <option value="CLP">CLP</option>
                <option value="COP">COP</option>
                <option value="CRC">CRC</option>
                <option value="CUP">CUP</option>
                <option value="CVE">CVE</option>
                <option value="CZK">CZK</option>
                <option value="DJF">DJF</option>
                <option value="DKK">DKK</option>
                <option value="DOP">DOP</option>
                <option value="DZD">DZD</option>
                <option value="EEK">EEK</option>
                <option value="EGP">EGP</option>
                <option value="ETB">ETB</option>
                <option value="FJD">FJD</option>
                <option value="FKP">FKP</option>
                <option value="GEL">GEL</option>
                <option value="GHS">GHS</option>
                <option value="GIP">GIP</option>
                <option value="GMD">GMD</option>
                <option value="GNF">GNF</option>
                <option value="GTQ">GTQ</option>
                <option value="GYD">GYD</option>
                <option value="HKD">HKD</option>
                <option value="HNL">HNL</option>
                <option value="HRK">HRK</option>
                <option value="HTG">HTG</option>
                <option value="HUF">HUF</option>
                <option value="IDR">IDR</option>
                <option value="ILS">ILS</option>
                <option value="INR">INR</option>
                <option value="IQD">IQD</option>
                <option value="IRR">IRR</option>
                <option value="ISK">ISK</option>
                <option value="JEP">JEP</option>
                <option value="JMD">JMD</option>
                <option value="JOD">JOD</option>
                <option value="KES">KES</option>
                <option value="KGS">KGS</option>
                <option value="KHR">KHR</option>
                <option value="KMF">KMF</option>
                <option value="KPW">KPW</option>
                <option value="KWD">KWD</option>
                <option value="KYD">KYD</option>
                <option value="KZT">KZT</option>
                <option value="LAK">LAK</option>
                <option value="LBP">LBP</option>
                <option value="LKR">LKR</option>
                <option value="LRD">LRD</option>
                <option value="LSL">LSL</option>
                <option value="LTL">LTL</option>
                <option value="LVL">LVL</option>
                <option value="LYD">LYD</option>
                <option value="MAD">MAD</option>
                <option value="MDL">MDL</option>
                <option value="MGA">MGA</option>
                <option value="MKD">MKD</option>
                <option value="MMK">MMK</option>
                <option value="MNT">MNT</option>
                <option value="MOP">MOP</option>
                <option value="MRO">MRO</option>
                <option value="MUR">MUR</option>
                <option value="MVR">MVR</option>
                <option value="MWK">MWK</option>
                <option value="MXN">MXN</option>
                <option value="MYR">MYR</option>
                <option value="MZN">MZN</option>
                <option value="NAD">NAD</option>
                <option value="NGN">NGN</option>
                <option value="NIO">NIO</option>
                <option value="NOK">NOK</option>
                <option value="NPR">NPR</option>
                <option value="OMR">OMR</option>
                <option value="PAB">PAB</option>
                <option value="PEN">PEN</option>
                <option value="PGK">PGK</option>
                <option value="PHP">PHP</option>
                <option value="PKR">PKR</option>
                <option value="PLN">PLN</option>
                <option value="PYG">PYG</option>
                <option value="QAR">QAR</option>
                <option value="RON">RON</option>
                <option value="RSD">RSD</option>
                <option value="RUB">RUB</option>
                <option value="RWF">RWF</option>
                <option value="SAR">SAR</option>
                <option value="SBD">SBD</option>
                <option value="SCR">SCR</option>
                <option value="SDG">SDG</option>
                <option value="SGD">SGD</option>
                <option value="SHP">SHP</option>
                <option value="SLL">SLL</option>
                <option value="SOS">SOS</option>
                <option value="SRD">SRD</option>
                <option value="STD">STD</option>
                <option value="SVC">SVC</option>
                <option value="SYP">SYP</option>
                <option value="SZL">SZL</option>
                <option value="THB">THB</option>
                <option value="TJS">TJS</option>
                <option value="TMT">TMT</option>
                <option value="TND">TND</option>
                <option value="TOP">TOP</option>
                <option value="TRY">TRY</option>
                <option value="TTD">TTD</option>
                <option value="TWD">TWD</option>
                <option value="TZS">TZS</option>
                <option value="UAH">UAH</option>
                <option value="UGX">UGX</option>
                <option value="UYU">UYU</option>
                <option value="UZS">UZS</option>
                <option value="VEF">VEF</option>
                <option value="VND">VND</option>
                <option value="VUV">VUV</option>
                <option value="WST">WST</option>
                <option value="XAF">XAF</option>
                <option value="XAG">XAG</option>
                <option value="XAU">XAU</option>
                <option value="XCD">XCD</option>
                <option value="XOF">XOF</option>
                <option value="XPF">XPF</option>
                <option value="YER">YER</option>
                <option value="ZAR">ZAR</option>
                <option value="ZMW">ZMW</option>
                <option value="ZWL">ZWL</option>
              </select>
            </div>
            <div class="clearfix"></div>
            <img src="<?=$frame_work->base_url;?>/images/loader.gif" id="calc-loader"/></div>
          <div class="modal-footer text-center">
            <div class="text-center">
              <button type="submit" class="btn btn-default">Convert</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <footer>
    <p>© <?=date('Y')?> <a href="http://www.rodriguesfilipe.net">http://www.rodriguesfilipe.net</a></p>
  </footer>
</div>
<script src="<?=$frame_work->base_url;?>/js/jquery.min.js"></script> 
<script src="<?=$frame_work->base_url;?>/js/bootstrap.min.js"></script> 
<script src="<?=$frame_work->base_url;?>/js/bootstrap-select.min.js"></script> 
<script>
$(function() {

  $.support.cors = true;

	function refreshRates() {
		  $.getJSON('<?=$frame_work->base_url;?>/api/v1/rates', function(response) {
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
		
		$.getJSON('<?=$frame_work->base_url;?>/api/v1/calculate/'+data[0]['value']+'/'+data[1]['value'], function(response) {
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

	$('.selectpicker').selectpicker('val', "<?=((isset($_GET['currency'])) ? $_GET['currency'] : 'USD')?>");

	$('#calculate-modal').on('hidden.bs.modal', function () {
		$('#calculate-form')[0].reset();
		$('#calc-response').html('');
		$('#calc-loader').hide();
	})
	
	setInterval(function() {
		refreshRates();
	},10000);
	
});
</script>
</body>
</html>