<?php
    $conn = mysqli_connect("localhost", "u104419741_icu", "Srsssm5cd@" , "u104419741_icu") or die (mysqli_error());

	class usr{}
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	if ((empty($email)) || (empty($password))) { 
        $response = new usr();
	 	$response->success = 0;
	 	$response->message = "Kolom tidak boleh kosong"; 
	 	die(json_encode($response));
	}
	
	$query = mysqli_query($conn, "SELECT * FROM pengguna WHERE email='$email' AND password='$password' ");
	
	$row = mysqli_fetch_array($query);
	
	if (!empty($row)){
        $response = new usr();
	 	$response->success = 1;
	 	$response->message = "Login berhasil!";
	 	$response->id = $row['user_id'];
	 	$response->name = $row['nama_lengkap'];
	 	$response->email = $row['email'];
	 	$response->password = $row['password'];
	 	die(json_encode($response));
	 } else { 
        $response = new usr();
	 	$response->success = 0;
	 	$response->message = "Username atau password salah, silahkan cek kembali";
	 	die(json_encode($response));
	}
	
	mysqli_close($conn);
?>