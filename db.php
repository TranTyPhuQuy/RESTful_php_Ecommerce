<?php
// Include config.php file
include_once 'config.php';

// Create a class Users
class Database extends Config
{
	// Fetch all or a single user from database
	public function getUser($userId = 0)
	{
		if ($userId != 0) {
			$sql = 'SELECT userId, username, email, roleId FROM users WHERE userId = :userId ';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(['cateId' => $userId]);
		} else {
			$sql = 'SELECT userId, username, email, roleId FROM users';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
		}
		$rows = $stmt->fetchAll();
		return $rows;
	}

	// register an user in the database
	public function insert($userName, $email, $password)
	{
		$sql = 'INSERT INTO users (userName, email, password) VALUES (:userName, :email, :password)';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['userName' => $userName, 'email' => $email, 'password' => $password]);
		return true;
	}

	// Update an user in the database
	public function update($userName, $email, $pass, $roleId, $id)
	{
		$sql = 'UPDATE users SET userName = :userName, email = :email, pass = :pass, roleId = :roleId WHERE id = :id';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['userName' => $userName, 'email' => $email, 'pass' => $pass, 'roleid' => $roleId, 'id' => $id]);
		return true;
	}

	// Delete an user from database
	public function delete($id)
	{
		$sql = 'DELETE FROM users WHERE id = :id';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['id' => $id]);
		return true;
	}

	// login an user in the database
	public function login($email, $password)
	{
		// Then, when verifying:
		$sql = 'SELECT userId, userName, email, roleId, password FROM users WHERE email = :email';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['email' => $email]);

		if ($stmt->rowCount() > 0) {
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			// Verify the password with the hash
			if (password_verify($password, $user['password'])) {
				// Password is valid, return the user data
				ob_clean(); // Thêm dòng này để xóa bộ đệm đầu ra
				return json_encode([
					'userId' =>  $user['userId'], 'userName' => $user['userName'],
					'email' => $user['email'], 'roleId' =>  $user['roleId']
				]);
			}
		}
		ob_clean(); // Thêm dòng này để xóa bộ đệm đầu ra
		// Invalid credentials
		return json_encode([
			'userId' =>  '', 'userName' => '',
			'email' => '', 'roleId' =>  ''
		]);
	}
	// insert categorys in the database
	public function addCategory($name, $imageData)
	{
		$sql = 'SELECT * FROM categories WHERE name = :name';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['name' => $name]);
		// $path = 'storage/images/' . $imageName;
		if ($stmt->rowCount() <= 0) {
			// Chuyển đổi chuỗi base64 thành file ảnh
			$data = explode(',', $imageData);
			$image_data = base64_decode($data[1]);
			// Đường dẫn đến thư mục lưu ảnh
			$target_dir = "../storage/images/";

			// Tạo đường dẫn lưu file với tên ảnh mới
			$imageName = time() . '.jpeg';
			$target_file = $target_dir . $imageName;
			$path = 'storage/images/' . $imageName;

			if (file_put_contents($target_file, $image_data)) {
				// //Lưu đường dẫn vào cơ sở dữ liệu
				$stmt1 = $this->conn->prepare("INSERT INTO categories (name, image) VALUES (:name, :image)");
				$stmt1->bindParam(':name', $name);
				$stmt1->bindParam(':image', $path);

				if ($stmt1->execute()) {
					$last_id = $this->conn->lastInsertId();
					echo json_encode(['success' => true, 'cateId' => $last_id, 'name' => $name, 'image' => $path]);
					return;
				}
			}
		}
		echo json_encode(['success' => false, 'cateId' => '', 'name' => '', 'image' => '']);
	}

	public function getCategoryById($cateId = 0)
	{
		if ($cateId != 0) {
			$sql = 'SELECT * FROM categories WHERE cateId = :cateId';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(['cateId' => $cateId]);
		} else {
			$sql = 'SELECT * FROM categories';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
		}

		$rows = $stmt->fetchAll();
		return $rows;
	}
	public function getProductFeatured()
	{
		$sql = 'SELECT * FROM products WHERE featured = 1';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll();

		return $rows;
	}
	public function getProductsById($productId = 0)
	{
		if ($productId != 0) {
			$sql = 'SELECT * FROM products WHERE productId = :productId';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(['productId' => $productId]);
		} else {
			$sql = 'SELECT * FROM products';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
		}

		$rows = $stmt->fetchAll();
		return $rows;
	}
	public function getProductsByCateId($cateId)
	{
		$sql = 'SELECT * FROM products WHERE cateId  = :cateId';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['cateId' => $cateId]);

		$rows = $stmt->fetchAll();
		return $rows;
	}
	public function addProduct($name, $price, $description, $cateId, $quantity, $image)
	{
		$data = explode(',', $image);
		$image_data = base64_decode($data[1]);

		$target_dir = "../storage/images/";

		$imageName = time() . '.jpeg';
		$target_file = $target_dir . $imageName;
		$path = 'storage/images/' . $imageName;
		if (file_put_contents($target_file, $image_data)) {
			$stmt = $this->conn->prepare("INSERT INTO products (name, price, description,cateId,quantity, image) VALUES (:name, :price , :description, :cateId, :quantity, :image)");
			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':price', $price);
			$stmt->bindParam(':description', $description);
			$stmt->bindParam(':cateId', $cateId);
			$stmt->bindParam(':quantity', $quantity);
			$stmt->bindParam(':image', $path);

			if ($stmt->execute()) {
				$last_id = $this->conn->lastInsertId();
				echo json_encode([
					'succedd' => true,'message' => 'Add Product Successfully!', 'productId' => $last_id, 'name' => $name,
					'price' => $price, 'description' => $description, 'cateId' => $cateId,
					'quantity' => $quantity, 'image' => $path
				]);
				return;
			}
		}
		echo json_encode([
			'succedd' => true,'message' => 'Failed To Add Product!', 'productId' => '', 'name' => '',
			'price' => '', 'description' => '', 'cateId' => '',
			'quantity' => '', 'image' => ''
		]);;
	}
	public function deleteCategoryByCateId($cateId)
	{
		$sql = 'SELECT * FROM categories WHERE cateId = :cateId';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['cateId' => $cateId]);
		if ($stmt->rowCount() > 0) {
			$sql1 = 'DELETE FROM categories WHERE cateId  = :cateId';
			$stmt1 = $this->conn->prepare($sql1);
			if ($stmt1->execute(['cateId' => $cateId])) {
				return true;
			}
		}
		return false;
	}
	public function deleteProductByProductId($productId)
	{
		$sql = 'SELECT * FROM products WHERE productId = :productId';
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['productId' => $productId]);
		if ($stmt->rowCount() > 0) {
			$sql1 = 'DELETE FROM products WHERE productId  = :productId';
			$stmt1 = $this->conn->prepare($sql1);
			if ($stmt1->execute(['productId' => $productId])) {
				return true;
			}
		}
		return false;
	}
	public function updateCategory($name, $cateId)
	{
		$sql = 'UPDATE categories SET name = :name WHERE cateId = :cateId';
		$stmt = $this->conn->prepare($sql);
		if ($stmt->execute(['name' => $name, 'cateId' => $cateId])) {
			return true;
		}
		return false;
	}
	public function updateProduct($productId, $name, $price, $description, $quantity)
	{
		$sql = 'UPDATE products SET name = :name, price = :price, description = :description, quantity = :quantity WHERE productId = :productId';
		$stmt = $this->conn->prepare($sql);
		if ($stmt->execute(['name' => $name, 'price' => $price, 'description' => $description, 'quantity' => $quantity, 'productId' => $productId])) {
			return true;
		}
		return false;
	}
}
