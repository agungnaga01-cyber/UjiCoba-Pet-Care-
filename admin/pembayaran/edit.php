<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
$id=(int)($_GET['id']??0);$s=$pdo->prepare("SELECT * FROM pembayaran WHERE id_bayar=?");$s->execute([$id]);$data=$s->fetch();if(!$data)exit('Pembayaran tidak ditemukan.');
$title='Edit Pembayaran';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $tanggal=$_POST['tanggal_bayar']??null;
    $jumlah=(float)($_POST['jumlah']??0);
    $metode=$_POST['metode']??'transfer';
    $status=$_POST['status']??'pending';
    if(!in_array($metode,['cash','transfer','qris'],true)||!in_array($status,['pending','lunas','gagal'],true)){$error='Pilihan pembayaran tidak valid.';}
    elseif($jumlah<0){$error='Jumlah tidak boleh negatif.';}
    else{
        $tanggal = $tanggal !== '' ? $tanggal : null;
        $s=$pdo->prepare("UPDATE pembayaran SET tanggal_bayar=?,jumlah=?,metode=?,status=? WHERE id_bayar=?");
        $s->execute([$tanggal,$jumlah,$metode,$status,$id]);
        header('Location:index.php');exit;
    }
}
include '../../includes/header.php';?>
<div class="page-head"><h1>Edit Pembayaran #<?=$data['id_bayar']?></h1></div><div class="card form-card"><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post">
<label>Tanggal Bayar</label><input type="date" name="tanggal_bayar" value="<?=e($data['tanggal_bayar'])?>">
<label>Jumlah</label><input type="number" name="jumlah" min="0" step="0.01" value="<?=e((string)$data['jumlah'])?>" required>
<label>Metode</label><select name="metode"><?php foreach(['cash','transfer','qris'] as $m):?><option value="<?=$m?>" <?=$data['metode']===$m?'selected':''?>><?=$m?></option><?php endforeach;?></select>
<label>Status</label><select name="status"><?php foreach(['pending','lunas','gagal'] as $st):?><option value="<?=$st?>" <?=$data['status']===$st?'selected':''?>><?=$st?></option><?php endforeach;?></select>
<div class="form-actions"><button class="btn btn-primary">Update</button><a class="btn btn-secondary" href="index.php">Batal</a></div>
</form></div>
<?php include '../../includes/footer.php';?>
