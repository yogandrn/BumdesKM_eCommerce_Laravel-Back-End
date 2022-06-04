<?php
$conn = mysqli_connect('localhost', 'root', '', 'new_bumdes');
if ($conn) {
  
        $namefile = date('Ymd');
        $namefile .= "_".uniqid();
        $id_transaction = intval ($_POST['id_transaction']);
        $status = $_POST['status'];
        $payment = $_POST['payment'];

        $realImage = base64_decode($payment);

        file_put_contents('../storage/app/public/assets/payment/'.$namafile, $realImage);

        $query = mysqli_query($conn, "INSERT INTO payments (payment, status) VALUES ($payment, $status )");
        if ( mysqli_affected_rows($conn) > 0) {
            mysqli_query($conn, "UPDATE transaction_outs SET status = 'Dibayar' WHERE id = '$id_transaction'");
            $response = array(['message' => 'SUCCESS']);
        }
    
} else {
    $response = array(['message' => 'NOT CONNECTED']); 
}
echo json_encode($response);