<?php defined('SYSPATH') OR die('No direct access allowed.');
// vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : 
/* SVN FILE: $Id$ */

class Util_Controller extends Controller
{

	// Set the name of the template to use
	//public $template = 'template';

	public function index() {
	  $this->template->content = 'No direct access.';
	}

	public function info() {
		$this->template = phpinfo();
	}

	public function constants() {
		$this->template->content = '<pre>';
		$this->template->content .= print_r(
			array(
				'EXT' => EXT,
				'KOHANA' => KOHANA,
				'DOCROOT' => DOCROOT,
				'APPPATH' => APPPATH,
				'SYSPATH' => SYSPATH,
				'MODPATH' => MODPATH,
			),
			true
		);
		$this->template->content .= '</pre>';
	}

	public function server_info()
	{
		$mq = (get_magic_quotes_gpc()) ? "on" : "off";

		$content = "<table style='margin: 30px;'><thead/><tbody>";
		$content .= "	<tr><th><hr /></th><td><hr /></td></tr>";
		$content .= "	<tr><th>Kohana Version</th><td>".KOHANA_VERSION."</td></tr>";
		$content .= "	<tr><th>APPPATH</th><td>".APPPATH."</td></tr>";
		$content .= "	<tr><th>DOCROOT</th><td>".DOCROOT."</td></tr>";
		$content .= "	<tr><th><hr /></th><td><hr /></td></tr>";
		$content .= "	<tr><th>Doctrine Version</th><td>".Doctrine::VERSION."</td></tr>";
		$content .= "	<tr><th><hr /></th><td><hr /></td></tr>";
		$content .= "	<tr><th>PHP Version</th><td>".PHP_VERSION."</td></tr>";
		$content .= "	<tr><th><hr /></th><td><hr /></td></tr>";
		$content .= "	<tr><th>Domain</th><td>".$_SERVER['HTTP_HOST']."</td></tr>";
		$content .= "	<tr><th>IP Address</th><td>".$_SERVER['SERVER_ADDR']."</td></tr>";
		$content .= "	<tr><th>Document Root</th><td>".$_SERVER['DOCUMENT_ROOT']."</td></tr>";
		$content .= "	<tr><th>Magic Quotes</th><td>".$mq."</td></tr>";
		$content .= "	<tr><th><hr /></th><td><hr /></td></tr>";
		$content .= "</tbody></table>";

		echo $content;
	}

	public function kill_session() {
		$this->session->destroy();
	}
  

	public function genModels() {
		Doctrine::generateModelsFromDb(DOCTRINE_MODELS);
		die('genModels: done.');
	}

	public function genMigration() {
		Doctrine::generateMigrationsFromDb(APPPATH.'../migrations');
		//Doctrine::generateMigrationsFromModels(APPPATH.'../migrations',APPPATH.'models/doctrine');
		die('genMigration: done.');
	}

	public function updateDb() {
		$migration = new Doctrine_Migration(APPPATH.'../migrations');
		$migration->migrate();
		Doctrine::generateModelsFromDb(DOCTRINE_MODELS);
		die('updateDb: done.');
	}

	public function create_customer() {
// Customers
		$data = array(
			'name'		=> 'Smith & Sons',
			'email'		=> 'kevin@reproconnect.com',
			'address1'	=> '321 Stevens Street',
			'city'		=> 'Geneva',
			'state'		=> 'IL',
			'zip'		=> '60134',
			'phone'		=> '630-938-7601',
			'fax'		=> '630-578-1109',
		);

		$c = new Customers();
		$c->merge($data);


// Users
		$data = array(
			//'customer_id'	=> $c->id,
			'username'		=> 'johnny',
			'password'		=> md5('johnny'),
			'first_name'	=> 'John',
			'last_name'		=> 'Hawley',
			//'email'			=> 'john@smith.com',
			'permissions'	=> 1,
			//'settings'		=> '',
		);

		$c->Users[] = new Users();
		$c->Users[0]->merge($data);
		$c->save();


// Domains
		$data = array(
			'domain'		=> 'efs2.local',
			'customer_id'	=> $c->id,
			//'default_form'	=> $f->id,
		);

		$d = new Domains();
		$d->merge($data);


// Forms
		$data = array(
			'customer_id'	=> $c->id,
			'title'			=> 'General Blueprints',
			'form_def'		=> '{"template":"TwoColumn","columns":[{"forms":[{"header":"Customer Stuff","indicatorGap":"25","fields":[{"type":"text","label":"Company Name","validation":"","width":"","value":"","required":"true","name":"company"},{"type":"text","label":"Contact Name","validation":"","width":"","value":"","name":"contact"},{"type":"textarea","label":"Address","validation":"","width":"","height":"","value":"","name":"address"},{"type":"text","label":"City","validation":"","width":"","value":"","name":"city"},{"type":"text","label":"State","validation":"","width":"30","value":"","name":"state"},{"type":"text","label":"Zip","validation":"","width":"70","value":"","name":"zip"},{"type":"text","label":"Phone","validation":"regex","expression":"\\\\d||3~~-\\\\d||3~~-\\\\d||4~~","tooltiptext":"Must be in the form ###-###-####","width":"","value":"","name":"phone"},{"type":"text","label":"Fax","validation":"regex","expression":"\\\\d||3~~-\\\\d||3~~-\\\\d||4~~","tooltiptext":"Must be in the form ###-###-####","width":"","value":"","name":"fax"},{"type":"text","label":"Email Address","validation":"","width":"","value":"","name":"email"},{"label":"Blank Space","type":"spacer","height":"12","name":"Blank_Space"},{"type":"textarea","label":"Comments","validation":"","width":"","height":"105","value":"","name":"comments"}]}]},{"forms":[{"header":"Order Stuff","indicatorGap":"30","fields":[{"type":"text","label":"Ordered By","validation":"","width":"","value":"","required":"true","name":"Ordered_By"},{"type":"text","label":"Job Name","validation":"","width":"","value":"","name":"job_name","required":"true"},{"type":"text","label":"Project #","validation":"","width":"","value":"","name":"Project_"},{"type":"text","label":"P.O.","validation":"","width":"","value":"","name":"PO"},{"type":"text","label":"Date/Time Needed","validation":"","width":"","value":"","name":"Date_Time_Needed"},{"type":"text","label":"No. of Originals","validation":"","width":"","value":"","name":"No_of_Originals"},{"type":"text","label":"No. of Copies","validation":"","width":"","value":"","name":"No_of_Copies"},{"type":"text","label":"Original Size","validation":"","width":"","value":"","name":"Original_Size"},{"type":"radiogroup","label":"Bindings","values":["Yes","No"],"direction":"horizontal","name":"Bindings"},{"type":"checkbox","label":"We have a Pickup","name":"We_have_a_Pickup"}]}]}]}',
		);

		$d->DefaultForm = new Forms();
		$d->DefaultForm->merge($data);
		return $d->save();

	}


	public function create_sample_job($cust_id) {

		$_SESSION['user']['username'] == 'kevin' or die('Invalid access!');
		$customer = Doctrine::getTable('Customers')->find($cust_id) or die('Invalid customer number');
		$count = Doctrine::getTable('Uploads')->findByCustomer_id($cust_id)->count();

		$u = new Uploads;
		$u->directory = 'Sample Job';
		$u->customer_id = $cust_id;
		$u->upload_count = $count;
		$u->subject = 'Sample Job';
		$u->comments = 'This is only a sample job.';
		$u->company = 'Main Street Creations';
		$u->contact = 'John Smith';
		$u->address1 = '1111 East Hawkins Dr.';
		$u->city = 'Anywhere';
		$u->state = 'TX';
		$u->zip = '98765-4321';
		$u->phone = '123-456-7890';
		$u->fax = '123-456-7891';
		$u->email = 'john.smith@example.com';
		$u->data = '{"PO":"AA-1234567-01","Ordered By":"Jane Doe","Original Size":"Letter","No Of Copies":"5","Date Time Needed":"2010-12-25 EOB"}';
		$u->status = 'ready';
		$u->submitted = date('Y-m-d H:i:s');
		$u->updated_at = date('Y-m-d H:i:s');

		$i = 0;
		foreach(glob(APPPATH.'../uploads/sample_job/*') as $filename) {
			//echo "$filename size " . filesize($filename);
			$f = $u->Files[$i++];
			$f->name = basename($filename);
			$f->size = filesize($filename);
			$f->mime = mime_content_type($filename);
			$f->submitted = date('Y-m-d H:i:s');
		}

		$u->file_count = $i;
		$u->save();

		$this->template->content = 'Sample job created.';
	}


	public function convert_aces() {
		$aces = Doctrine_Query::create()
			->from('Sermons')
			->where('type = ?', 'ACE')
			->execute()
			;

		foreach($aces as $old) {
			$new = Doctrine::getTable('Aces')->findOneByDate($old->date);
			if(!$new) {
				$new = new Aces();
				$values = $old->toArray();
				unset($values['id']);
				$new->merge($values);
			}
			else {
				$new->merge($old->toArray());
			}

			// Convert fields
			$new->created_at = $old->created;
			//$new->updated_at = $old['modified'];
			$new->teacher = $old->preacher;
			$new->comment = $old->scripture;

			$new->save();
			echo $new->date . "\n";
		}
	}

	public function convert_portraits() {
		$portraits = Doctrine_Query::create()
			->from('Sermons')
			->where('type = ?', 'Portrait')
			->execute()
			;

		foreach($portraits as $old) {
			$new = Doctrine::getTable('Portraits')->findOneByDate($old->date);
			if(!$new) {
				$new = new Portraits();
				$values = $old->toArray();
				unset($values['id']);
				$new->merge($values);
			}
			else {
				$new->merge($old->toArray());
			}

			// Convert fields
			//$new->created_at = $old->created;
			//$new->updated_at = $old['modified'];
			$new->speaker = $old->preacher;
			$new->comment = $old->scripture;

			$new->save();
			echo $new->date . "\n";
		}
	}
}
?>
