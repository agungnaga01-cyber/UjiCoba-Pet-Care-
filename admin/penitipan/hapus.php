<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
if($_SERVER['REQUEST_METHOD']==='POST'){
    $id=(int)($_POST['id']??0);
    try{
        $s=$pdo->prepare("SELECT id_kandang FROM penitipan WHERE id_titip=?");
        $s->execute([$id]);$data=$s->fetch();
        $pdo->beginTransaction();
        if($data){
            $s=$pdo->prepare("UPDATE kandang SET status='tersedia' WHERE id_kandang=?");
            $s->execute([$data['id_kandang']]);
        }
        $s=$pdo->prepare("DELETE FROM penitipan WHERE id_titip=?");
        $s->execute([$id]);
        $pdo->commit();
    }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();}
}
header('Location:index.php');exit;
