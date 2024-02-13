<html>
	<head>
		<title>Web Radio</title>
		<link rel="icon" type="image/x-icon" href="assets/radio.png">
		<link rel="stylesheet" href="lib/bootstrap.min.css" />
		<link rel="stylesheet/less" type="text/css" href="style.less" />
		<meta name="debug" content="<?php echo isset($_GET['DEBUG']) ? 'true' : 'false'; ?>"/>
	</head>
	<body id="page-radio">

		<div class="page-wrapper" id="page-wrapper-radio">
			<div class="container">
				<div class="row align-items-start">

					<div class="col-12">
						<div class="ctrl-group" id="ctrl-group-top">
							<div class="ctrl" id="ctrl-banner">
								<img src="assets/radio.png">
								<h1>Web Radio</h1>
							</div>
						</div>
					</div>

					<div class="col-8">
						<div class="ctrl-group ctrl-group-bordered" id="ctrl-group-main">
							<div class="ctrl" id="ctrl-currently-playing">
								<span class="stream-name">STREAM</span><span class="separator"> - </span><span class="song-name">SONG</span>
							</div>
							<div class="ctrl" id="ctrl-radio-controls">
								<a href="#action" data-action="play" class="btn btn-primary" id="ctrl-radio-controls-play">Play</a>
								<a href="#action" data-action="pause" class="btn btn-primary" id="ctrl-radio-controls-pause">Pause</a>
								<div id="ctrl-radio-controls-volume">
									<input type="range" min="0" max="100" value="40" step="5" />
								</div>
							</div>
						</div>

						<?php if (isset($_GET['DEBUG'])) { ?>
						<div class="ctrl-group ctrl-group-bordered" id="ctrl-group-debug">
							<div class="ctrl" id="">
								<div class="ctrl" id="ctrl-debug-title">
									<h3>Developer tools</h3>
								</div>
								<div class="ctrl" id="ctrl-debug-log">
									<h5>API call log</h5>
									<div id="ctrl-debug-log-wrapper">
										<table class="table table-striped">
											<thead>
												<th scope="col">Timestamp</th>
												<th scope="col">Action</th>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
								<div class="ctrl" id="ctrl-debug-status">
									<h5>MPD status</h5>
									<div id="ctrl-debug-status-wrapper">
										<table class="table table-striped">
											<thead>
												<th scope="col">Key</th>
												<th scope="col">Value</th>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
								<div class="ctrl" id="ctrl-debug-control">
									<a href="#action" data-action="refresh_status" class="btn btn-primary" id="ctrl-radio-controls-refresh">Refresh</a>
								</div>
							</div>
						</div>
						<?php } ?>

					</div>

					<div class="col-4">
						<div class="ctrl-group ctrl-group-bordered" id="ctrl-group-stations">
							<div class="ctrl" id="ctrl-stationlist">
								<ul class="" id="station-list">
								</ul>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<script src="lib/jquery.min.js"></script>
		<script src="lib/bootstrap.min.js"></script>
		<script src="lib/less.min.js"></script>
		<script src="script.js"></script>
	</body>
</html>