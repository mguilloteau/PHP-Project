<?php

require('controller/controller.php');


try {
	if (isset($_GET['action'])) {

		if ($_GET['action'] == 'indexView') {
					
			homePost();
					
		} elseif ($_GET['action'] == 'addLogin') { 

			$db = new PDO('mysql:host=localhost;dbname=project;charset=utf8', 'root', 'root');
			
			$pseudo = $_POST['my-pseudo'];
			$mail = $_POST['my-mail'];
			$pass = $_POST['my-password'];
			$pass2 = $_POST['my-password2'];

			$reqpseudo = $db->prepare("SELECT * FROM account WHERE pseudo = ?");
			$reqpseudo->execute(array($pseudo));
			$verif = $reqpseudo->rowCount();
			
			if (empty($pseudo) || empty($mail) || empty($pass)) {

				throw new Exception('<p>Veuillez remplir tous les champs ! Revenir à l\'inscription : <a href="index.php?action=createLogin">ICI</a></p>');
			
			} elseif (strlen($pseudo >= 13) || !preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $mail) || strlen($pass >= 17)) {
			
				throw new Exception('<p>Votre pseudo, ou mot de passe sont trop longs et/ou ne sont pas au bon format ! Rententez et revenez à l\'inscription : <a href="index.php?action=createLogin">ICI</a></p>');
			
			} elseif ($verif == 1)  {
				
				throw new Exception('<p>Le pseudo existe déjà ! Revenir à l\'inscription : <a href="index.php?action=createLogin">ICI</a></p>');
			
			} elseif ($pass != $pass2) {  
				
				throw new Exception('<p>Les mots de passe ne correspondent pas ! Rententez ! Revenir à l\'inscription : <a href="index.php?action=createLogin">ICI</a></p>');
			
			} else {

				addLogin($pseudo, $mail, $pass);
			}

		} elseif ($_GET['action'] == 'postView') {
					
			getPostView();
					
		} elseif ($_GET['action'] == 'fullPost') {
					
					if (isset($_GET['id']) && $_GET['id'] > 0) {
							
			fullPost();

						} else {
							
							throw new Exception('Ce billet n\'existe pas ! Revenez à la page d\'accueil : <a href="index.php">ICI</a> ');
							
					}
			} elseif ($_GET['action'] == 'editPost') {

			if(isset($_GET['id']) && $_GET['id'] > 0) {

				updatePost($_GET['id'], $_POST['elem1']);

			} else {

				throw new Exception('Vous ne pouvez pas éditer un billet qui n\'existe pas ! Revenez à la page d\'accueil : <a href="index.php">ICI</a>');
			}

		} elseif ($_GET['action'] == 'addPost') {

			addPost($_POST['elem1']);

		} elseif ($_GET['action'] == 'writeComments') { 

			writeComments();

		} elseif ($_GET['action'] == 'writePost') {
					
			writeView();
					
		} elseif ($_GET['action'] == 'createLogin') {
					
			formLogin();
			
		} elseif ($_GET['action'] == 'backoffice') {
			
			backoffice();

		} elseif ($_GET['action'] == 'disconnect') {

			disconnect();

		} elseif ($_GET['action'] == 'deletePost') {

			if (isset($_GET['id']) && $_GET['id'] > 0) {

			deletePost($_GET['id']);

			}

		} else { 

			throw new Exception('Erreur : La page que vous avez demandé n\'existe pas ! Revenez à la page d\'accueil : <a href="index.php">ICI</a>');

		}
	} else {
			
		homePost();

	}
} catch (Exception $e) {

	$errorMessage = $e->getMessage();
	require('views/error.php');

}

?>