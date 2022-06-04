<?php
// require_once 'config.php';

// if ($conn) {
$id_user = $_POST['id_user'];
$id_kabupaten = $_POST['kode_pos'];
$asal = '68124';
$kurir = 'jne';
$totalberat = 1000;

// // $querycart = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM keranjang WHERE id_user = '$id_user'")) ;
// $querycart = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_user = '$id_user'") ;

// // foreach($querycart as $data) {
// //   $idb = $data['id_barang'];
// //   $getBerat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM data_brg WHERE id_brg = '$id_brg'"));
// //   $berat += $getBerat['berat'];
// // }

// while ($data_brg = mysqli_fetch_assoc($querycart)) {
//   $idb = $data_brg['id_barang'];
//   $qty = $data_brg['qty'];
//   $getweight = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM data_brg WHERE id_brg = '$idb'"));
//   $weight = $getweight['berat'];
//   $berat = $weight * $qty;
//   $totalberat += $berat;
// }


$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://api.rajaongkir.com/starter/cost",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "origin=".$asal."&destination=".$id_kabupaten."&weight=".$totalberat."&courier=".$kurir."",
//   CURLOPT_POSTFIELDS => "origin=68124&destination=61473&weight=400&courier=jne",
  CURLOPT_HTTPHEADER => array(
    "content-type: application/x-www-form-urlencoded",
    "key: 6408a508912b42bbccac2a0424c249cd"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    exit("cURL Error #:" . $err);
    $new_response = (array('status' => 'FAILED'));
}

$responses = json_decode($response, true);
$cost = $responses['rajaongkir']['results'][0]['costs'][1]['cost'][0]['value'];
$est = $responses['rajaongkir']['results'][0]['costs'][1]['cost'][0]['etd'];
// echo $response;
$new_response = array('status' => 'SUCCESS', 'cost' => $cost, 'estimasi' => $est);
// var_dump($new_response);
// } else {
//   $new_response = (array('status' => 'NOT CONNECTED'));
// }
echo json_encode($new_response);
?>