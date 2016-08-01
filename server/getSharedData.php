<?php
require 'dbconfig.php';
$urlHash = null;
if(isset($_GET['shareID']) && ctype_alnum($_GET['shareID'])) {
	$urlHash = $_GET['shareID'];
} else {
	die(json_encode(array('error'=> 'no share id')));
}

$updateCountQ = 'UPDATE SharedData SET countView = countView + 1 WHERE url_hash = :urlHash';
$updateStmt = $db->prepare($updateCountQ);
$updateStmt->bindParam(':urlHash', $urlHash);
$updateStmt->execute();

$q = 'SELECT SharedData.*, Visualization.name as VizName, Layer.name as LayerName ' .
	'FROM SharedData, Visualization, Layer '.
	'WHERE url_hash= :urlHash AND SharedData.visualization_id=Visualization.visualization_id AND SharedData.layer_id=Layer.layer_id';
$stmt = $db->prepare($q);
$stmt->bindParam(':urlHash', $urlHash);

$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_OBJ);

echo json_encode($row);

?>