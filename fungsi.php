<?php
$koneksi = mysqli_connect("localhost", "root", "", "user07082");

if (!$koneksi) {
	die("Gagal melakukan koneksi ke MySQL: " . mysqli_connect_error());
}
