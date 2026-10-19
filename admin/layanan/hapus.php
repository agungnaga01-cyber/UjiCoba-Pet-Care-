<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
if($_SERVER['REQUEST_METHOD']==='POST'){ $id=(int)($_POST['id']??0); try{$s=$pdo->prepare("DELETE FROM layanan WHERE id_layanan=?");$s->execute([$id]);}catch(PDOException $e){} }
header('Location:index.php');exit;
