<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
if($_SERVER['REQUEST_METHOD']==='POST'){ $id=(int)($_POST['id']??0); $s=$pdo->prepare("DELETE FROM pembayaran WHERE id_bayar=?");$s->execute([$id]); }
header('Location:index.php');exit;
