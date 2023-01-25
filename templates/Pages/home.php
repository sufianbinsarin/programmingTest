<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.10.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->disableAutoLayout();

$checkConnection = function (string $name) {
    $error = null;
    $connected = false;
    try {
        $connection = ConnectionManager::get($name);
        $connected = $connection->connect();
    } catch (Exception $connectionError) {
        $error = $connectionError->getMessage();
        if (method_exists($connectionError, 'getAttributes')) {
            $attributes = $connectionError->getAttributes();
            if (isset($attributes['message'])) {
                $error .= '<br />' . $attributes['message'];
            }
        }
    }

    return compact('connected', 'error');
};

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace templates/Pages/home.php with your own version or re-enable debug mode.'
    );
endif;

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        Cards Programming Test
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake', 'home']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <?= $this->fetch('script') ?>
</head>
<body>
    <header>
        <div class="container text-center">
            <h1>
                Welcome to Cards Programming Test
            </h1>
        </div>
    </header>
    <main class="main">
        <div class="container">
            <div class="content">
                <div class="row">
                    <div class="column">
                       <form method="post" action="/submitPlayerNumber">
					   Player: <input type='text' name='player' id='player' value=''/>
					   <button type='button' name='button1' id='button1'>Submit</button>
					   </form>
                    </div>
                </div>     
				<div class="row" style='display:none;' id='div_hide'>
                    <div class="column">
					<table class="table table-bordered table-striped" id="example">
					  <thead>
						<tr>
						  <th scope="col" width='20%'>Players</th>
						  <th scope="col" width='80%'>Cards</th>
						</tr>
					  </thead>
					  <tbody>
					  </tbody>
					</table>
					</div>
                </div>
            </div>
        </div>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
	
    var table =$('#example').DataTable();

	$("#button1").click(function() {
	
	  var m=$('#player').val();
	  // alert(m);
	  if((Number.isInteger(parseInt(m))) && (parseInt(m)>0))
	  {
		  $.post("<?= $this->Url->build('/submitPlayerNumber', ['fullBase' => true]) ?>", { player: $('#player').val()} )
		  .done(function( data ) {	 
			table.clear();
			for (let i = 0; i < data.data.length; i++) {
			  table.row.add(data.data[i]).draw(false);
			}
			table.draw(); 
			$('#div_hide').show();
		  });
	  }
	  else
	  {
			Swal.fire({
			  icon: 'error',
			  title: 'Oops...',
			  text: 'Input value does not exist or value is invalid'
			})
	  }
	});
});
</script>
</html>
