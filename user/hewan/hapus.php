<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('user');
if($_SERVER['REQUEST_METHOD']==='POST'){
$id=(int)($_POST['id']??0);$id_user=(int)$_SESSION['user']['id_user'];$s=$pdo->prepare("DELETE FROM hewan WHERE id_hewan=? AND id_user=?");$s->execute([$id,$id_user]);
}
header('Location:index.php');exit;
