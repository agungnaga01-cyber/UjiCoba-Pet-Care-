<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
$id=(int)($_GET['id']??0);
$s=$pdo->prepare("SELECT * FROM penitipan WHERE id_titip=?");$s->execute([$id]);$data=$s->fetch();if(!$data)exit('Data penitipan tidak ditemukan.');
$title='Edit Penitipan';$error='';
$statuses=['menunggu','disetujui','berlangsung','selesai','dibatalkan'];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $status=$_POST['status']??'';
    $catatan=trim($_POST['catatan']??'');
    if(!in_array($status,$statuses,true)){$error='Status tidak valid.';}
    else{
        try{
            $pdo->beginTransaction();
            $s=$pdo->prepare("UPDATE penitipan SET status=?, catatan=? WHERE id_titip=?");
            $s->execute([$status,$catatan,$id]);

            if($status==='berlangsung'){
                $s=$pdo->prepare("UPDATE kandang SET status='terisi' WHERE id_kandang=?");
                $s->execute([$data['id_kandang']]);
            } elseif(in_array($status,['selesai','dibatalkan'],true)){
                $s=$pdo->prepare("UPDATE kandang SET status='tersedia' WHERE id_kandang=?");
                $s->execute([$data['id_kandang']]);
            }

            $pdo->commit();
            header('Location:index.php');exit;
        }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error='Gagal memperbarui status.';}
    }
}
include '../../includes/header.php';?>
<div class="page-head"><h1>Edit Penitipan #<?=$data['id_titip']?></h1></div><div class="card form-card">
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post">
<label>Status</label><select name="status"><?php foreach($statuses as $st):?><option value="<?=$st?>" <?=$data['status']===$st?'selected':''?>><?=$st?></option><?php endforeach;?></select>
<label>Catatan</label><textarea name="catatan"><?=e($data['catatan'])?></textarea>
<div class="form-actions"><button class="btn btn-primary">Update</button><a class="btn btn-secondary" href="index.php">Batal</a></div>
</form></div>
<?php include '../../includes/footer.php';?>
