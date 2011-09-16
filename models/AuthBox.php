<?php
class Model_AuthBox extends Model_Abstract {
	function printBox() {
		$auth = new Model_Auth;
		$auth = $auth->getUserDetails();
		
		$userTypes = array(
			'U' => 'Utilizator',
			'M'	=> 'Manager',
			'A'	=> 'Administrator'
		);
		if (!$auth->type): ?>
			<a class="buton" href="/auth/login">Logare</a> | <a href="/auth/register" class="buton">Creare cont nou</a>
		<?php else: ?>
			<div class="authenticated">
				Bine ati venit, <em><?php echo $userTypes[$auth->type]; ?></em>.
					Sunteti logat(a) ca <b><?php echo $auth->email; ?></b>
					<a href="/auth/logout">Delogare</a>
			</div>
		<?php endif; 
	}
}