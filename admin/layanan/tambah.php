<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');
$title='Tambah Layanan';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
$n=trim($_POST['nama_layanan']??'');$h=(float)($_POST['harga']??0);$d=trim($_POST['deskripsi']??'');$st=$_POST['status']??'aktif';
if($n===''||$h<0)$error='Nama layanan wajib diisi dan harga tidak boleh negatif.';
elseif(!in_array($st,['aktif','nonaktif'],true))$error='Status tidak valid.';
else{$s=$pdo->prepare("INSERT INTO layanan(nama_layanan,harga,deskripsi,status) VALUES(?,?,?,?)");$s->execute([$n,$h,$d,$st]);header('Location:index.php');exit;}
}
include '../../includes/header.php';?>
<div class="page-head"><h1>Tambah Layanan</h1></div><div class="card form-card"><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?><form method="post">
<label>Nama Layanan</label><input name="nama_layanan" required>
<label>Harga</label><input type="number" name="harga" min="0" step="0.01" required>
<label>Deskripsi</label><textarea name="deskripsi"></textarea>
<label>Status</label><select name="status"><option>aktif</option><option>nonaktif</option></select>
<div class="form-actions"><button class="btn btn-primary">Simpan</button><a class="btn btn-secondary" href="index.php">Batal</a></div></form></div>
<?php include '../../includes/footer.php';?>
