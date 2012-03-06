<?php  

include('custom_helper.php');

$br = '<br>';
// http://ws.audioscrobbler.com/2.0/?method=track.getsimilar&artist=cher&track=believe&api_key=b25b959554ed76058ac220b7b2e0a026
$api = 'b25b959554ed76058ac220b7b2e0a026';
$artist = 'rammstein';
$track = 'sonne';
$limit = 10; 
$size_cover = 1;
$show_youtube = false;

$artist = clear_name($_POST['artist']);
$track = clear_name($_POST['track']);
$limit = (int) $_POST['limit'];
if( (int)$_POST['show_youtube'] > 0 )
	$show_youtube = true;
if( isset($_POST['size_cover']) )
	$size_cover = (int)$_POST['size_cover'];


if( $limit < 1 )
	die('Você não deseja exibir nada.');

$url_string = 'http://ws.audioscrobbler.com/2.0/?method=track.getsimilar&artist=%s&track=%s&limit=%s&api_key=%s';

// last.fnm
$url = sprintf($url_string, $artist, $track, $limit, $api);
//echo $url;

// youtube
$url_youtube = 'http://gdata.youtube.com/feeds/api/videos?q='.$artist.'-'.$track.'&start-index=21&max-results=10&v=2';

// getting list on last.fm
$xml_lastfm = file_get_contents( $url );

//echo '<pre>';
//print_r($xml_lastfm);

$lastfm = simplexml_load_string( $xml_lastfm );

//print_r($lastfm);
$i = 0;
?>

<?php if( count($lastfm->similartracks->track) > 0 ): ?>

	<table class="lastfm">

		<tr class="tbl_header">
			<td>Nome da Musica</td>
			<td>Artista</td>
			<td>Duração</td>
			<td>Capa</td>
			<td>Youtube</td>
		</tr>

		<?php foreach( $lastfm->similartracks->track as $track ): ?>

			<?php /*print_r($track);*/ ?>
			<?php 
				$class_bg = '';
				if( $i % 2 == 0 ) 
					$class_bg = 'class=".bg_line"'; 
				else 
					$class_bg = '' ;

				$i++;


				switch ($size_cover) {
					case 0:
						$img_w = ' width="34"';
						$img_h = ' height="34"'; 
						break;
					case 1:
						$img_w = ' width="64"';
						$img_h = ' height="64"'; 
						break;
					case 2:
						$img_w = ' width="126"';
						$img_h = ' height="126"'; 
						break;
					case 3:
						$img_w = ' width="300"';
						$img_h = ' height="300"'; 
						break;
					
					default:
						$img_w = ' width="64"';
						$img_h = ' height="64"'; 
						break;
				}
			?>
			<tr>
				<td <?php echo $class_bg; ?>><?php echo $track->name; ?></td>
				<td <?php echo $class_bg; ?>><?php echo $track->artist->name; ?></td>
				<td <?php echo $class_bg; ?>><?php echo get_duration($track->duration); ?></td>
				<td <?php echo $class_bg; ?>>
					<img src="<?php echo get_image_src($track->image[$size_cover]); ?>" alt="<?php echo $track->artist->name ; ?>" <?php echo $img_w.$img_h; ?> />
				</td>
				<td <?php echo $class_bg; ?>>

					<?php if( $show_youtube ): ?>
						<?php $youtube_url = get_youtube_url($track->artist->name, $track->name); ?>
						<a href="<?php echo $youtube_url; ?>" target="_blank">
							<img src="<?php echo video_image($youtube_url, true);  ?> " alt="" <?php echo $w_h; ?> />
						</a>
					<?php else: ?>
						Não exibido
					<?php endif; ?>
				</td>
			</tr>

		<?php endforeach; ?>

	</table>
<?php else: ?>
	<span class="msg msg_error">Nenhum conteúdo encontrado.</span>
<?php endif; ?>

<?php
//$file_xml = 'youtube_return.xml';
//$xml = simplexml_load_file($file_xml);
//print_r($xml);
//foreach ($xml->entry as $entry) {

	//echo $entry->title.  ' ('.$entry->title.') ';
	//echo $entry->content['type'], $entry->content['src'];
	//echo $entry->link[0]['href'];
	//echo video_image($entry->link[0]['href'], true);
	//echo '<br>';

	# code...
///}
?>