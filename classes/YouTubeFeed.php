<?php

/**
 * YouTubeFeed
 * Uses RssFeed to parse..
 *
 */

require_once('iSocialFeed.php');
require_once('Base.php');

class YouTubeFeed extends Base implements iSocialFeed {

	public function __construct() {
	}

	public function getFeed ($options) {

		if (empty($options['sa_yt_APIKey'])) return array('error' => 4, 'message' => 'Error fetching YouTube feed: <span class="social-feed-error">No user found.</span>');

		$since_time = empty($options['sa_since_time']) ? 1 : $options['sa_since_time'];

		$url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults='.$options['sa_yt_maxResults'].'&playlistId='.$options['sa_yt_playlist'].'&fields=items%2CnextPageToken%2CprevPageToken&key='.$options['sa_yt_APIKey'];
		if(!ini_get('allow_url_fopen')){
			return array('error'=>4, 'message' => 'Error retrieving YouTube feed, fopen headers not set!');
		}
		$json = file_get_contents($url);
		$feed = json_decode($json);

		//$rss = Feed::loadRss('http://youtube.com/rss/user/' . $options['sa_yt_username']);

		if (count($feed->items) == 0) return array('error' => 6, 'message' => 'Error fetching YouTube feed: <span class="social-feed-error">No items found or user does not exist.</span>');

		$data = array();
		$date_added = time();
		$item_timestamps = array();


		foreach ($feed->items as $item) {
			// $item_timestamp = (int)$item->timestamp;
			$item_timestamp = strtotime((string)$item->snippet->publishedAt);

			if ($item_timestamp > $since_time) {
				$p = array();
				$p['id'] = (string)$item->snippet->resourceId->videoId;
				$p['message'] = (string)$item->snippet->title;
				$p['description'] = (string)$item->snippet->description;

				$video_id = $item->snippet->resourceId->videoId;
				$p['video_id'] = $video_id;

				$link = 'https://www.youtube.com/watch?v='.$video_id;
				$p['link'] = $link;

				$p['author'] = (string)$item->snippet->channelTitle;
				$p['date_added'] = $date_added;
				$p['date_created'] = strtotime((string)$item->snippet->publishedAt);

				$p['picture'] = $item->snippet->default->url;

				$item_timestamps[] = $item_timestamp;
				array_push($data, $p);
			}

		}

		if (count($data) > 0) {
			$res = array('data' => $data, 'since_time' => max($item_timestamps));
			return $res;
		}
		else {
			return array('error' => 3, 'message' => 'No new YouTube items found.');
		}
	}

}
