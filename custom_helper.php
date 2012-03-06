<?php 

function clear_name($string=null){

  if( empty($string) )
    return false;

  $string = str_replace(' ', '+', $string);

  return $string;

}

function get_image_src($path=null){

  $img_default = 'media/images/no_cover.jpg';

  if( is_null($path) || $path == '' )
    return $img_default;

  return $path;

}

function get_duration($duration=null){

  if( is_null($duration) || $duration == '' )
    return '0:00';

  return date('i:s', $duration/1000);

}

function get_youtube_url($artist=null, $track=null){

  if( is_null($artist) || is_null($track) )
    return false;

  $artist = clear_name($artist);
  $track = clear_name($track);

  $url_youtube = 'http://gdata.youtube.com/feeds/api/videos?q='.$artist.'-'.$track.'&start-index=21&max-results=10&v=2';

  $xml_utube = file_get_contents($url_youtube);

  $xml = simplexml_load_string($xml_utube);

  foreach( $xml->entry as $entry ){

    return $entry->link[0]['href'];

  }

}


function video_image($url, $link=false)
{
    $image_url = parse_url($url);
    $img_link = null;

    if(isset($image_url['host']))
    {

        if($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com')
        {
            $array = explode("&", $image_url['query']);
            $img_link = "http://img.youtube.com/vi/".substr($array[0], 2)."/default.jpg";
        }
        else if($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com')
        {
            // Preparando o Cache para os dados do Vimeo devido ao seu tempo demorado de resposta (~ 7seg)
            $CI =& get_instance();
            $CI->load->driver('cache');

            if ( ! $hash = $CI->cache->file->get(url_title($url)))
            {
                 $CI->cache->file->save(
                        url_title($url),
                        unserialize(file_get_contents("http://vimeo.com/api/v2/video/".substr($image_url['path'], 1).".php")),
                        1314000);
            }

            $img_link = $hash[0]["thumbnail_small"];
        }
    }

    if( $link )
      return ($img_link) ? $img_link : '/img/icons/video.png';
    else
      return ($img_link) ? '<img src="'.$img_link.'" />' : '<img src="/img/icons/video.png" />';
}

function get_id_video($url, $site = 'youtube')
{
    $id = 0;
    if($site === 'youtube')
    {
        $url = parse_url($url);
        $array = explode("&", $url['query']);
        $id = substr($array[0], 2);
    }
    elseif($site === 'vimeo')
    {
        $array = explode('vimeo.com/',$url);
        $id = $array[1];
    }
    return $id;
}

function link_to_embed($url, $width=355, $height=250){

  $site = (strpos($url, 'youtube')) ? 'youtube' : 'vimeo';
  $id = get_id_video($url, $site);

  if($id != false){

    if($site === 'youtube'){

        return '<iframe height="'.$height.'" width="'.$width.'" src="http://www.youtube.com/embed/'.$id.'?wmode=transparent" frameborder="0"></iframe>';

    } elseif($site === 'vimeo') {

        return '<iframe src="http://player.vimeo.com/video/'.$id.'?title=0&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'" frameborder="0"></iframe>';

    }   

  }

}

function prepare_date_mysql($date){

  $d = substr($date,0,2);
  $m = substr($date,3,2);
  $a = substr($date,6,4);
  
  return $a . '/' . $m  . '/' . $d;

}

function prepare_date_br($date){

  return date('d/m/Y', strtotime($date));

}