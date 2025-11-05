<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= META_DESCRIPTION ?>">
  <title>
    <?= isset($main_currency['name']) ? META_INDIVIDUAL_TITLE . $main_currency['name'] : META_TITLE ?>
  </title>
  <link href="<?= $base_url ?>/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= $base_url ?>/css/bootstrap-select.min.css" rel="stylesheet">
  <link href="<?= $base_url ?>/css/style.min.css" rel="stylesheet">
</head>
<body>

<nav id="top-navigation" class="navbar navbar-default navbar-static-top" style="background-color:<?= $template_color ?>;">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?= $base_url ?>"><?= BRAND_NAME ?></a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#" data-toggle="modal" data-target="#calculate-modal">Calculator</a></li>
      </ul>
    </div>
  </div>
</nav>

<div id="main-banner" class="jumbotron" style="background-color:<?= $template_color ?>;">
  <div class="container">
    <div class="row">
      <?php if (isset($error_404) && $error_404): ?>
      <div class="col-md-12 text-center" id="error-404">
        <h1>Error 404 <br/>
          Could not be found</h1>
        <hr/>
      </div>
      <?php endif; ?>
      <div class="col-md-6">
        <h3>CURRENT RATE</h3>
        <p><small class="">1 ETH <i class="glyphicon glyphicon-transfer"></i>
         <?= isset($main_currency['name']) ? $main_currency['name'] : 'Unknown Currency' ?>
         -
         <?= isset($main_currency['code']) ? $main_currency['code'] : 'N/A' ?>
        </small></p>
        <h1><img src="<?= $base_url ?>/images/flags/<?= $main_currency['code'] ?? '' ?>.png" class="flag-icon"><span data-currency-rel="<?= $main_currency['code'] ?? '' ?>">
          <?= isset($main_currency['rate_formatted']) ? $main_currency['rate_formatted'] : 'N/A' ?>
        </span></h1>
        <br/>
      </div>
      <div id="popular-cur" class="col-md-6">
        <h3>POPULAR CURRENCIES</h3>
        <p><small>1 ETH <i class="glyphicon glyphicon-transfer"></i></small></p>
        <ul id="popular-cur-list">
          <?php foreach ($popular_currencies as $object): ?>
          <li><a href="<?= $base_url ?>/currency/<?= $object['code'] ?>"><img src="<?= $base_url ?>/images/flags/<?= $object['code'] ?>.png" class="flag-icon">
            <?= $object['name'] ?>
            </a> <span class="pull-right"> <span data-currency-rel="<?= $object['code'] ?>">
            <?= $object['rate_formatted'] ?>
            </span> </span>
            <?= $object['code'] ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <?php if (isset($error_message)): ?>
    <div class="alert alert-danger" role="alert">
      <?= $error_message ?>
    </div>
  <?php endif; ?>

  <span id="cur-filter" class="pull-right">
    <div class="input-group pull-right">
      <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-filter"></i></span>
      <input type="text" class="form-control" id="filter" placeholder="Filter Currencies..." aria-describedby="basic-addon1">
    </div>
  </span>
  <a class=""></a>
  <h3 class="under-line">All Exchange Rates <small>(<?= count(array_merge(...$all_currencies)) ?> Currencies)</small></h3>
  <div class="clearfix"></div>
  <div class="row">
    <?php foreach ($all_currencies as $column): ?>
    <div class="col-md-4">
      <ul class="cur-list">
        <?php foreach ($column as $object): ?>
        <li><a href="<?= $base_url ?>/currency/<?= $object['code'] ?>"><img src="<?= $base_url ?>/images/flags/<?= $object['code'] ?>.png" class="flag-icon">
          <?= $object['name'] ?>
          </a><span class="pull-right"><span data-currency-rel="<?= $object['code'] ?>">
          <?= $object['rate_formatted'] ?>
          </span>
          <?= $object['code'] ?>
        </span></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endforeach; ?>
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
                <?php foreach (array_merge(...$all_currencies) as $currency): ?>
                  <option value="<?= $currency['code'] ?>"><?= $currency['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="clearfix"></div>
            <img src="<?= $base_url ?>/images/loader.gif" id="calc-loader"/>
          </div>
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
    <p>&copy; <?= date('Y') ?> <a href="http://www.rodriguesfilipe.net">http://www.rodriguesfilipe.net</a></p>
  </footer>
</div>

<script>
  var base_url = "<?= $base_url ?>";
  var current_currency = "<?= $main_currency['code'] ?? 'USD' ?>";
</script>
<script src="<?= $base_url ?>/js/jquery.min.js"></script>
<script src="<?= $base_url ?>/js/bootstrap.min.js"></script>
<script src="<?= $base_url ?>/js/bootstrap-select.min.js"></script>
<script src="<?= $base_url ?>/js/app.js"></script>
</body>
</html>