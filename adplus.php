<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AdIt+</title>
	
	<link rel=stylesheet href="css/bootstrap.css">
	<link rel=stylesheet href="css/style.css">
	
	<script type="text/javascript" src="js/jquery-1.12.0.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="js/adplus.js"></script>
</head>
 
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="btn myButton" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="glyphicon glyphicon-search"></span>
				</button>
				
				<a id="title" class="navbar-brand" href="#">AdIt+</a>
			</div>
			
			<div id="navbar" class="navbar-collapse collapse">
				<form id="searchBar" class="navbar-form navbar-right" role="form">
					<div class="form-group">
					<input id="searchbox" class="form-control" type="text" placeholder="Search for Ads!" name="query">
					</div>
					<button type="button" class="btn myButton" onclick="searchAd()"><span class="glyphicon glyphicon-search"></span> AdIt!</button>
				</form>
			</div>
		</div>
	</nav>
	
	<div id="result" class="container">
		
		<!-- RESULT FORMAT-->
		<!--div class="row">
			<div class="col-md-4 resultBlock">
				<div class="resultCard" style="background-image: url(http://i.ytimg.com/vi_webp/xUjKzTMSB-g/mqdefault.webp);" data-toggle="modal" data-target="#vidModal" onclick="changeEmbedVideo(1)">
					<div class="resultRank">1</div>
					<h5 class="resultTitle">元大人壽 照顧你的愛</h5>
				</div>
			</div>
			<div class="col-md-4 resultBlock">
				<div class="resultCard" style="background-image: url(http://i.ytimg.com/vi_webp/xUjKzTMSB-g/mqdefault.webp);" data-toggle="modal" data-target="#vidModal" onclick="changeEmbedVideo(1)">
					<div class="resultRank">2</div>
					<h5 class="resultTitle">4G微電影-看曼曼故事，發現你的故事｜中華電信4G［曼曼婚禮］</h5>
				</div>
			</div>
			<div class="col-md-4 resultBlock">
				<div class="resultCard">
					<div class="resultRank">3</div>
				</div>
			</div>
		</div-->
	
	</div>
	
	<!--SHOW VIDEO-->
	
	<div class="modal fade" id="vidModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="container">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<iframe id="videoDisplay" width="853" height="480" src="https://www.youtube.com/embed/xUjKzTMSB-g?rel=0" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="modal-footer videoInfo">
					<div id="rankNum" class="videoRank">1</div>
					<div class="row">
						<div class="col-md-8">
							<div id="videoTitleDisplay" class="videoTitle">This is Title</div>
						</div>
						<div class="col-md-4">
							<div class="videoLike">
								<img class="iconImg goleft" src="./img/thumb-up.png"/><div id="videoLikeNum" class="videoNum goleft">1234</div>
								<img class="iconImg goleft" src="./img/thumb-down.png"/><div id="videoDislikeNum" class="videoNum goleft">1234</div>
								<img class="iconImg goleft" src="./img/view.png"><div id="videoViewNum" class="videoNum goleft">12345678</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
	

</body>
 
</html>