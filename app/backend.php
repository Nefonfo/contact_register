<?php
header('Content-Type: application/json');
require_once('./database.php');

use Web\App\Database;

Class User {
	static private $name_regex = '/^[A-Za-z ]{2,80}$/m';
	static private $phone_regex = '/^[0-9]{8,12}$/m';
	static private $birthday_regex = '/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/';
	private $id;
	private $name;
	private $birthday;
	private $phone;

	function __construct($id, $name, $birthday, $phone) {
		$validation = $this -> validate($name, $birthday, $phone);
		if($validation === '') {
			$this -> id = $id;
			$this -> name = $name;
			$this -> birthday = $birthday;
			$this -> phone = $phone;
		} else {
			throw new Exception($validation);
		}
	}

	private function validate($name, $birthday, $phone) {
		$result = '';
		if(!preg_match(self::$name_regex, $name)) {
			$result = 'El nombre debe contener solo letras de 2-80 caracteres: '.$name;
		} else if(!preg_match(self::$phone_regex, $phone)) {
			$result = 'El teléfono debe ser numérico de 8-12 elementos';
		} else if(!preg_match(self::$birthday_regex, $birthday)) {
			$result = 'La fecha es invalida';
		}

		return $result;
	}

	public function create(): string {
		$id = NULL;
		$db = Database\Database::get_instance();
		$cnx = $db -> get_connection();
		$new_date = date('Y-m-d', strtotime($this -> birthday));
		$psmt = $cnx -> prepare("INSERT INTO Agenda (id, name, birthday, phone) VALUES (?, ?, ?, ?)");
		$psmt -> bindParam(1, $id);
		$psmt -> bindParam(2, $this -> name);
		$psmt -> bindParam(3, $new_date);
		$psmt -> bindParam(4, $this -> phone);

		$change = $psmt -> execute();
		if($change) {
			$result = 'Agregado Correctamente';
		} else {
			
			throw new Exception('Error al Agregar');
			
		}
		$cnx = NULL;
		return $result;
	}

	public function update(): string {
		if(is_null($this -> id)) throw new Exception('El id no ha sido proporcionado para actualizar');
		$db = Database\Database::get_instance();
		$new_date = date('Y-m-d', strtotime($this -> birthday));
		$cnx = $db -> get_connection();
		$psmt = $cnx -> prepare('UPDATE Agenda SET name = :name, birthday = :birthday, phone = :phone WHERE id = :id');
		$psmt -> bindParam(':name', $this -> name, PDO::PARAM_STR);
		$psmt -> bindParam(':birthday', $new_date, PDO::PARAM_STR);
		$psmt -> bindParam(':phone', $this -> phone, PDO::PARAM_STR);
		$psmt -> bindParam(':id', $this -> id, PDO::PARAM_INT);
		$change = $psmt -> execute();
		if($change) {
			$result = 'Actualizado Correctamente';
		} else {
			throw new Exception('Error al Actualizar');
		}
		$cnx = NULL;
		return $result;
	}

	static function delete($id): string {
		$db = Database\Database::get_instance();
		$cnx = $db -> get_connection();
		$psmt = $cnx->prepare('DELETE FROM Agenda WHERE id = :id');
		$change = $psmt -> execute(array(
			':id' => $id
		));
		if($change) {
			$result = 'Eliminado Correctamente';
		} else {
			throw new Exception('Error al Eliminar');
		}
		$cnx = NULL;
		return $result;
	}

}

Class Post {

	function __construct() {
		$message_response = NULL;
		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
			try {
				switch($_POST['type']) {
					case 'add':
						if(isset($_POST['name']) && isset($_POST['birthday']) && $_POST['phone']) {
							$user = new User(NULL, $_POST['name'], $_POST['birthday'], $_POST['phone']);
							$message_response = $user -> create();
						} else {
							throw new Exception('Error: faltan valores para agregar');
						}
						break;
					case 'update':
						if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['birthday']) && $_POST['phone']) {
							$user = new User($_POST['id'], $_POST['name'], $_POST['birthday'], $_POST['phone']);
							$message_response = $user -> update();
						} else {
							throw new Exception('Error: faltan valores para actualizar');
						}
						break;
					case 'delete':
						if(isset($_POST['id'])) {
							$message_response = User::delete($_POST['id']);
						} else {
							throw new Exception('Error: faltan id para eliminar');
						}
						break;
					default:
						die();
				}
				http_response_code(200);
				echo json_encode(array(
					'status' => 200,
					'message' => $message_response
				));
			} catch(Exception $e) {
				$message_response = $e->getMessage();
				http_response_code(300);
				echo json_encode(array(
					'status' => 300,
					'message' => $message_response
				));
			}
			die();
		}
	}
}


new Post();