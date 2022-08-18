<?php

// Membuat variable
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "produk"; 

// mengecek koneksi database
$koneksi    = mysqli_connect($host, $user, $pass, $db);
// cek koneksi database, jika koneksi tidak ada maka akan masuk ke dalam if statement
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

// Membuat variable kosong yang akan di isi 
$nama       = '';
$kode      = '';
$tanggal_produksi      = '';
$tipe    = '';
$sukses     = '';
$error      = '';


// op akan digunakan untuk menangkap variable yang ada di dalam url 
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == "delete") {
    $id = $_GET['id'];
    $sql1 = "delete from produk where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = 'gagal melakukan hapus data';
    }
}


// Process Update Data
// Jika var op di url bernilai edit, maka tampilkan datanya
if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "select * from produk where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);

    $nama = $r1['nama'];
    if ($nama == '') {
        $error = 'Data Tidak Ditemukan!';
    } else {
        $kode = $r1['kode'];
        $tanggal_produksi = $r1['tanggal_produksi'];
        $tipe = $r1['tipe'];
    }
}

//* proses create data
// jika tombol sudah ditekan maka akan masuk kedalam if 
if (isset($_POST['simpan'])) {
    // Membuat variable yang di isi dari input yang memiliki atribut name di dalam form, dan ambil valuenya
    $nama       = $_POST['nama'];
    $kode      = $_POST['kode'];
    $tanggal_produksi      = $_POST['tanggal_produksi'];
    $tipe    = $_POST['tipe'];

    // Jika variable di bawah ini ada isinya, maka akan masuk kedalam if
    if ($nama && $kode && $tanggal_produksi && $tipe) {


        // Melakukan pengecekan dari tabel nama
        $q = mysqli_query($koneksi, "SELECT * FROM produk WHERE nama='$nama'");
        $cek = mysqli_num_rows($q);

        //* Process update data
        if ($op == 'edit') {
            $sql1   = "update produk set nama = '$nama', kode='$kode', tanggal_produksi='$tanggal_produksi', tipe='$tipe' where id = '$id'";
            $q1     = mysqli_query($koneksi, $sql1);

            if ($q1 && $cek == 0) {
                $sukses = "Data berhasil di update";
            } else {
                $error = "Data gagal di update";
            }
        }
        //* untuk Memasukkan data
        else {
            // Jika var cek isinya 0, maka akan bernilai true
            if ($cek == 0) {
                // Memasukkan data kedalam database menggunakan sql
                $sql1   = "insert into produk(nama, kode, tanggal_produksi, tipe) values ('$nama', '$kode', '$tanggal_produksi', '$tipe')";
                $q1 = mysqli_query($koneksi, $sql1);
                $sukses     = "Berhasil memasukkan data!";
            }
            // sebaliknya
            else {
                $error      = 'Gagal memasukkan data!';
            }
        }
    }

    // Jika input dalam form tidak di isi akan masuk kedalam else.
    else {
        $error      = 'silahkan masukan semua data!';
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk</title>

    <!-- My CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <style>
        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 10px;
        }

        .header {
            margin: 25px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- Untuk memasukkan data -->
        <h1 class="header">Data Barang</h1>
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                // Melalukan pengecekan, jika variable error ada isinya maka akan memunculkan alert danger
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:2;url=index.php"); /* 5 : detik */
                }
                ?>
                <?php
                // Jika variable sukses ada isinya maka akan memunculkan alert success. 
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:2;url=index.php");
                }
                ?>
                <form action="" method="post">
                    <!-- input nama produk -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                    </div>
                    <!-- input kode produksi -->
                    <div class="mb-3">
                        <label for="kode" class="form-label">kode</label>
                        <input type="kode" class="form-control" id="kode" name="kode" value="<?php echo $kode ?>">
                    </div>
                    <!-- input tanggal -->
                    <div class="mb-3">
                        <label for="tanggal_produksi" class="form-label">tanggal produksi</label>
                        <input type="date" class="form-control" id="tanggal_produksi" name="tanggal_produksi" value="<?php echo $tanggal_produksi ?>">
                    </div>
                    <!-- input tipe produk -->
                    <div class="mb-3">
                        <label for="tipe" class="form-label">tipe</label>
                        <input type="text" class="form-control" id="tipe" name="tipe" value="<?php echo $tipe ?>">
                    </div>
                    <button type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Produk
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">kode</th>
                            <th scope="col">tanggal produksi</th>
                            <th scope="col">tipe</th>
                        </tr>
                    <tbody>
                        <?php
                        //* Proses Read Data

                        $sql2   = "select * from produk order by id desc";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urutan = 1;

                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id         = $r2['id'];
                            $nama       = $r2['nama'];
                            $kode      = $r2['kode'];
                            $tanggal_produksi      = $r2['tanggal_produksi'];
                            $tipe    = $r2['tipe'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urutan++ ?></th>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $kode ?></td>
                                <td scope="row"><?php echo $tanggal_produksi ?></td>
                                <td scope="row"><?php echo $tipe ?></td>

                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('anda yakin?');"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>


</html>