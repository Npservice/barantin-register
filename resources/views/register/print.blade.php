@php
if($barantin->lingkup_aktifitas){
    $lingkupAktivitas = json_decode($barantin->lingkup_aktifitas, true);
    $activityNames = array_column($lingkupAktivitas, 'activity');
    $activityList = implode(', ', $activityNames);
}
@endphp
{{--  @dd($barantin)  --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registrasi PTK Online</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
            /* Ukuran font default untuk body */
        }

        .container {
            width: 90%;
            /* Mengurangi lebar kontainer untuk memperkecil margin */
            margin: 20px auto;
            /* Mengurangi margin otomatis di kiri dan kanan */
            padding: 10px;
            /* Mengurangi padding di dalam kontainer */
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            position: relative;
            /* Menambahkan posisi relatif untuk container */
        }

        h1,
        h2 {
            color: #0056b3;
            margin-top: 0;
            font-size: 16px;
            /* Ukuran font untuk h1 */
        }

        h2 {
            font-size: 14px;
            /* Ukuran font untuk h2 */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
            /* Ukuran font untuk tabel */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            /* Mengurangi padding agar tabel lebih kompak */
            text-align: left;
            /* Menetapkan lebar kolom secara langsung */
        }

        th {
            background-color: #f2f2f2;
            width: 25%;
            /* Lebar kolom header */
        }

        td {
            width: 75%;
            /* Lebar kolom data */
        }

        .footer {
            text-align: center;
            margin-top: 5px;
            font-size: 12px;
            /* Ukuran font untuk footer */
        }

        .footer p {
            margin: 5px 0;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .qr-code {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>
    <!-- Logo Perusahaan -->
    <div class="logo">
        <img src="./assets/images/logo-light.png" width="80">
    </div>
    <div class="container">

        <h1>Registrasi PTK Online</h1>

        <!-- QR Code -->
        <div class="qr-code">
            <img src="{{ $qrCode }}" width="100" height="100">
        </div>

        <h2>Informasi Umum</h2>
        <table>
            <tr>
                <th style="width: 25%;">Nama</th>
                <td>{{ $barantin->nama_perusahaan }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Email</th>
                <td>{{ $barantin->email }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Jenis Pemohon</th>
                <td>{{ $barantin->preregister->pemohon }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Nama Perusahaan</th>
                <td>{{ $barantin->nama_perusahaan }}</td>
            </tr>
        </table>

        <h2>Data Registrasi</h2>
        <table>
            <tr>
                <th style="width: 25%;">Pemohon</th>
                <td>{{ $barantin->preregister->pemohon }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Identifikasi Perusahaan</th>
                <td>{{ $barantin->jenis_perusahaan }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Nama Perusahaan</th>
                <td>{{ $barantin->nama_perusahaan }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Jenis Identitas</th>
                <td>{{ $barantin->jenis_identitas }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">No Identitas</th>
                <td>{{ $barantin->nomor_identitas ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Telepon</th>
                <td>{{ $barantin->telepon ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Registrasi ke UPT</th>
                <td>
                    @foreach ($barantin['upt'] as $upt)
                        {{ $upt }}</br>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th style="width: 25%;">Jenis Perusahaan</th>
                <td>{{ $barantin->jenis_perusahaan }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Lingkup Aktivitas</th>
                <td>{{ $activityList }}</td>
            </tr>
        </table>

        <h2>Kontak Person</h2>
        <table>
            <tr>
                <th style="width: 25%;">Nama</th>
                <td>{{ $barantin->nama_cp ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Alamat</th>
                <td>{{ $barantin->alamat_cp ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Telepon</th>
                <td>{{ $barantin->telepon_cp ?? '' }}</td>
            </tr>
        </table>

        <h2>Penandatangan</h2>
        <table>
            <tr>
                <th style="width: 25%;">Nama</th>
                <td>{{ $barantin->nama_tdd ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Jenis Identitas</th>
                <td>{{ $barantin->jenis_identitas_tdd ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Nomor Identitas</th>
                <td>{{ $barantin->nomor_identitas_tdd ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Jabatan</th>
                <td>{{ $barantin->jabatan_tdd ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Alamat</th>
                <td>{{ $barantin->alamat_tdd ?? '' }}</td>
            </tr>
        </table>
        <br>
        <h2>Kegiatan Usaha</h2>
        <table>
            <tr>
                <th style="width: 25%;">Rerata Frekuensi Kegiatan Dalam 1 Tahun</th>
                <td>{{ $barantin->rerata_frekuensi ?? '-' }} kali</td>
            </tr>
            <tr>
                <th style="width: 25%;">Daftar Komoditas</th>
                <td>
                    @if (!empty($barantin->daftar_komoditas))
                        @foreach ($barantin->daftar_komoditas as $key => $value)
                            <div>
                                <strong>{{ ucwords(str_replace(['_', 'keterangan'], [' ', ''], $key)) }}:</strong>
                                {{ $value }}<br>
                            </div>
                        @endforeach
                    @else
                        <div>Tidak ada komoditas terdaftar.</div>
                    @endif
                </td>
            </tr>
        </table>

        <?php
        if($barantin->preregister->pemohon === 'perusahaan') { 
        ?>
        <h2>Sarana Prasarana</h2>
        <table>
            <tr>
                <th style="width: 25%;">Memiliki Tempat Tindakan Karantina</th>
                <td>{{ $barantin->nomor_registrasi ? 'Ya' : 'Tidak' }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Nomor Registrasi / SK Instalasi Karantina</th>
                <td>{{ $barantin->nomor_registrasi ?? '-' }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Status Kepemilikan</th>
                <td>{{ $barantin->status_kepemilikan ?? '-' }}</td>
            </tr>
        </table>
        <?php } ?>

        <h2>Dokumen Pendukung</h2>
        <table>
            <thead>
                <tr>
                    <th>Jenis Dokumen</th>
                    <th>Nomor Dokumen</th>
                    <th>Tanggal Terbit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barantin->dokumen_pendukung as $dokumen)
                    <tr>
                        <td>{{ $dokumen->jenis_dokumen }}</td>
                        <td>{{ $dokumen->nomer_dokumen }}</td>
                        <td>{{ $dokumen->tanggal_terbit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Terima kasih telah melakukan registrasi.</p>
            <p>Untuk pertanyaan lebih lanjut, silakan hubungi kantor karantina terdekat.</p>
        </div>
    </div>
</body>

</html>
