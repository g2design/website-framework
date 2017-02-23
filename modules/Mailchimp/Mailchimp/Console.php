<?php

namespace Mailchimp;

use DrewM\MailChimp\MailChimp As MailAPI;

function print_line($message) {
	print $message . PHP_EOL;
}

function sep() {
	print_line('=======================================');
}

function _sepmessage($message) {
	print_line('');
	sep();
	print_line($message);
	sep();
	print_line('');
}

function _rep($message) {
	print $message . "\r\r";
}

class Console {

	var $mailchimp = null;

	public function __construct() {
		_sepmessage('Connecting to Mailchimp');

		$this->mailchimp = new MailAPI('3115b92c9649c45915d902f99fc9f5a9-us10');
		$this->mailchimp->verify_ssl = false;
	}

	/**
	 * 
	 * @param \Mailchimp\callable $function
	 */
	function forlists(callable $function) {
		$m = &$this->mailchimp;
		$lists = $this->mailchimp->get('lists');

		if ($m->getLastError()) {
			_sepmessage('ERROR:' . "Request : \n " . var_export($m->getLastResponse(), true));
		}

		foreach ($lists['lists'] as $list) {
			$function($m, $list);
		}
	}

	private function formembers(callable $function) {
		$this->forlists(function(MailAPI $m, $list) use($function) {

			$count = $list['stats']['member_count'];
			$id = $list['id'];


			$start = 1;
			while ($start < $count) {

				$members = $m->get("lists/$id/members", ['count' => '50', 'offset' => $start, 'status' => 'subscribed']);

				foreach ($members['members'] as $member) {
					//Callable HERE
					$function($m, $list, $member);
				}

				$start += 50;
			}
		});
	}

	function fetch() {
		$m = &$this->mailchimp;
		$lists = $this->mailchimp->get('lists');

		if ($m->getLastError()) {
			_sepmessage('ERROR:' . "Request : \n " . var_export($m->getLastResponse(), true));
		}
		$notcorrect = 0;
		$correct = 0;
		$notreadable = 0;
		foreach ($lists['lists'] as $list) {
			print_line("LIST: {$list['name']}");
			sep();
			$count = $list['stats']['member_count'];
			$id = $list['id'];


			$start = 1;
			while ($start < $count) {

				$members = $m->get("lists/$id/members", ['count' => '50', 'offset' => $start, 'status' => 'subscribed']);
				$dodgydates = 0;
				foreach ($members['members'] as $member) {


					usleep(1000);
					$update_fields = [];

					if (isset($member['merge_fields']['BIRTH']) || isset($member['merge_fields']['MERGE5'])) {

						unset($birth_tmstp);
						$filebirth = $member['merge_fields']['BIRTH'] ? $member['merge_fields']['BIRTH'] : $member['merge_fields']['MERGE5'];
						$birth_tmstp = strtotime($filebirth);

						if ($birth_tmstp) {
//							_sepmessage('Found programaticaly readable Birtday on '.date('d M Y', $birth_tmstp) . PHP_EOL. "For email {$member['email_address']}");
							//Determine dodge dates
							// Date with a word as a month is not dodgy
							$dodge = true;
							if (
									strpos(strtolower($filebirth), strtolower(date('F', $birth_tmstp))) ||
									strpos(strtolower($filebirth), strtolower(date('M', $birth_tmstp)))
							) {
//										_sepmessage('Textual Date Found on '.date('d M Y', $birth_tmstp) . PHP_EOL. "For email {$member['email_address']} == $filebirth");
								$dodge = false;
							}
							// Dates with days more then 12 and is not dodgy
							if (( date('j', $birth_tmstp) > 12 ) && (date('j', $birth_tmstp) != date('n', $birth_tmstp))) {
								$dodge = false;
							}

							if ($dodge) {
								_sepmessage($filebirth . " is a unacceptable date - " . date('d M Y', $birth_tmstp));
								$notcorrect++;
							} else {
								$correct++;
							}

							$mdbirth = date('m/d', strtotime($filebirth));
							$update_fields['BIRTHDAY'] = $mdbirth;
						} else {
							$notreadable++;
						}
					}

					$hash = $m->subscriberHash($member['email_address']);

					if (isset($update_fields)) {
						$result = $m->patch("lists/{$list['id']}/members/{$hash}", [
							'merge_fields' => $update_fields,
						]);

						if ($m->getLastError()) {
							print "LIST: $list_bean->name: Update Failed with message: {$m->getLastError()} \n";
							print "Request : \n " . var_export($m->getLastResponse(), true) . " \n";
						}
					}
				}


				$start += 50;
			}
		}
		_sepmessage("$notcorrect incorrect birtdays found" .
				PHP_EOL . "$correct correct birthdays found"
				. PHP_EOL . "$notreadable birthdays is not programmatically readable");
	}

	function backup() {

		$this->forlists(function(MailAPI $m, $list) {
			$backupfield = [
				"tag" => "BDAYBACKUP",
				"required" => false, // or true to set is as required 
				"name" => "Birthday Backup Field",
				"type" => "text", // text, number, address, phone, email, date, url, imageurl, radio, dropdown, checkboxes, birthday, zip
				"default_value" => "", // anything
				"public" => false, // or false to set it as not 
				"help_text" => "This is a field to backup old birtday data",
				"display_order" => 22,
			];

			print_line("Runs for {$list['name']}");
			$this->register_field($list['id'], $backupfield);
		});

		$this->formembers(function($m, $list, $member) {
			$update_fields = [];

			if (isset($member['merge_fields']['BIRTH']) || isset($member['merge_fields']['MERGE5'])) {

				unset($birth_tmstp);
				$filebirth = $member['merge_fields']['BIRTH'] ? $member['merge_fields']['BIRTH'] : $member['merge_fields']['MERGE5'];
				$birth_tmstp = strtotime($filebirth);

				if ($birth_tmstp) {
//							_sepmessage('Found programaticaly readable Birtday on '.date('d M Y', $birth_tmstp) . PHP_EOL. "For email {$member['email_address']}");
					//Determine dodge dates
					// Date with a word as a month is not dodgy
					$dodge = true;
					if (
							strpos(strtolower($filebirth), strtolower(date('F', $birth_tmstp))) ||
							strpos(strtolower($filebirth), strtolower(date('M', $birth_tmstp)))
					) {
//										_sepmessage('Textual Date Found on '.date('d M Y', $birth_tmstp) . PHP_EOL. "For email {$member['email_address']} == $filebirth");
						$dodge = false;
					}
					// Dates with days more then 12 and is not dodgy
					if (( date('j', $birth_tmstp) > 12 ) && (date('j', $birth_tmstp) != date('n', $birth_tmstp))) {
						$dodge = false;
					}

					if ($dodge) {
						_sepmessage($filebirth . " is a unacceptable date - " . date('d M Y', $birth_tmstp));
						$update_fields['BIRTHDAY'] = "";
						$update_fields['BIRTH'] = "_";
						$notcorrect++;
					} else {
						$correct++;
						$mdbirth = date('m/d', $birth_tmstp);
						$reformat = date('d/m/Y', $birth_tmstp);

						$update_fields['BIRTHDAY'] = $mdbirth;
						$update_fields['BIRTH'] = $reformat;


						print_line("Reformating to $reformat");
					}


//					
				} else {
					$notreadable++;
					$update_fields['BIRTHDAY'] = "";
					$update_fields['BIRTH'] = "_";
				}
				$update_fields['BDAYBACKUP'] = $filebirth;

				$hash = $m->subscriberHash($member['email_address']);

				if (isset($update_fields) && !empty($update_fields)) {
					_sepmessage("Making backup of {$member['email_address']} saved birthday");
					$result = $m->patch("lists/{$list['id']}/members/{$hash}", [
						'merge_fields' => $update_fields,
					]);

					if ($m->getLastError()) {
						print "LIST: $list_bean->name: Update Failed with message: {$m->getLastError()} \n";
						print "Request : \n " . var_export($m->getLastResponse(), true) . " \n";
					}
				}
			}
		});
	}

	private function register_field($list_id, $field) {
		$m = &$this->mailchimp;

		$result = $m->get("lists/$list_id/merge-fields?count=50");

		foreach ($result['merge_fields'] as $field_t) {
			if ($field_t['name'] == $field['name']) {
				$exists = true;
				break;
			}
//			print_r($field_t);
//			print_line($field_t['tag']);
		}

		if (!isset($exists)) { // Create field
			$result = $m->post("lists/$list_id/merge-fields", $field);
			if ($m->getLastError()) {
				echo $m->getLastError();
				var_dump($m->getLastResponse());
			}
		}
	}

}
