<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
require_role('user');

$id=(int)$_SESSION['user']['id_user'];
$title='Profil Saya';
$s=$pdo->prepare("SELECT * FROM users WHERE id_user=?");$s->execute([$id]);$data=$s->fetch();
$error='';
$success='';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $nama=trim($_POST['nama']??'');$username=trim($_POST['username']??'');$no_telp=trim($_POST['no_telp']??'');$alamat=trim($_POST['alamat']??'');$password=$_POST['password']??'';
    if($nama===''||$username==='')$error='Nama dan username wajib diisi.';
    else{
        try{
            if($password!==''){
                $s=$pdo->prepare("UPDATE users SET nama=?,username=?,password=?,no_telp=?,alamat=? WHERE id_user=?");
                $s->execute([$nama,$username,password_hash($password,PASSWORD_DEFAULT),$no_telp,$alamat,$id]);
            }else{
                $s=$pdo->prepare("UPDATE users SET nama=?,username=?,no_telp=?,alamat=? WHERE id_user=?");
                $s->execute([$nama,$username,$no_telp,$alamat,$id]);
            }
            $_SESSION['user']['nama']=$nama;$_SESSION['user']['username']=$username;
            $data['nama']=$nama;$data['username']=$username;$data['no_telp']=$no_telp;$data['alamat']=$alamat;
            $success='Profil berhasil diperbarui.';
        }catch(PDOException $e){$error=$e->getCode()==='23000'?'Username sudah digunakan.':'Gagal memperbarui profil.';}
    }
}
include '../includes/header.php';?>
<div class="page-head"><h1>Profil Saya</h1></div><div class="card form-card">
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<?php if($success):?><div class="alert alert-success"><?=e($success)?></div><?php endif;?>
<form method="post">
<label>Nama</label><input name="nama" value="<?=e($data['nama'])?>" required>
<label>Username</label><input name="username" value="<?=e($data['username'])?>" required>
<label>Password Baru <small>(kosongkan jika tidak diubah)</small></label><input type="password" name="password">
<label>No. Telepon</label><input name="no_telp" value="<?=e($data['no_telp'])?>">
<label>Alamat</label><textarea name="alamat"><?=e($data['alamat'])?></textarea>
<div class="form-actions"><button class="btn btn-primary">Simpan Perubahan</button></div>
</form></div>
<?php include '../includes/footer.php';?>
