<?php
	
	//$htmlBody = <<<END;
	
	class videoData{
		
		public $videoId = "";
		
		//Data for Display
		public $title = "";
		public $description = "";
		public $img = "";
		
		//Data for Scoring : video['statistics']
		public $viewCount = 0;
		public $likeCount = 0;
		public $dislikeCount = 0;
		//public $favoriteCount = 0;
		public $commentCount = 0;
		
		public $viewTime = 0;	//sec or hour
		
		public $score = 0;
		//other video datas
		
		public function assignViewTime($dateTime)
		{
			$date1 = new DateTime($dateTime);
			$date_now = new DateTime(date('Y-m-d'));
			$interval = $date1->diff($date_now);
			// shows the total amount of days (not divided into years, months and days like above)
			return $interval->days;
		}
		
		public function calculateScore(){
			$score = (($this->likeCount - $this->dislikeCount) + $this->commentCount)/$this->viewCount + log10($this->viewCount/$this->viewTime);
			return $score;
		}
		
		public function makeArray($rank){
			$thisArray = array("rank" => $rank, "videoTitle" => $this->title, "videoImage" => $this->img, "videoEmbed" => "https://www.youtube.com/embed/".$this->videoId."?rel=0", "likeNum" => $this->likeCount, "dislikeNum" => $this->dislikeCount, "viewNum" => $this->viewCount);
			return $thisArray;
		}
	}
	function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) 
	{
		$sort_col = array();
		foreach ($arr as $key=> $row) 
		{
			$sort_col[$key] = $row[$col];
		}

		array_multisort($sort_col, $dir, $arr);
	}
	
	$videoDataArray = array();

	//GET VIDEO SEARCH RESULT
	//if ($_GET["query"] && $_GET["query"] != NULL) {
		// Call set_include_path() as needed to point to your client library.
		require_once "./GoogleSrc/Google/autoload.php";
		require_once "./GoogleSrc/Google/Client.php";
		require_once "./GoogleSrc/Google/Service/YouTube.php";

		/*
		* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
		* Google Developers Console <https://console.developers.google.com/>
		* Please ensure that you have enabled the YouTube Data API for your project.
		*/
	
		$query = "汽車 廣告";
		//$query = $_GET["query"]." 廣告";	// add '廣告', 'advertisement' or 'commercial'
		$maxResults = 30;	//can only be [0~50]
		$DEVELOPER_KEY = 'AIzaSyAFgolJAE1T90re6jsA7JuEsKaMIDkIUpo';

		$client = new Google_Client();
		$client->setDeveloperKey($DEVELOPER_KEY);

		// Define an object that will be used to make all API requests.
		$youtube = new Google_Service_YouTube($client);

		try{
			// Call the search.list method to retrieve results matching the specified query term.
			// Need to decide maxResults !!!!!!!!!!!!!!!!!!!!
			$searchResponse = $youtube->search->listSearch("id,snippet", array( "q" => $query, "maxResults" => $maxResults, ));
		
		
			// Add each result to the appropriate list, and then display the lists of
			// matching videos, channels, and playlists.
			
			// Retrieve search result videos
			foreach ($searchResponse['items'] as $searchResult) {	
				$videoResult = new videoData();
				switch ($searchResult['id']['kind']) {
					case 'youtube#video':
						$videoResult->title = $searchResult['snippet']['title'];
						$videoResult->videoId = $searchResult['id']['videoId'];
						$videoResult->description = $searchResult['snippet']['description'];
						$videoResult->img = $searchResult['snippet']['thumbnails']['high']['url'];
						array_push($videoDataArray, $videoResult);
					break;
					case 'youtube#channel':
						//$channels .= sprintf('<li>%s (%s)</li>',
						//$searchResult['snippet']['title'], $searchResult['id']['channelId']);
					break;
					case 'youtube#playlist':
						//$playlists .= sprintf('<li>%s (%s)</li>',
						//$searchResult['snippet']['title'], $searchResult['id']['playlistId']);
					break;
				}
			}
		} catch (Google_Service_Exception $e) {
			//$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',htmlspecialchars($e->getMessage()));
			echo json_encode(htmlspecialchars($e->getMessage()));
		} catch (Google_Exception $e) {
			//$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',htmlspecialchars($e->getMessage()));
			echo json_encode(htmlspecialchars($e->getMessage()));
		}
		
		$list_id = array();
		foreach ($videoDataArray as $vid_info)
		{
			array_push($list_id, $vid_info->videoId);
		}
		$videoIds = join(",", $list_id);
		
		//Retrieve information of each video
		try{
			$videoResponse = $youtube->videos->listVideos('snippet, statistics', array('id' => $videoIds,));
			foreach ($videoResponse['items'] as $video_index => $videoResult) {
				//$videoDataArray[$video_index]->viewTime = $videoResult['snippet']['publishedAt'];
				$videoDataArray[$video_index]->viewTime = $vid_info->assignViewTime($videoResult['snippet']['publishedAt']);
				$videoDataArray[$video_index]->viewCount = $videoResult['statistics']['viewCount'];
				$videoDataArray[$video_index]->likeCount = $videoResult['statistics']['likeCount'];
				$videoDataArray[$video_index]->dislikeCount = $videoResult['statistics']['dislikeCount'];
				$videoDataArray[$video_index]->commentCount = $videoResult['statistics']['commentCount'];
			}
			
		} catch (Google_Service_Exception $e) {
			//$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',htmlspecialchars($e->getMessage()));
			echo json_encode(htmlspecialchars($e->getMessage()));
		} catch (Google_Exception $e) {
			//$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',htmlspecialchars($e->getMessage()));
			echo json_encode(htmlspecialchars($e->getMessage()));
		}
	
		// TODO : Retrieve comments of each video
		
		//Analysis and Ranking
		// 1. assign score (calculateScore function)
		$list_score = array();
		$temp = array();
		foreach ($videoDataArray as $vid_index => $vid_info)
		{
			$vid_info->score = $vid_info->calculateScore();
			$temp['org_rank'] = $vid_index;
			$temp['score'] = $vid_info->score;
			array_push($list_score, $temp);
		}
		// 2. rank by score 
		array_sort_by_column($list_score, 'score');
		$video_result = array();
		foreach($list_score as $video_rank => $vids)
		{
			array_push($video_result, $videoDataArray[$vids['org_rank']]->makeArray($video_rank) );
		}
		// Encode to json string
		print_r($list_score);
		//print_r($videoDataArray);
		print_r($video_result);
		//echo json_encode($video_result);
	//}
	
	
	/*
	$videoUrl = array();
	if(isset($_GET["query"]) && $_GET["query"] != NULL){
		$query = $_GET["query"];
		$query = str_replace(" ", "+", $query);
		$query = urlencode($query);
		
		for($pageNum = 1; $pageNum < 3; $pageNum++){
			$youtubeUrl = "https://www.youtube.com/results?search_query=".$query."&page=".$pageNum;
			
			$contents = getVideoFile($youtubeUrl);
			retrieveVideoUrl($contents);
			
			//analysis
		}
		
		//parse analysis result to json string
		
		$video_result = array();
		$resultNum = count($videoUrl);
		for($i = 0; $i < $resultNum; $i++){
			$video_result[$i] = array("rank" => $i+1, "videoTitle" => "Title", "videoImage" => "http://i.ytimg.com/vi_webp/xUjKzTMSB-g/mqdefault.webp", "videoEmbed" => "https://www.youtube.com/embed/".$videoUrl[$i]."?rel=0");
		}
		
	}
	*/
	
	/*
	$video_result = array();
	$video_result[0] = array("rank" => 1, "videoTitle" => "元大人壽 照顧你的愛", "videoImage" => "http://i.ytimg.com/vi_webp/xUjKzTMSB-g/mqdefault.webp", "videoEmbed" => "https://www.youtube.com/embed/xUjKzTMSB-g?rel=0");
	$video_result[1] = array("rank" => 2, "videoTitle" => "4G微電影-看曼曼故事，發現你的故事｜中華電信4G［曼曼婚禮］", "videoImage" => "http://i.ytimg.com/vi_webp/kvWRfLnZd_4/mqdefault.webp", "videoEmbed" => "https://www.youtube.com/embed/kvWRfLnZd_4?rel=0");
	*/
	
	//echo json_encode($video_result);
	
	/*
	function getVideoFile($url){
		$options = array("http" => array("user_agent" => "Mozilla/5.0 (compatible; Megarush bos; +http://megarush.net/meta-search-engine-php/)"));
		$context = stream_context_create($options);
		$contents = file_get_contents($url, false, $context);
		return $contents;
	}
	*/
	/*
	function retrieveVideoUrl($contents){
		global $videoUrl;
		//regular expression get candidate videos
		$pattern = '/<a href="(.+?)" class="yt-uix-sessionlink yt-uix-tile-link yt-ui-ellipsis yt-ui-ellipsis-2       spf-link ".*?>/';
		preg_match_all($pattern, $contents, $retrieveUrls);
		//retrieve : '/watch?v=W--GTD_x_Iw'
		for($i = 0; $i < count($retrieveUrls[1]); $i++){
			$retrieveUrls[1][$i] = preg_replace("/\/watch\?v=/", '', $retrieveUrls[1][$i]);
			$retrieveUrls[1][$i] = preg_replace("/&amp;list=(.+)/", '', $retrieveUrls[1][$i]);
			array_push($videoUrl, $retrieveUrls[1][$i]);
		}
	}
	*/
?>