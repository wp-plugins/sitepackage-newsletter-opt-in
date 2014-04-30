<?php
/**
 * Plugin Name: sitepackage:// Newsletter Opt-in
 * Plugin URI: http://www.sitepackage.de
 * Description: This plugin provides a newsletter opt-in form that is connected to sitepackage://. To use this plugin you need to register for the sitepackage:// newsletter system on http://www.sitepackage.de. There is a free version available.
 * Version: 1.0
 * Author: webworx GmbH
 * Author URI: http://www.webworx.de
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
* Widget registration
*/
add_action ('widgets_init', create_function ('', 'register_widget ("sitepackage_widget");'));

/**
* Widget class
*
* @version 1.0
* @since 03.09.2012
* @author webworx GmbH <info@webworx.de>
* @access public
*/
class Sitepackage_Widget extends WP_Widget
{
	/**
	* Field configuration
	*
	* @access protected
	* @var array $fields
	*/	
	protected $fields = array (
		'title' 		=> array ('title' => 'Titel', 'default' => 'Newsletter', 'formtype' => 'input', 'strip' => true),
		'introtext'		=> array ('title' => 'Einleitungstext', 'default' => '', 'formtype' => 'textarea', 'strip' => true),
		'domain'		=> array ('title' => 'Projektdomain', 'default' => '', 'formtype' => 'input', 'strip' => true),
		'form_id'		=> array ('title' => 'Formular-ID', 'default' => '', 'formtype' => 'input', 'strip' => true),
		'text_field'	=> array ('title' => 'Feldbezeichner', 'default' => 'E-Mail-Adresse', 'formtype' => 'input', 'strip' => true),
		'text_button'	=> array ('title' => 'Schaltfl&auml;che', 'default' => 'Abonnieren', 'formtype' => 'input', 'strip' => true),
		'privacynote'	=> array ('title' => 'Privatsph&auml;rehinweis ', 'default' => 'Eine Abmeldung ist jederzeit m&ouml;glich.', 'formtype' => 'input', 'strip' => true),
	);	  
	
	/**
	* Constructor
	*
	* @access public
	* @return void
	*/
	public function __construct()
	{
		parent::__construct ('sitepackage_widget', 'sitepackage:// Newsletter Opt-in',
			array('description' => 'Mit dem WordPress Widget von sitepackage:// l&auml;sst sich ein Formular ins eigene Blog integrieren, &uuml;ber das Besucher einen E-Mail-Newsletter abonnieren k&ouml;nnen.')
		);
	}

	/**
	* Output
	*
	* @access public
	* @param array $args Widget Argumente
	* @param array $instance Gespeicherte Werte
	* @return void
	*/	 
	public function widget ($args, $instance)
	{
		extract ($args);

		echo $before_widget;

		// Title
		if (!empty ($instance['title'])) {
			echo $before_title.$instance['title'].$after_title;
		}
		
		// Introductory text
		if (!empty ($instance['introtext'])) {
			echo '<p>'.$instance['introtext'].'</p>'."\n";
		}
		
		// Form
		echo '<form action="http://formular.sitepackage.de/senden.php" method="post">'."\n";
		echo '<p><label for="email">'.$instance['text_field'].'</label> <input name="email" type="text" id="email" /></p>'."\n";
		echo '<input type="hidden" name="domain" value="'.$instance['domain'].'" /><input type="hidden" name="id" value="'.$instance['form_id'].'" />'."\n";
		echo '<p><button type="submit">'.$instance['text_button'].'</button></p>'."\n";
		echo '</form>'."\n";
		
		// Privacy notice
		if (!empty ($instance['privacynote'])) {
			echo '<p style="font-size: 0.8em">'.$instance['privacynote'].'</p>'."\n";
		}
		
		echo $after_widget;
	}

	/**
	* Strip form values before saving
	*
	* @access public
	* @param array $new_instance Neue Werte
	* @param array $old_instance Alte Werte
	* @return array $instance Array mit den ges�uberten Werten
	*/	 
	public function update ($new_instance, $old_instance)
	{		
		$instance = array ();
		
		foreach ($this -> fields as $key => $val) {
			$instance[$key] = strip_tags ($new_instance[$key]);
		}

		return $instance;
	}

	/**
	* Build form
	*
	* @access public
	* @param array $instance Gespeicherte Werte
	* @return void
	*/	 
	public function form ($instance)
	{		
		foreach ($this -> fields as $key => $val) {
			if (isset ($instance[$key])) {
				$value = $instance[$key];
			} else {
				$value = $val['default'];
			}
			
			echo '<p>'."\n";
			echo '<label for="'.$this -> get_field_id ($key).'">'.$val['title'].':</label>'."\n";
			
			if ($val['formtype'] == 'textarea') {
				echo '<textarea class="widefat" id="'.$this -> get_field_id ($key).'" name="'.$this -> get_field_name ($key).'">'.esc_attr ($value).'</textarea>'."\n";
			} else {
				echo '<input class="widefat" id="'.$this -> get_field_id ($key).'" name="'.$this -> get_field_name ($key).'" type="text" value="'.esc_attr ($value).'" />'."\n";
			}
			echo '</p>'."\n";
		}		
	}
}
?>