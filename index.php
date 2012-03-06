<?php  
/**
 * @author Wallace Silva contato [at] wallacesilva [dot] com
 * @description 
 */
?>
<html>
<head>
	<title>Get Similar Tracks by Last.fm</title>

	<base href="http://localhost/teste/similar_tracks/" />

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-- Mobile Specific Metas ================================================== -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="Sistema gerador de playlist baseado no Last.fm" />

	<link rel="stylesheet" type="text/css" href="media/css/style.css" />

</head>
<body>

	<div id="loading" style="display:none;">
		<img src="media/images/loading.gif" alt="Carregando, por favor, aguarde." />
	</div>

	<div id="wrapper">

		<div id="header">

			<h1>Get Playlist</h1>
			<h2>Sistema de busca de playlist usando Last.fm</h2>

			<div id="search">

				<form id="frm_search" action="get_similar_tracks.php" method="post">
					
					<label for="artist">Artista/Banda</label>
					<input type="text" name="artist" id="artist" /> 

					<label for="track">Música</label>
					<input type="text" name="track" id="track" /> 

					<input type="submit" class="send" value="OK" />

					<a href="#" class="show_options">Opções</a>


					<div id="search_option">

						<label for="limit">Exibir quantos?</label>
						<select id="limit" name="limit">
							<option value="1">Único (1 item)</option>
							<option value="5" selected="selected">Básico (5 itens)</option>
							<option value="10">Normal (10 itens)</option>
							<option value="30">Grande (30 itens)(mais lento)</option>
						</select>

						<label for="size_cover">Tamanho da capa</label>
						<select id="size_cover" name="size_cover">
							<option value="0">Pequena</option>
							<option value="1" selected="selected">Média</option>
							<option value="2">Grande</option>
							<option value="3">Gigante</option>
						</select>

						<br>
						<label for="show_youtube">Exibir Video do Youtube:</label>
						<input type="radio" name="show_youtube" class="show_youtube" value="0" checked="checked" /> Não
						<input type="radio" name="show_youtube" class="show_youtube" value="1" /> Sim (mais lento)

					</div><!-- end #search_option -->

				</form><!-- end #frm_search -->

			</div> <!-- end #search -->

		</div> <!-- end #header -->

		<div id="container">

			<?php  
				if( !empty($_POST) ) 
					include('get_similar_tracks.php');
				else
					echo '<span class="msg msg_error">Use a busca acima.</span>';
			?>


		</div>

		<div id="footer"></div>

	</div><!-- end #wrapper -->

	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function($){

		// exibe mais opcoes para a busca
		$('.show_options').click(function(){
			$('#search_option').slideToggle();
		});

		// verifica formulario antes de enviar e carrega via ajax
		$('#frm_search').submit(function(){
			//return true;

			$('#container').empty();
			$('#loading').fadeIn();

			var el_track = $(this).find('#track');
			var el_artist = $(this).find('#artist');

			if( el_track.val() == null || el_track.val() == '' ){
				alert('Digite o nome da música corretamente.');
				return false;
			} else if( el_artist.val() == null || el_artist.val() == '' ){
				alert('Digite o nome do artista/banda corretamente.');
				return false;
			}

			$('#search_option').slideUp();

			//var form_url = $(this).attr('action');
			var form_url = this.action;
			//$('#container').empty();
			/*$('#container').load(form_url, function(){
				$('#loading').fadeOut('slow');
			});*/

			var dados_form = jQuery(this).serialize(); // Dados do formulário  

			$.ajax({
			  url: form_url,
			  type: "POST",
			  data: dados_form,
			  success: function(dados_return){
          
          $('#container').html(dados_return);
          $('#loading').fadeOut('slow');

        },
        error:function(XMLHttpRequest, textStatus, errorThrown){
        	var msg = null;
        	var msg_error = null;

        	msg_error = 'Ocorreu um erro ao realizar a busca. Por favor, tente novamente.';
        	msg = '<span class="msg msg_error">'+msg_error+'</span>';
          $('#container').html(msg);
          $('#loading').fadeOut('slow');

        }

			});

			return false;

		});

	});
	</script>
</body>
</html>