<button type="button" data-bs-toggle="modal" data-bs-target="#normal" class="btn btn-warning"><i class="fa fa-address-book"></i>Edit Data</button>
<div class="modal fade text-left" id="normal" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel1">Edit Transaksi</h5>
        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <form class="form" method="post">
          <div class="row">
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="">
                  <h6>Nama Pelanggan</h6>
                </label>
                <fieldset class="form-group">
                  <select class="form-select" id="basicSelect" name="pelanggan">
                    <?php foreach ($dataSemuaPelanggan as $pelanggan) : ?>
                      <?php $terpilih = ""; ?>
                      <?php if ($dataTransaksi['id_pelanggan'] == $pelanggan['id']) {
                        $terpilih .= "selected";
                      } ?>
                      <option <?= @$terpilih ?> value="<?= $pelanggan['id']; ?>"><?= $pelanggan['nama']; ?></option>
                    <?php endforeach ?>
                  </select>
                </fieldset>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="">
                  <h6>Masukkan Tanggal</h6>
                </label>
                <div>
                  <input type="datetime-local" id="update_tanggal" class="form-control" placeholder="Tanggal" name="tgl" onchange="setBatasWaktu()" required value="<?= $dataTransaksi['tgl'] ?>">
                </div>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="">
                  <h6>Batas Waktu</h6>
                </label>
                <div>
                  <input type="datetime-local" id="update_batas" class="form-control" placeholder="Batas Waktu" name="btstgl" onchange="hitung()" value="<?= $dataTransaksi['batas_waktu'] ?>">
                </div>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="">
                  <h6>Tanggal Pembayaran</h6>
                </label>
                <div>
                  <input type="datetime-local" class="form-control" name="tglbayar" value="<?= $dataTransaksi['tgl_bayar'] ?>">
                </div>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="">
                  <h6>Status</h6>
                </label>
                <fieldset class="form-group">
                  <select class="form-select" id="basicSelect" name="status">
                    <option value="baru" <?= ($dataTransaksi['status'] == 'baru') ? 'selected' : ''; ?>>Baru</option>
                    <option value="proses" <?= ($dataTransaksi['status'] == 'proses') ? 'selected' : ''; ?>>Proses</option>
                    <option value="selesai" <?= ($dataTransaksi['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                    <option value="diambil" <?= ($dataTransaksi['status'] == 'diambil') ? 'selected' : ''; ?>>Diambil</option>
                  </select>
                </fieldset>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="">
                  <h6>Status Bayar</h6>
                </label>
                <fieldset class="form-group">
                  <select class="form-select" id="basicSelect" name="status_bayar">
                    <option value="dibayar" <?= ($dataTransaksi['dibayar'] == 'dibayar') ? 'selected' : ''; ?>>Dibayar</option>
                    <option value="belum_dibayar" <?= ($dataTransaksi['dibayar'] == 'belum_dibayar') ? 'selected' : ''; ?>>Belum Dibayar</option>
                  </select>
                </fieldset>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label>Layanan Antar</label>
                <div class="form-group">
                  <select class="form-select" id="update_layananAntar" name="layanan_antar" onchange="hitung()">
                    <option value="0" <?= ($dataTransaksi['layanan_antar'] == 0) ? 'selected' : ''; ?>>TIDAK</option>
                    <option value="<?= $dataLayanan['harga'] ?>" <?= (!empty($dataTransaksi['layanan_antar'])) ? 'selected' : ''; ?>>YA</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label>Layanan Jemput</label>
                <div class=" form-group">
                  <select class="form-select" id="update_layananJemput" name="layanan_jemput" onchange="hitung()">
                    <option value="0" <?= ($dataTransaksi['layanan_jemput'] == 0) ? 'selected' : ''; ?>>TIDAK</option>
                    <option value="<?= $dataLayananJ['harga'] ?>" <?= (!empty($dataTransaksi['layanan_jemput'])) ? 'selected' : ''; ?>>YA</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label>Status Antar</label>
                <div class="form-group">
                  <select class="form-select" id="update_statusAntar" name="status_antar">
                    <option value="blm_diantar" <?= ($dataTransaksi['layanan_antar'] == 0 && $dataTransaksi['status_antar'] == 'blm_diantar') ? 'selected' : ''; ?>>Blm Diantar</option>
                    <option value="diantar" <?= (!empty($dataTransaksi['layanan_antar']) && $dataTransaksi['status_antar'] == 'diantar') ? 'selected' : ''; ?>>Diantar</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label>Status Jemput</label>
                <div class=" form-group">
                  <select class="form-select" id="update_statusJemput" name="status_jemput">
                    <option value="blm_dijemput" <?= ($dataTransaksi['layanan_jemput'] == 0 && $dataTransaksi['status_jemput'] == 'blm_dijemput') ? 'selected' : ''; ?>>Blm Dijemput</option>
                    <option value="dijemput" <?= (!empty($dataTransaksi['layanan_jemput']) && $dataTransaksi['status_jemput'] == 'dijemput') ? 'selected' : ''; ?>>Dijemput</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Daftar Paket dan form paket -->
            <div class="mt-lg-2">
              <label for="">
                <h6>Daftar Paket</h6>
              </label>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered mb-0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Paket</th>
                    <th>Jenis</th>
                    <th>Harga</th>
                    <th>Berat(Kg)</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 0; ?>
                  <?php foreach ($dataPaket as $paket) : ?>
                    <?php $i++; ?>
                    <tr>
                      <td><?= $i; ?></td>
                      <td><?= $paket['nama_paket']; ?></td>
                      <td><?= $paket['jenis']; ?></td>
                      <td><?= $paket['harga']; ?></td>
                      <td>
                        <?php $value = 0; ?>
                        <?php foreach ($dataDetailTransaksi as $detail) : ?>
                          <?php if ($detail['id_paket'] == $paket['id']) {
                            $value += $detail['qty'];
                          } ?>
                        <?php endforeach ?>
                        <input type="number" value="<?= $value ?>" class="form-control" name="qty<?= $paket['id'] ?>" id="update_qty<?= $paket['id'] ?>" onchange="hitung()">
                      </td>
                      <td>
                        <?php $keterangan = ""; ?>
                        <?php foreach ($dataDetailTransaksi as $detail) : ?>
                          <?php if ($detail['id_paket'] == $paket['id']) {
                            $keterangan .= $detail['keterangan'];
                          } ?>
                        <?php endforeach ?>
                        <input type="text" class="form-control" name="ket<?= $paket['id'] ?>" id="" value="<?= $keterangan ?>">
                      </td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="user">Total Harga</label>
                  <input class="form-control" type="text" name="total_harga" id="update_hasil_paket_akhir" onkeyup="hitung()" readonly="" value="<?= $dataTransaksi['total_harga'] ?>">
                </div>
              </div>
            </div>
            <button type="submit" class="ms-3 btn btn-primary float-end col-2 mt-10" name="simpan">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>