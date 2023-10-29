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
                  <input type="datetime-local" class="form-control" name="tgl" value="<?= $dataTransaksi['tgl'] ?>">
                </div>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="">
                  <h6>Batas Waktu Pembayaran</h6>
                </label>
                <div>
                  <input type="datetime-local" class="form-control" name="batastgl" value="<?= $dataTransaksi['batas_waktu'] ?>">
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
                        <input type="text" value="<?= $value ?>" class="form-control" name="qty<?= $paket['id'] ?>">
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
            </div>
            <button type="submit" class="ms-3 btn btn-primary float-end col-2 mt-10" name="simpan">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>