var resultAd;

function searchAd(){
	$.ajax({
		url:"queryAd.php",
		data:$('#searchBar').serialize(),
		type:"GET",
		datatype:"json",
		
		success: function(json){
			resultAd = JSON.parse(json);
			console.log(resultAd);
			showResult();
			//deBug();
		},
		
		error: function(xhr, ajaxOptions, thrownError){
			alert(xhr.status); 
            alert(thrownError);
		}
		
	});
}


function deBug(){
	var resultDiv = document.getElementById("result");
	var resultNum = resultAd.length;
	var cards = '';
	cards = resultAd;
	resultDiv.innerHTML = cards;
	
}

function showResult(){
	var resultDiv = document.getElementById("result");
	var resultNum = resultAd.length;
	var cards = '';
	
	for(var i = 0; i < resultNum; i++){
		if(i % 3 == 0){
			cards = cards + '<div class="row">';
		}
		
		cards = cards + '<div class="col-md-4 resultBlock">';
		cards = cards +	'<div class="resultCard" style="background-image: url(' + resultAd[i].videoImage + ');" data-toggle="modal" data-target="#vidModal" onclick = changeEmbedVideo('+ (resultAd[i].rank-1) +')>';
		cards = cards + '<div class="resultRank">' + resultAd[i].rank + '</div>';
		cards = cards +	'<h5 class="resultTitle">' + resultAd[i].videoTitle + '</h5>';
		cards = cards + '</div>';
		cards = cards + '</div>';
		if(i%3 == 2){
			cards = cards+'</div>';
		}
	}
	resultDiv.innerHTML = cards;
	
}

function changeEmbedVideo(rankNum){
	console.log(rankNum);
	console.log(resultAd);
	
	document.getElementById("rankNum").src = resultAd[rankNum].rank;
	document.getElementById("videoDisplay").src = resultAd[rankNum].videoEmbed;
	document.getElementById("videoTitleDisplay").innerHTML = resultAd[rankNum].videoTitle;
	document.getElementById("videoLikeNum").innerHTML = resultAd[rankNum].likeNum;
	document.getElementById("videoDislikeNum").innerHTML = resultAd[rankNum].dislikeNum;
	document.getElementById("videoViewNum").innerHTML = resultAd[rankNum].viewNum;
	
	console.log(document.getElementById("videoDisplay").src);
}