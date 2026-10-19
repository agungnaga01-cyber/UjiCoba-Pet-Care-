<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
$id=(int)($_GET['id']??0);$s=$pdo->prepare("SELECT * FROM layanan WHERE id_layanan=?");$s->execute([$id]);$data=$s->fetch();if(!$data)exit('Layanan tidak ditemukan.');
$title='Edit Layanan';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
$n=trim($_POST['nama_layanan']??'');$h=(float)($_POST['harga']??0);$d=trim($_POST['deskripsi']??'');$st=$_POST['status']??'aktif';
if($n===''||$h<0)$error='Nama layanan wajib diisi dan harga tidak boleh negatif.';
elseif(!in_array($st,['aktif','nonaktif'],true))$error='Status tidak valid.';
else{$s=$pdo->prepare("UPDATE layanan SET nama_layanan=?,harga=?,deskripsi=?,status=? WHERE id_layanan=?");$s->execute([$n,$h,$d,$st,$id]);header('Location:index.php');exit;}
}
include '../../includes/header.php';?>
<div class="page-head"><h1>Edit Layanan</h1></div><div class="card form-card"><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?><form method="post">
<label>Nama Layanan</label><input name="nama_layanan" value="<?=e($data['nama_layanan'])?>" required>
<label>Harga</label><input type="number" name="harga" min="0" step="0.01" value="<?=e((string)$data['harga'])?>" required>
<label>Deskripsi</label><textarea name="deskripsi"><?=e($data['deskripsi'])?></textarea>
<label>Status</label><select name="status"><?php foreach(['aktif','nonaktif'] as $st):?><option value="<?=$st?>" <?=$data['status']===$st?'selected':''?>><?=$st?></option><?php endforeach;?></select>
<div class="form-actions"><button class="btn btn-primary">Update</button><a class="btn btn-secondary" href="index.php">Batal</a></div></form></div>
<?php include '../../includes/footer.php';?>
