<?php
class Database {
	// properti
	private $dbHost="localhost";
	private $dbUser="root";
	private $dbPass="root";
	private $dbName="admin_oop";
	
	// method koneksi mysql
	function connectMySQL() {
		mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
		mysql_select_db($this->dbName) or die ("Database Tidak Ditemukan di Server"); 
	}
	// method today
	function tanggal_sekarang(){
		$tgl_sekarang = date("Ymd");
		return $tgl_sekarang;
	}

	function ymd_to_dmy($str) {
    if(str_replace(array("-", "/"), "", $str) == "")
        return "";
    return substr($str, 8, 2)."-".substr($str, 5, 2)."-".substr($str, 0, 4);
}

function tgl_indo($tgl){
			$tanggal = substr($tgl,8,2);
			$bulan = $this->getBulan(substr($tgl,5,2));
			$tahun = substr($tgl,0,4);
			return $tanggal.' '.$bulan.' '.$tahun;		 
	}	

	function getBulan($bln){
				switch ($bln){
					case 1: 
						return "Jan";
						break;
					case 2:
						return "Feb";
						break;
					case 3:
						return "Mar";
						break;
					case 4:
						return "Apr";
						break;
					case 5:
						return "Mei";
						break;
					case 6:
						return "Jun";
						break;
					case 7:
						return "Jul";
						break;
					case 8:
						return "Ags";
						break;
					case 9:
						return "Sep";
						break;
					case 10:
						return "Okt";
						break;
					case 11:
						return "Nov";
						break;
					case 12:
						return "Des";
						break;
				}
			} 
}

class User {
// Proses Login
	function cek_login($username, $password) {
		$password = md5($password);
		$result = mysql_query("SELECT * FROM users WHERE username='$username' AND password='$password'");
		$user_data = mysql_fetch_array($result);
		$no_rows = mysql_num_rows($result);
		if ($no_rows == 1) {
			$_SESSION['login'] = TRUE;
			$_SESSION['id'] = $user_data['id_users'];
			return TRUE;
		}
		else {
		  return FALSE;
		}
	}
	
	// Ambil Sesi 
	function get_sesi() {
		return $_SESSION['login'];
	}


	// Logout 
	function user_logout() {
		$_SESSION['login'] = FALSE;
		session_destroy();
	}

	// ambil nama
	function ambilNama($id)
	{
		$query = mysql_query("SELECT * FROM users WHERE id_users='$id'");
		$row = mysql_fetch_array($query);
		echo $row['nama_users'];
	}

	// tampilkan data dari tabel users 
	function tampil_data(){
		$data=mysql_query("SELECT * FROM users");
		while($d=mysql_fetch_array($data)){
			$result[]=$d;
		}
		return $result;
	}

	// proses input data user
	function input_user($nama_users,$username,$pwd,$level){
		mysql_query("INSERT INTO users (nama_users,username,password,level) VALUES ('$nama_users','$username','$pwd','$level')");
	}

	// tampilkan data dari tabel users yang akan di edit 
	function edit_user($id){
		$data=mysql_query("SELECT * FROM users WHERE id_users='$id'");
		while($x=mysql_fetch_array($data)){
			$hasil[]=$x;
		}
		return $hasil;
	}

	// proses update data user
	function update_user($id,$nama_users,$username,$pwd,$level){
		mysql_query("UPDATE users SET nama_users='$nama_users',username='$username',password='$pwd',level='$level' WHERE id_users='$id'");
	}

	// proses delete data user
	function hapus_user($id){
		mysql_query("DELETE FROM users where id_users='$id'");
	}
}

class berita {

	// tampilkan data dari tabel berita
	function tampil_data(){
		$data=mysql_query("SELECT * FROM berita");
		while($d=mysql_fetch_array($data)){
			$hasil[]=$d;
		}
		return $hasil;
	}

	// proses input data berita
	function input_berita($judul_berita,$isi_berita,$foto,$tanggal,$publish){
	mysql_query("INSERT INTO berita (judul_berita,isi_berita,foto,tanggal,publish) VALUES ('$judul_berita','$isi_berita','$foto','$tanggal','$publish')");
	}

	// tampilkan data dari tabel berita yang akan di edit 
	function edit_berita($id){
	$data=mysql_query("SELECT * FROM berita WHERE id_berita='$id'");
	while($q=mysql_fetch_array($data)){
	$hasil[]=$q;
	}
	return $hasil;
	}

	// proses update data berita
	function update_berita($id,$judul_berita,$isi_berita,$foto,$publish){
	mysql_query("UPDATE berita SET judul_berita='$judul_berita',isi_berita='$isi_berita',foto='$foto',publish='$publish' WHERE id_berita='$id'");
	}

	// proses update data berita jika foto tidak diubah
	function update_berita_noupdatephoto($id,$judul_berita,$isi_berita,$publish){
	mysql_query("UPDATE berita SET judul_berita='$judul_berita',isi_berita='$isi_berita',publish='$publish' WHERE id_berita='$id'");
	}

	// proses delete data berita
	function delete_berita($id){
	mysql_query("DELETE FROM berita WHERE id_berita='$id'");
	}

	function select_foto_to_trash($id){
	$data_gambar = mysql_query("SELECT foto FROM berita WHERE id_berita='$id'");
	$r    	= mysql_fetch_array($data_gambar);
	@unlink('../foto_berita/'.$r['foto']);
	@unlink('../foto_berita/'.'small_'.$r['foto']);
	}

	function UploadProduk($fupload_name){
  //direktori gambar
  $vdir_upload = "../foto_berita/";
  $vfile_upload = $vdir_upload . $fupload_name;
  $tipe_file   = $_FILES['fupload']['type'];

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);

  //identitas file asli  
  if ($tipe_file=="image/jpeg" ){
      $im_src = imagecreatefromjpeg($vfile_upload);
      }elseif ($tipe_file=="image/png" ){
      $im_src = imagecreatefrompng($vfile_upload);
      }elseif ($tipe_file=="image/gif" ){
      $im_src = imagecreatefromgif($vfile_upload);
      }elseif ($tipe_file=="image/wbmp" ){
      $im_src = imagecreatefromwbmp($vfile_upload);
    }
  $src_width = imageSX($im_src);
  $src_height = imageSY($im_src);

  //Simpan dalam versi small 110 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width = 110;
  $dst_height = ($dst_width/$src_width)*$src_height;

  //proses perubahan ukuran
  $im = imagecreatetruecolor($dst_width,$dst_height);
  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

  //Simpan gambar
  if ($_FILES["fupload"]["type"]=="image/jpeg" ){
      imagejpeg($im,$vdir_upload . "small_" . $fupload_name);
      }
	  elseif ($_FILES["fupload"]["type"]=="image/png" ){
      imagepng($im,$vdir_upload . "small_" . $fupload_name);
      }
	  elseif ($_FILES["fupload"]["type"]=="image/gif" ){
      imagegif($im,$vdir_upload . "small_" . $fupload_name);
      }
	  elseif($_FILES["fupload"]["type"]=="image/wbmp" ){
      imagewbmp($im,$vdir_upload . "small_" . $fupload_name);
      }
  
  //Hapus gambar di memori komputer
  imagedestroy($im_src);
  imagedestroy($im);
}

// Methode Paging

}	
