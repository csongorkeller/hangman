<?php
/**
 * Plugin Name:     Akasztófa játék
 * Plugin URI:      
 * Description:     Több csoportos akasztófa plugin.
 * Author:          Keller Csongor
 * Author URI:      
 * Version:         1.2.2
 */

if ( ! defined( 'WP_HANGMAN_ASSETS_URL' ) ) {
	define( 'WP_HANGMAN_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );
}

if ( ! defined( 'WP_HANGMAN_VIEWS_DIR' ) ) {
	define( 'WP_HANGMAN_VIEWS_DIR', __DIR__ . '/views' );
}

require __DIR__ . '/src/class-add-shortcode.php';
require __DIR__ . '/src/enqueue-styles.php';

$hangman_shortcode = new Add_Shortcode(
	include __DIR__ . '/config/shortcodes-config.php'
);


$hangman_shortcode->init();


function install_a_db()
{
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix."akasztofa";

	$sql = "
		CREATE TABLE ".$table_name." (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`words` text NOT NULL,
			`group` varchar(64) DEFAULT '' NOT NULL,
			PRIMARY KEY  (`id`)
		) ".$charset_collate.";";

	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}
register_activation_hook( __FILE__, 'install_a_db');



function akasztofa_add_admin_menu() {
	add_menu_page('Akasztófa', 'Akasztófa', 'manage_options', 'akasztofa', 'akasztofa_lista', 'dashicons-editor-spellcheck', 6);
	add_submenu_page('akasztofa', 'Új kategória', 'Új kategória', 'manage_options', 'uj_akasztofa_kategoria', 'uj_akasztofa_kategoria');
	add_submenu_page(null, 'Kategória törlés', 'Kategória törlés', 'manage_options', 'kategoria_torles_akasztofa', 'kategoria_torles_akasztofa');
}
add_action( 'admin_menu', 'akasztofa_add_admin_menu' );

function akasztofa_lista()
{
?>
<div class="wrap">
	<h1 class="wp-heading-inline">Akasztófa</h1>
	<a href="/wp-admin/admin.php?page=uj_akasztofa_kategoria" class="page-title-action">Új csoport hozzáadása</a>
	<hr class="wp-header-end">
		<table class="widefat fixed" cellspacing="0">
		    <thead>
			    <tr>
					<th id="columnname" class="manage-column column-columnname" scope="col">Csoport neve</th>
					<th id="columnname" class="manage-column column-columnname" scope="col">Shortcode</th>
					<th id="columnname" class="manage-column column-columnname num" style="text-align: right;" scope="col">Szavak</th>

			    </tr>
		    </thead>
		    <tbody>
		    	<?php
		    	global $wpdb;
				$r = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."akasztofa ;", ARRAY_A);
		    	foreach ($r as $g) {
		    	?>
		        <tr class="alternate">
		            <td class="column-columnname">
		            	<?= $g['group']; ?>
		                <div class="row-actions">
		                    <span><a href="/wp-admin/admin.php?page=kategoria_torles_akasztofa&id=<?= $g['id']; ?>">Törlés</a></span>
		                </div>
		            </td>
		            <td>[akasztofa csoport="<?= $g['group']; ?>"]</td>
		            <td class="column-columnname" style="text-align: right;"><?php
		            $szvk = json_decode($g['words']);
		            foreach ($szvk as $sz) {
		            	echo $sz.", ";
		            }
		            ?></td>
		        </tr>
		        <?php
		    	}
		        ?>
		    </tbody>
		    <tfoot>
			    <tr>
					<th id="columnname" class="manage-column column-columnname" scope="col">Csoport neve</th>
					<th id="columnname" class="manage-column column-columnname" scope="col">Shortcode</th>
					<th id="columnname" class="manage-column column-columnname num" style="text-align: right;" scope="col">Szavak</th>
			    </tr>
		    </tfoot>
		</table>
	<br class="clear">
</div>
<?php
}

function uj_akasztofa_kategoria()
{
	global $wpdb;
	if (
		isset($_POST['new_group_name']) and !empty($_POST['new_group_name']) and
		isset($_POST['new_group_words']) and !empty($_POST['new_group_words'])
	) {
		$arrt = "[";
		$s = explode(",", $_POST['new_group_words']);
		for ($i=0; $i < count($s); $i++) { 
			$arrt .= '"'.$s[$i].'"';
			if (count($s)-1 != $i) {
				$arrt .= ", ";
			}
		}
		$arrt .= "]";

		$wpdb->query("INSERT INTO `".$wpdb->prefix."akasztofa` (`group`, `words`) VALUES ('".$_POST['new_group_name']."', '".$arrt."') ;", ARRAY_A);
		header("Location: /wp-admin/admin.php?page=akasztofa");
		exit();
	}
?>
<div class="wrap">
	<h1 class="wp-heading-inline">Akasztófa</h1>
	<a href="" class="page-title-action">Új csoport hozzáadása</a>
	<hr class="wp-header-end">
	<br>
	<div id="namediv" class="stuffbox">
		<div class="inside">
			<br>
			<form action="" method="POST">
				<input type="hidden" name="page" value="uj_akasztofa_kategoria">
				<fieldset>
					<legend class="edit-comment-author">Csoport adatai</legend>
					<table class="form-table editcomment">
						<tbody>
							<tr>
								<td class="first"><label for="name">Név:</label></td>
								<td><input type="text" name="new_group_name" size="30" value="" id="new_group_name"></td>
							</tr>
							<tr>
								<td class="first"><label for="email">Szavak (,-vel elválasztva):</label></td>
								<td>
									<input type="text" name="new_group_words" size="30" value="" id="new_group_words">
								</td>
							</tr>
						</tbody>
					</table>
					<br>
					<button class="">Küldés</button>
				</fieldset>
			</form>
		</div>
	</div>
	<br class="clear">
</div>
<?php
}

function kategoria_torles_akasztofa()
{
	global $wpdb;
	if (isset($_GET['id']) and !empty($_GET['id'])) {		
		$wpdb->query("DELETE FROM `".$wpdb->prefix."akasztofa` WHERE `id` = '".$_GET['id']."' ;", ARRAY_A);
	}
	header("Location: /wp-admin/admin.php?page=akasztofa");
	exit();
}