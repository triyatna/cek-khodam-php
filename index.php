<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Khodam</title>
    <link rel="shortcut icon" href="https://rakyatbengkulu.disway.id/upload/12200177249868c87d89289e2f7e8735.jpeg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/styles.css">
    <meta name="description" content="Cek Khodam Anda, Biar Tenang">
    <meta name="keywords" content="Cek Khodam, Khodam, Cek Khodam Anda">
    <meta name="author" content="TY Studio DEV">
    <meta property="og:title" content="Cek Khodam Anda">
    <meta property="og:description" content="Cek Khodam Anda, Biar Tenang">
    <meta property="og:image" content="https://rakyatbengkulu.disway.id/upload/12200177249868c87d89289e2f7e8735.jpeg">
    <meta property="og:url" content="https://mykhodam.tyonly.com/">
    <meta property="og:site_name" content="Cek Khodam Anda">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:locale:alternate" content="en_US">
</head>

<body>
    <div class="container text-center mt-5">

        <div class="card bg-glass card-center">
            <div class="card-body">
                <h1 class="card-title">Cek Khodam Anda</h1>
                <div class="mt-5">
                    <div class="mb-3">
                        <label for="nama" class="form-label" required>Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label" required>Tanggal Lahir</label>
                        <input type="date" class="form-control" pattern="dd-mm-yyyy" id="tanggal" name="tanggal" max="<?php echo date("Y-m-d"); ?>">
                    </div>
                    <button type="submit" onclick="checkKhodam()" id="cekkhodam" class="btn btn-primary mb-3">Cek Khodam</button>
                    <button type="submit" class="btn btn-primary mb-3" disabled="true" id="loading" hidden>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Sedang mengecek...
                    </button>
                </div>
                <script>
                    function checkKhodam() {
                        var nama = document.getElementById('nama').value;
                        var tanggal = document.getElementById('tanggal').value;


                        if (!nama || !tanggal) {
                            alert('Nama dan Tanggal Lahir harus diisi');
                            return;
                        }
                        if (!/^[a-zA-Z\s]*$/.test(nama)) {
                            alert('Masukan nama kamu dengan benar :)');
                            return;
                        }
                        // ajax
                        $.ajax({
                            url: 'data/request.php',
                            type: 'POST',
                            data: {
                                nama: nama,
                                tanggal: tanggal
                            },
                            beforeSend: function() {
                                document.getElementById('cekkhodam').hidden = true;
                                document.getElementById('loading').hidden = false;
                                $('.result').html(` <div class="spinner-border text-warning mt-4 mb-4" role="status"> Ty
                                <span class = "visually-hidden" > Loading... < /span> </div>`);
                            },
                            success: function(response) {
                                setTimeout(function() {
                                    document.getElementById('cekkhodam').hidden = false;
                                    document.getElementById('loading').hidden = true;
                                    kodam = response.name;
                                    deskripsi = response.desc
                                    nama = response.nama;
                                    weton = response.weton.pasaran;

                                    if (response.id == 0 || response.id >= 105) {
                                        $('.result').html(`
                                     <div class="card mt-4 mb-3 bg-paper">
                                         <div class="card-body">
                                        <h3>${kodam}</h3>
                                        <p><b>${nama} tidak punya khodam pendamping</p>
                                        <p>${deskripsi}</p>
                                        <p>Weton: ${weton}</p>
                                         </div>
                                     </div>
                                    `);
                                    } else {
                                        $('.result').html(`
                                     <div class="card mt-4 mb-3 bg-paper">
                                         <div class="card-body">
                                        <h3>Khodam ${kodam}</h3>
                                        <p><b>${nama} punya khodam <u>${kodam}</u></b></p>
                                        <p>Karakteristik Khodam yang dimiliki ${nama} yaitu ${deskripsi}</p>
                                        <p>Weton: ${weton}</p>
                                         </div>
                                     </div>
                                    `);
                                    }
                                }, 4000);
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseText);
                                document.getElementById('cekkhodam').hidden = false;
                                document.getElementById('loading').hidden = true;
                                alert('Terjadi kesalahan, silahkan coba lagi');
                            }
                        });
                    }
                </script>
            </div>
        </div>
        <div class="result"></div>

        <footer>
            Copyright <span id="currentYear"></span> &copy; TY Studio DEV - Web Games.
        </footer>

    </div>

    <script>
        // Menampilkan tahun saat ini secara otomatis
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
</body>
<script src=" https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</html>