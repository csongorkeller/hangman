<?php
/**
 * @var array  $atts    Parsed shortcode attributes.
 * @var string $content Shortcode content.
 */
wp_enqueue_style( 'wp-hangman-font' );
wp_enqueue_style( 'wp-hangman-styles' );

global $wpdb;

$r = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."akasztofa WHERE `group` = '".$atts['csoport']."' LIMIT 1;", ARRAY_A);
if (!count($r)) {
	echo "Ilyen csoport nem létezik!";
}
?>
<script type="text/javascript">
	function shuffle(a) {
		for (let i = a.length - 1; i > 0; i--) {
			const j = Math.floor(Math.random() * (i + 1));
			[a[i], a[j]] = [a[j], a[i]];
		}
		return a;
	}
	var wordtomb2 = shuffle(<?= $r[0]['words']; ?>);
</script>

<div id="hangman-game">
		<div class="hangman-flex-item score">
		<div id="hangman-notices"></div>
		
		<div id="hangman-figure">
			<canvas id="hangman-canvas"></canvas>
		</div>
		<div id="hangman-answer-placeholders"></div>
	</div>
	<div class="hangman-flex-item">
		<?php if ( $content ) : ?>
			<div id="hangman-intro-content">
				<?php echo $content; ?>
			</div>
		<?php endif; ?>
		
		<div id="hangman-available-characters">
			<ul id="hangman-available-characters-list"></ul>
		</div>
	</div>

</div>

<div id="hangman-game-win" style="max-width: 550px; border: 1px solid black; padding: 15px 20px; margin: auto;">
	<h5 style="text-align: center; font-weight: bold;">A játék véget ért</h5>
<div class="w3-light-grey">
  <div class="w3-green" id="csik" style="height:24px;"></div>
</div>
<br>


	<p style="text-align: center; font-weight: bold;"><span id="percent"></span>%</p><br>
	<p style="font-weight: bold;">Sikeresen eltalát szavak száma: <span id="correctword" style="font-weight: bold;"></span> </p>
	<p style="font-weight: bold;">Hibás szavak száma: <span id="wrongword" style="font-weight: bold;"></span> </p>
	<a href="https://www.facebook.com/sharer/sharer.php" target="_blank"><img src="https://profitquery-a.akamaihd.net/website_2.0/img/blog/share_buttons/facebook_share_icon.png" style="display: block;margin: auto; max-width: 100px;"></a>

	<div class="googleadd" style="text-align:center;">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Fejtsdmeg-akasztofa300x250 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-9088496051359445"
     data-ad-slot="8112331455"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
	</div>
</div>
