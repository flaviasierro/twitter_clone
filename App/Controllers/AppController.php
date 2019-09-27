<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

		public function timeline(){

			$this->validaAutenticacao();
	
			//RECUPERAÇÃO DOS TWEETS
			$tweet = Container::getModel('Tweet');

			$tweet->__set('id_usuario', $_SESSION['id']);

			$tweets = $tweet->getAll();

			
		
	
			$this->view->tweets = $tweets;	
			$this->render('timeline');	
			
	}
	
	public function tweet(){

		$this->validaAutenticacao();

		$this->render('timeline');	

		$tweet = Container::getModel('Tweet');//Instancia model Tweet

		$tweet->__set('tweet', $_POST['tweet']);
		$tweet->__set('id_usuario', $_SESSION['id']);

		$tweet->salvar();

		header('Location: /timeline');



}

	public function validaAutenticacao(){

		session_start();

		if(!isset($_SESSION['id']) ||  $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
		
			header('Location: /?login=erro');

		}
	}

	public function quemSeguir(){

		$this->validaAutenticacao();

		$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

	

		$usuarios = array();

		if($pesquisarPor != ''){
			$usuario = Container::getModel('Usuario');
			$usuario->__set('nome', $pesquisarPor);
			$usuario->__set('id', $_SESSION['id']);
			$usuarios = $usuario->getAll();

			
		}

		$this->view->usuarios = $usuarios;
		$this->render('quemSeguir');
	}

	public function acao(){

		$this->validaAutenticacao();
		

		//acao
		$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
		$id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);

			if($acao == 'seguir'){

				$usuario->seguirUsuario($id_usuario_seguindo);

			}else if($acao == 'deixarseguir'){

				$usuario->deixarseguirUsuario($id_usuario_seguindo);
			}

		header('Location: /quem_seguir');
	}


	public function remover(){
		
		$this->validaAutenticacao();
	
		$evento = isset($_GET['evento']) ? $_GET['evento'] : '';
		$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
		$usuario = Container::getModel('Tweet');
		$usuario->__set('id', $_SESSION['id']);

		if($evento == 'remover'){

			if($id_usuario == $_SESSION['id']){

				$usuario->remover($id_usuario);
				header('Location: /quem_seguir');	
			}

				
		}


	}
	
}

?>