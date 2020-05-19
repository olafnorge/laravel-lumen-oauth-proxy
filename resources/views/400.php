<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Error 400</title>

    <meta name="author" content="<?php echo config('app.host') ?>">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

  </head>
  <body style="height: 100vh;">

    <div class="container-fluid h-100">
	<div class="row h-100">
		<div class="col-12 col-md-6 offset-md-3 my-auto">
			<div class="page-header">
				<h1>400. <small class="text-muted"> That’s an error.</small></h1>
			</div>
            <h6><b>Error: redirect_uri_mismatch</b></h6>
			<p>
                The redirect URI in the request, <?php echo $redirectUri ?>,
                does not match the one authorized for the OAuth client.
			</p>
			<h6>Request Details</h6>
			<ul>
				<li class="list-item">
                    <?php echo $clientId ?>
				</li>
				<li class="list-item">
                    redirect_uri=<?php echo $redirectUri ?>
				</li>
				<li class="list-item">
                    scope=<?php echo $scope ?>
				</li>
				<li class="list-item">
                    response_type=<?php echo $responseType ?>
				</li>
				<li class="list-item">
                    state=<?php echo $state ?>
				</li>
			</ul>
            <p><small class="text-muted">That’s all we know.</small></p>
		</div>
	</div>
</div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
