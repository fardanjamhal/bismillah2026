<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Setting</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Setting</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Setting</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <form @submit.prevent="submit">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-primary" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/applications" class="nav-link">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Aplikasi</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/authentications" class="nav-link">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Autentikasi</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/transactions" class="nav-link">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Transaksi</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/notifications" class="nav-link active">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Notifikasi</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/site-configurations" class="nav-link">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Konfigurasi Situs</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li v-if="$page.props.setting.enable_affiliate_feature == 1" class="nav-item" role="presentation">
                                        <Link href="/admin/settings/affiliates" class="nav-link">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Program Afiliasi</div>
                                            </div>
                                        </Link>
                                    </li>
                                </ul>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade active show" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div v-if="$page.props.session.success" class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="font-35 text-white"><i class='bx bxs-check-circle'></i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 text-white">Sukses</h6>
                                                            <div class="text-white">{{ $page.props.session.success }}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div v-if="$page.props.session.error" class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 text-white">Gagal</h6>
                                                            <div class="text-white">{{ $page.props.session.error }}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                                                    <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                                                    <ul class="mt-3">
                                                        <li v-for="error in errors">{{ error }}</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Tipe Notifikasi</label>
                                                <select name="" id="" class="form-control" v-model="form.notification_type" :class="{ 'is-invalid': errors.notification_type }">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Whatsapp</option>
                                                    <option value="2">Email</option>
                                                </select>
                                                <div v-if="errors.notification_type" class="invalid-feedback">
                                                    {{ errors.notification_type }}
                                                </div>
                                            </div>

                                            <div class="col-12" v-if="form.notification_type == 1">
                                                <label class="form-label">Nomor Whatsapp</label>
                                                <input type="text" class="form-control" v-model="form.whatsapp_number" :class="{ 'is-invalid': errors.whatsapp_number }" placeholder="Nomor Whatsapp">
                                                <div v-if="errors.whatsapp_number" class="invalid-feedback">
                                                    {{ errors.whatsapp_number }}
                                                </div>
                                            </div>

                                            <div class="col-12" v-if="form.notification_type == 1">
                                                <label class="form-label">Token Whatsapp (Untuk Notifikasi)</label>
                                                <input type="text" class="form-control" v-model="form.whatsapp_token" :class="{ 'is-invalid': errors.whatsapp_token }" placeholder="Token Whatsapp">
                                                <div v-if="errors.whatsapp_token" class="invalid-feedback">
                                                    {{ errors.whatsapp_token }}
                                                </div>
                                                <small>Token whatsaap digenerate menggunakan <i>third party fonnte</i>. <a href="https://fonnte.com/" target="_blank">klik disini</a> untuk generate token</small>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12" v-if="form.notification_type == 2">
                                                <label class="form-label">Mail Mailer</label>
                                                <input type="text" class="form-control" v-model="form.mail_mailer" :class="{ 'is-invalid': errors.mail_mailer }" placeholder="Mail Mailer">
                                                <div v-if="errors.mail_mailer" class="invalid-feedback">
                                                    {{ errors.mail_mailer }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12" v-if="form.notification_type == 2">
                                                <label class="form-label">Mail Host</label>
                                                <input type="text" class="form-control" v-model="form.mail_host" :class="{ 'is-invalid': errors.mail_host }" placeholder="Mail Host">
                                                <div v-if="errors.mail_host" class="invalid-feedback">
                                                    {{ errors.mail_host }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12" v-if="form.notification_type == 2">
                                                <label class="form-label">Mail Port</label>
                                                <input type="text" class="form-control" v-model="form.mail_port" :class="{ 'is-invalid': errors.mail_port }" placeholder="Mail Port">
                                                <div v-if="errors.mail_port" class="invalid-feedback">
                                                    {{ errors.mail_port }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12" v-if="form.notification_type == 2">
                                                <label class="form-label">Mail Username</label>
                                                <input type="text" class="form-control" v-model="form.mail_username" :class="{ 'is-invalid': errors.mail_username }" placeholder="Mail Username">
                                                <div v-if="errors.mail_username" class="invalid-feedback">
                                                    {{ errors.mail_username }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12" v-if="form.notification_type == 2">
                                                <label class="form-label">Mail Password</label>
                                                <input type="text" class="form-control" v-model="form.mail_password" :class="{ 'is-invalid': errors.mail_password }" placeholder="Mail Password">
                                                <div v-if="errors.mail_password" class="invalid-feedback">
                                                    {{ errors.mail_password }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12" v-if="form.notification_type == 2">
                                                <label class="form-label">Mail Encryption</label>
                                                <input type="text" class="form-control" v-model="form.mail_encryption" :class="{ 'is-invalid': errors.mail_encryption }" placeholder="Mail Encryption">
                                                <div v-if="errors.mail_encryption" class="invalid-feedback">
                                                    {{ errors.mail_encryption }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12" v-if="form.notification_type == 2">
                                                <label class="form-label">Mail Address</label>
                                                <input type="text" class="form-control" v-model="form.mail_from_address" :class="{ 'is-invalid': errors.mail_from_address }" placeholder="Mail Address">
                                                <div v-if="errors.mail_from_address" class="invalid-feedback">
                                                    {{ errors.mail_from_address }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12" v-if="form.notification_type == 2">
                                                <label class="form-label">Mail Name</label>
                                                <input type="text" class="form-control" v-model="form.mail_from_name" :class="{ 'is-invalid': errors.mail_from_name }" placeholder="Mail Name">
                                                <div v-if="errors.mail_from_name" class="invalid-feedback">
                                                    {{ errors.mail_from_name }}
                                                </div>
                                            </div>     
                                            <div class="col-12" v-if="form.notification_type">
                                                <button class="btn btn-primary  btn-sm" type="button" style="float:right;" @click="showModal">Tes Kirim {{ form.notification_type == 1 ? 'Whatsapp' : 'Email' }}</button>
                                            </div>        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-header">
                                <h6 class="card-title">Variabel Notifikasi Transaksi</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <table class="table table-bordered">
                                            <thead class="table-success">
                                                <tr>
                                                    <th colspan="3" style="text-align: center;">VARIABEL UNTUK NOTIFIKASI AKUN & TRANSAKSI</th>
                                                </tr>
                                                <tr>
                                                    <th width="30px">No</th>
                                                    <th>Variabel</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>{application_name}</td>
                                                    <td>Nama Aplikasi</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>{application_url}</td>
                                                    <td>Link Aplikasi</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>{verification_url}</td>
                                                    <td>Link Verifikasi Peserta</td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>{name}</td>
                                                    <td>Nama Peserta</td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td>{email}</td>
                                                    <td>Email Peserta</td>
                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td>{username}</td>
                                                    <td>Username Peserta</td>
                                                </tr>
                                                <tr>
                                                    <td>7</td>
                                                    <td>{province}</td>
                                                    <td>Provinsi Peserta</td>
                                                </tr>
                                                <tr>
                                                    <td>8</td>
                                                    <td>{city}</td>
                                                    <td>Kota / Kab Peserta</td>
                                                </tr>
                                                <tr>
                                                    <td>9</td>
                                                    <td>{district}</td>
                                                    <td>Kecamatan Peserta</td>
                                                </tr>
                                                <tr>
                                                    <td>10</td>
                                                    <td>{village}</td>
                                                    <td>Desa / Kelurahan Peserta</td>
                                                </tr>
                                                <tr>
                                                    <td>11</td>
                                                    <td>{address}</td>
                                                    <td>Alamat Peserta</td>
                                                </tr>
                                                <tr>
                                                    <td>12</td>
                                                    <td>{whatsapp_number}</td>
                                                    <td>Nomor Whatsapp Peserta</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <table class="table table-bordered">
                                            <thead class="table-success">
                                                <tr>
                                                    <th colspan="3" style="text-align: center;">VARIABEL UNTUK NOTIFIKASI TRANSAKSI</th>
                                                </tr>
                                                <tr>
                                                    <th width="30px">No</th>
                                                    <th>Variabel</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>{transaction_invoice_number}</td>
                                                    <td>Kode Transaksi</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>{transaction_date}</td>
                                                    <td>Tanggal Transaksi</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>{transaction_description}</td>
                                                    <td>Deskripsi Transaksi</td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>{transaction_payment_method}</td>
                                                    <td>Metode Pembayaran</td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td>{transaction_total_payment}</td>
                                                    <td>Total Transaksi</td>
                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td>{transaction_status}</td>
                                                    <td>Status Transaksi</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-header mb-0">
                                <h6 class="card-title">Notifikasi Aktivasi Akun</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Aktifkan Notifikasi Aktivasi Akun</label>
                                        <select name="" id="" class="form-control" v-model="form.account_activation_notification_is_active" :class="{ 'is-invalid': errors.account_activation_notification_is_active }">
                                            <option value="">[ Pilih ]</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                        <div v-if="errors.account_activation_notification_is_active" class="invalid-feedback">
                                            {{ errors.account_activation_notification_is_active }}
                                        </div>
                                    </div>
                                    <div class="col-12" v-if="form.account_activation_notification_is_active == 1">
                                        <label class="form-label">Pesan Notifikasi Aktivasi Akun</label>
                                        <textarea class="form-control" rows="15" v-model="form.account_activation_notification" :class="{ 'is-invalid': errors.account_activation_notification }" placeholder="Notifikasi Aktivasi Akun"></textarea>
                                        <div v-if="errors.account_activation_notification" class="invalid-feedback">
                                            {{ errors.account_activation_notification }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-header mb-0">
                                <h6 class="card-title">Notifikasi Reminder Aktivasi Akun</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Aktifkan Notifikasi Reminder Aktivasi Akun</label>
                                        <select name="" id="" class="form-control" v-model="form.account_activation_reminder_notification_is_active" :class="{ 'is-invalid': errors.account_activation_reminder_notification_is_active }">
                                            <option value="">[ Pilih ]</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                        <div v-if="errors.account_activation_reminder_notification_is_active" class="invalid-feedback">
                                            {{ errors.account_activation_reminder_notification_is_active }}
                                        </div>
                                    </div>
                                    <div class="col-12" v-if="form.account_activation_reminder_notification_is_active == 1">
                                        <label class="form-label">Pesan Notifikasi Reminder Aktivasi Akun</label>
                                        <textarea class="form-control" rows="15" v-model="form.account_activation_reminder_notification" :class="{ 'is-invalid': errors.account_activation_reminder_notification }" placeholder="Notifikasi Reminder Aktivasi Akun"></textarea>
                                        <div v-if="errors.account_activation_reminder_notification" class="invalid-feedback">
                                            {{ errors.account_activation_reminder_notification }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-header mb-0">
                                <h6 class="card-title">Notifikasi Transaksi Pending</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Aktifkan Notifikasi Transaksi Pending</label>
                                        <select name="" id="" class="form-control" v-model="form.transaction_pending_notification_is_active" :class="{ 'is-invalid': errors.transaction_pending_notification_is_active }">
                                            <option value="">[ Pilih ]</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                        <div v-if="errors.transaction_pending_notification_is_active" class="invalid-feedback">
                                            {{ errors.transaction_pending_notification_is_active }}
                                        </div>
                                    </div>
                                    <div class="col-12" v-if="form.transaction_pending_notification_is_active == 1">
                                        <label class="form-label">Pesan Notifikasi Transaksi Pending</label>
                                        <textarea class="form-control" rows="15" v-model="form.transaction_pending_notification" :class="{ 'is-invalid': errors.transaction_pending_notification }" placeholder="Notifikasi Transaksi Pending"></textarea>
                                        <div v-if="errors.transaction_pending_notification" class="invalid-feedback">
                                            {{ errors.transaction_pending_notification }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-header mb-0">
                                <h6 class="card-title">Notifikasi Transaksi Dibayar</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Aktifkan Notifikasi Transaksi Dibayar</label>
                                        <select name="" id="" class="form-control" v-model="form.transaction_paid_notification_is_active" :class="{ 'is-invalid': errors.transaction_paid_notification_is_active }">
                                            <option value="">[ Pilih ]</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                        <div v-if="errors.transaction_paid_notification_is_active" class="invalid-feedback">
                                            {{ errors.transaction_paid_notification_is_active }}
                                        </div>
                                    </div>
                                    <div class="col-12" v-if="form.transaction_paid_notification_is_active == 1">
                                        <label class="form-label">Pesan Notifikasi Transaksi Dibayar</label>
                                        <textarea class="form-control" rows="15" v-model="form.transaction_paid_notification" :class="{ 'is-invalid': errors.transaction_paid_notification }" placeholder="Notifikasi Transaksi Dibayar"></textarea>
                                        <div v-if="errors.transaction_paid_notification" class="invalid-feedback">
                                            {{ errors.transaction_paid_notification }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-header mb-0">
                                <h6 class="card-title">Notifikasi Transaksi Selesai</h6>
                            </div>

                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Aktifkan Notifikasi Transaksi Selesai</label>
                                        <select name="" id="" class="form-control" v-model="form.transaction_done_notification_is_active" :class="{ 'is-invalid': errors.transaction_done_notification_is_active }">
                                            <option value="">[ Pilih ]</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                        <div v-if="errors.transaction_done_notification_is_active" class="invalid-feedback">
                                            {{ errors.transaction_done_notification_is_active }}
                                        </div>
                                    </div>
                                    <div class="col-12" v-if="form.transaction_done_notification_is_active == 1">
                                        <label class="form-label">Pesan Notifikasi Transaksi Selesai</label>
                                        <textarea class="form-control" rows="15" v-model="form.transaction_done_notification" :class="{ 'is-invalid': errors.transaction_done_notification }" placeholder="Notifikasi Transaksi Selesai"></textarea>
                                        <div v-if="errors.transaction_done_notification" class="invalid-feedback">
                                            {{ errors.transaction_done_notification }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-header mb-0">
                                <h6 class="card-title">Notifikasi Transaksi Kedaluarsa</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Aktifkan Notifikasi Transaksi Kedaluarsa</label>
                                        <select name="" id="" class="form-control" v-model="form.transaction_expired_notification_is_active" :class="{ 'is-invalid': errors.transaction_expired_notification_is_active }">
                                            <option value="">[ Pilih ]</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                        <div v-if="errors.transaction_expired_notification_is_active" class="invalid-feedback">
                                            {{ errors.transaction_expired_notification_is_active }}
                                        </div>
                                    </div>
                                    <div class="col-12" v-if="form.transaction_expired_notification_is_active == 1">
                                        <label class="form-label">Pesan Notifikasi Transaksi Kedaluarsa</label>
                                        <textarea class="form-control" rows="15" v-model="form.transaction_expired_notification" :class="{ 'is-invalid': errors.transaction_expired_notification }" placeholder="Notifikasi Transaksi Kedaluarsa"></textarea>
                                        <div v-if="errors.transaction_expired_notification" class="invalid-feedback">
                                            {{ errors.transaction_expired_notification }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-header mb-0">
                                <h6 class="card-title">Notifikasi Transaksi Gagal</h6>
                            </div>

                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Aktifkan Notifikasi Transaksi Gagal</label>
                                        <select name="" id="" class="form-control" v-model="form.transaction_failed_notification_is_active" :class="{ 'is-invalid': errors.transaction_failed_notification_is_active }">
                                            <option value="">[ Pilih ]</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                        <div v-if="errors.transaction_failed_notification_is_active" class="invalid-feedback">
                                            {{ errors.transaction_failed_notification_is_active }}
                                        </div>
                                    </div>
                                    <div class="col-12" v-if="form.transaction_failed_notification_is_active == 1">
                                        <label class="form-label">Pesan Notifikasi Transaksi Gagal</label>
                                        <textarea class="form-control" rows="15" v-model="form.transaction_failed_notification" :class="{ 'is-invalid': errors.transaction_failed_notification }" placeholder="Notifikasi Transaksi Gagal"></textarea>
                                        <div v-if="errors.transaction_failed_notification" class="invalid-feedback">
                                            {{ errors.transaction_failed_notification }}
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
    <!--end page wrapper -->

     <!-- Modal -->
    <div class="modal fade" id="testSendMessageModal" tabindex="-1" aria-labelledby="testSendMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testSendMessageModalLabel">Tes Kirim {{ form.notification_type == 1 ? 'Whatsapp' : 'Email' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="messageErrors.length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in messageErrors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">{{ form.notification_type == 1 ? 'Nomor Whatsapp' : 'Email' }}</label>
                        <input type="text" class="form-control" v-model="messageForm.contact " id="contact" :placeholder="form.notification_type == 1 ? 'Masukkan Nomor. Contoh : 6281212126043' : 'Masukkan Alamat Email'">
                    </div>
                    <div class="mb-3">
                        <label for="whatsappMessage" class="form-label">Pesan</label>
                        <textarea class="form-control" v-model="messageForm.message" id="whatsappMessage" rows="10" placeholder="Masukkan Pesan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="sendMessage">Kirim Pesan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive } from 'vue';

    // import Swal
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import Multiselect from '@suadelabs/vue3-multiselect'

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Multiselect
        },

        //props
        props: {
            errors: Object,
            setting: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                notification_type : props.setting.notification_type ?? '',
                whatsapp_number : props.setting.whatsapp_number ?? '',
                whatsapp_token : props.setting.whatsapp_token ?? '',
                mail_mailer : props.setting.mail_mailer ?? '',
                mail_host : props.setting.mail_host ?? '',
                mail_port : props.setting.mail_port ?? '',
                mail_username : props.setting.mail_username ?? '',
                mail_password : props.setting.mail_password ?? '',
                mail_encryption : props.setting.mail_encryption ?? '',
                mail_from_address : props.setting.mail_from_address ?? '',
                mail_from_name : props.setting.mail_from_name ?? '',
                account_activation_notification_is_active : props.setting.account_activation_notification_is_active,
                account_activation_notification : props.setting.account_activation_notification ?? '',
                account_activation_reminder_notification_is_active : props.setting.account_activation_reminder_notification_is_active,
                account_activation_reminder_notification : props.setting.account_activation_reminder_notification ?? '',
                transaction_pending_notification_is_active : props.setting.transaction_pending_notification_is_active,
                transaction_pending_notification : props.setting.transaction_pending_notification ?? '',
                transaction_paid_notification_is_active : props.setting.transaction_paid_notification_is_active,
                transaction_paid_notification : props.setting.transaction_paid_notification ?? '',
                transaction_failed_notification_is_active : props.setting.transaction_failed_notification_is_active,
                transaction_failed_notification : props.setting.transaction_failed_notification ?? '',
                transaction_done_notification_is_active : props.setting.transaction_done_notification_is_active,
                transaction_done_notification : props.setting.transaction_done_notification ?? '',
                transaction_expired_notification_is_active : props.setting.transaction_expired_notification_is_active,
                transaction_expired_notification : props.setting.transaction_expired_notification ?? '',
            });

            const messageForm = reactive({
                contact : '',
                message: ''
            });

            const messageErrors = reactive([]);

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/settings/notifications`, {
                    forceFormData: true,
                    // data
                    notification_type : form.notification_type,
                    whatsapp_number : form.whatsapp_number,
                    whatsapp_token : form.whatsapp_token,
                    mail_mailer : form.mail_mailer,
                    mail_host : form.mail_host,
                    mail_port : form.mail_port,
                    mail_username : form.mail_username,
                    mail_password : form.mail_password,
                    mail_encryption : form.mail_encryption,
                    mail_from_address : form.mail_from_address,
                    mail_from_name : form.mail_from_name,
                    account_activation_notification_is_active : form.account_activation_notification_is_active,
                    account_activation_notification : form.account_activation_notification,
                    account_activation_reminder_notification_is_active : form.account_activation_reminder_notification_is_active,
                    account_activation_reminder_notification : form.account_activation_reminder_notification,
                    transaction_pending_notification_is_active : form.transaction_pending_notification_is_active,
                    transaction_pending_notification : form.transaction_pending_notification,
                    transaction_paid_notification_is_active : form.transaction_paid_notification_is_active,
                    transaction_paid_notification : form.transaction_paid_notification,
                    transaction_failed_notification_is_active : form.transaction_failed_notification_is_active,
                    transaction_failed_notification : form.transaction_failed_notification,
                    transaction_done_notification_is_active : form.transaction_done_notification_is_active,
                    transaction_done_notification : form.transaction_done_notification,
                    transaction_expired_notification_is_active : form.transaction_expired_notification_is_active,
                    transaction_expired_notification : form.transaction_expired_notification,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Setting Notifikasi Berhasil Diupdate!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
                });
            }

            const showModal = () => {
                const modal = new bootstrap.Modal(document.getElementById('testSendMessageModal'));
                modal.show();
            };

            const sendMessage = () => {
                messageErrors.length = 0; // Reset errors

                if (!messageForm.contact.trim()) {
                    messageErrors.push(form.notification_type == 1 ? 'Nomor Whatsapp harus diisi.' : 'Email harus diisi.');
                }

                if(form.notification_type == 2 && messageForm.contact.trim() && !/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(messageForm.contact)) {
                    messageErrors.push('Format email tidak valid.');
                }

                if (!messageForm.message.trim()) {
                    messageErrors.push('Pesan harus diisi.');
                }

                if (messageErrors.length > 0) {
                    return; 
                }

                Inertia.post(`/admin/settings/test-send-message`, {
                    notification_type : form.notification_type ,
                    contact : messageForm.contact ,
                    message: messageForm.message,
                }, {
                    onSuccess: () => {
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Pesan telah berhasil dikirim. Silakan cek pesan di '+(form.notification_type == 1 ? 'nomor' : 'email')+' tujuan.',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });

                        messageForm.contact  = '';
                        messageForm.message = '';
                        messageErrors.length = 0; // Reset errors after successful submission
                    },
                });

                const modal = bootstrap.Modal.getInstance(document.getElementById('testSendMessageModal'));
                modal.hide();
            };


            // return form state and submit method
            return {
                form,
                submit,
                showModal,
                messageForm,
                sendMessage,
                messageErrors
            }
        }
    }
</script>

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>