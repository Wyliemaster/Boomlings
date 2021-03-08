<?php
include "incl/connection.php";
include "incl/mainLib.php";

if (!isset($_POST['udid']) || !isset($_POST['refID']))
	exit(-1);

$referrerID = $db->prepare("SELECT * FROM user WHERE referral_code = :refID");
$referrerID->execute([':refID' => $_POST['refID']]);
$referrerID = $referrerID->fetchAll();

if (empty($referrerID[0][1])) {
	exit("kE02");
}

if ($referrerID[0][1] == $_POST['udid']) {
	exit("kE03");
}

$refereeID = MainLib::getUserIDOrDie($db, $_POST['udid']);

if (empty($referrerID) || empty($refereeID)) {
	exit("kE02");
}

$refID = $db->prepare("INSERT INTO referral (referrer_id, referee_id) VALUES (:referrerID, :refereeID) ON DUPLICATE KEY UPDATE id = id");
$refID->execute(['referrerID' => $referrerID[0][0], 'refereeID' => $refereeID]);

echo 1;
