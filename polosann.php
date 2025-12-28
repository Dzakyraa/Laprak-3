<?php
// Reset ke form awal
if (isset($_POST['reset'])) {
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .card-header-custom { padding: 10px; text-align: center; color: white; border-radius: 5px 5px 0 0; }
        .form-label { font-weight: bold; }

        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 25px 30px; }
        .detail-kiri { flex: 1; }
        .info-text { margin-bottom: 10px; font-size: 1.1rem; }
        .label-bold { font-weight: 700; }
        .value-bold { font-weight: 600; }
    </style>
</head>
<body>
<div class="container mt-4 mb-5 px-5">
    <div class="card shadow-sm mb-4">
        <div class="card-header-custom bg-primary">
            <h1 class="h4 mb-0">Form Penilaian Mahasiswa</h1>
        </div>
        <div class="card-body">
            <form method="post">
                <?php
                // helper untuk value form
                function old($key) {
                    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : '';
                }
                ?>
                <div class="mb-3">
                    <label class="form-label">Masukkan Nama</label>
                    <input type="text" class="form-control" name="nama"
                           placeholder="Nama" value="<?= old('nama') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Masukkan NIM</label>
                    <input type="text" class="form-control" name="nim"
                           placeholder="NIM" value="<?= old('nim') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nilai Kehadiran (10%)</label>
                    <input type="number" class="form-control" name="kehadiran"
                           placeholder="Untuk Lulus minimal 70%" value="<?= old('kehadiran') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nilai Tugas (20%)</label>
                    <input type="number" class="form-control" name="tugas"
                           placeholder="0-100" value="<?= old('tugas') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nilai UTS (30%)</label>
                    <input type="number" class="form-control" name="uts"
                           placeholder="0-100" value="<?= old('uts') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nilai UAS (40%)</label>
                    <input type="number" class="form-control" name="uas"
                           placeholder="0-100" value="<?= old('uas') ?>">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="proses" class="btn btn-primary">Proses</button>
                </div>
            </form>

            <?php
            if (isset($_POST['proses'])) {
                $nama  = $_POST['nama'];
                $nim   = $_POST['nim'];
                $hadir = $_POST['kehadiran'];
                $tugas = $_POST['tugas'];
                $uts   = $_POST['uts'];
                $uas   = $_POST['uas'];

                // Validasi peringatan (logika sama)
                if (
                    empty($nama) && empty($nim) &&
                    $hadir === "" && $tugas === "" && $uts === "" && $uas === ""
                ) {
                    echo "<div class='mt-3 p-3 text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-3'>
                            Semua kolom harus diisi!
                          </div>";
                } elseif (empty($nama) || empty($nim)) {
                    echo "<div class='mt-3 p-3 text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-3'>
                            Kolom Nama dan NIM harus diisi
                          </div>";
                } elseif ($hadir === "" || $tugas === "" || $uts === "" || $uas === "") {
                    echo "<div class='mt-3 p-3 text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-3'>
                            Semua kolom harus diisi!
                          </div>";
                } else {
                    // Konversi ke numerik sekali saja
                    $hadir = (float)$hadir;
                    $tugas = (float)$tugas;
                    $uts   = (float)$uts;
                    $uas   = (float)$uas;

                    // Nilai akhir
                    $na = ($hadir * 0.1) + ($tugas * 0.2) + ($uts * 0.3) + ($uas * 0.4);

                    // Grade
                    if     ($na >= 85) $g = "A";
                    elseif ($na >= 70) $g = "B";
                    elseif ($na >= 55) $g = "C";
                    elseif ($na >= 40) $g = "D";
                    else               $g = "E";

                    // Kelulusan
                    $lulus = ($na >= 60 && $hadir > 70 && $tugas >= 40 && $uts >= 40 && $uas >= 40);
                    $color_hex = $lulus ? "#198754" : "#e35d5d";
                    $status    = $lulus ? "LULUS" : "TIDAK LULUS";
                    ?>
                    <div class="card shadow-sm mt-4">
                        <div class="card-header-custom text-start" style="background-color: <?= $color_hex ?>;">
                            <span class="mb-0">Hasil Penilaian</span>
                        </div>

                        <div class="card-body p-0">
                            <div class="detail-row" style="padding-top: 20px; padding-bottom: 10px;">
                                <div class="detail-kiri" style="text-align: center;">
                                    <div class="info-text" style="font-size: 1.5rem;">
                                        <span class="label-bold">Nama:</span>
                                        <span class="value-bold"><?= htmlspecialchars($nama) ?></span>
                                    </div>
                                </div>
                                <div class="detail-kiri" style="text-align: center; font-size: 1.5rem;">
                                    <span class="label-bold">NIM:</span>
                                    <span class="value-bold"><?= htmlspecialchars($nim) ?></span>
                                </div>
                            </div>

                            <div class="detail-row" style="padding-top: 0; padding-bottom: 25px;">
                                <div class="detail-kiri">
                                    <div class="info-text"><span class="label-bold">Nilai Kehadiran:</span> <?= $hadir ?>%</div>
                                    <div class="info-text"><span class="label-bold">Nilai Tugas:</span> <?= $tugas ?></div>
                                    <div class="info-text"><span class="label-bold">Nilai UTS:</span> <?= $uts ?></div>
                                    <div class="info-text"><span class="label-bold">Nilai UAS:</span> <?= $uas ?></div>
                                    <div class="info-text"><span class="label-bold">Nilai Akhir:</span> <?= number_format($na, 2) ?></div>
                                    <div class="info-text"><span class="label-bold">Grade:</span> <?= $g ?></div>
                                    <div class="info-text">
                                        <span class="label-bold">Status:</span>
                                        <span style="color: <?= $color_hex ?>; font-weight: none;"><?= $status ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <form method="post" class="d-grid">
                            <button type="submit" name="reset" class="btn text-white"
                                    style="background-color: <?= $color_hex ?>;">Selesai</button>
                        </form>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>