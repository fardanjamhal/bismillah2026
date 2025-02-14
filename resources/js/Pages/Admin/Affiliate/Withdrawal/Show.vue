<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Detail Penarikan Komisi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Pengajuan Penarikan Komisi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Penarikan Komisi</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link href="/user/affiliates/withdrawals" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit">
                        <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                            <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <th colspan="3">Biodata</th>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td width="10">:</td>
                                        <td>{{ withdrawal.user ? withdrawal.user.name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>:</td>
                                        <td>{{ withdrawal.user ? withdrawal.user.email : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Username</td>
                                        <td>:</td>
                                        <td>{{ withdrawal.user ? withdrawal.user.username : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3">&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Pengajuan Penarikan</th>
                                    </tr>
                                    <tr>
                                        <td>Waktu Pengajuan</td>
                                        <td>:</td>
                                        <td>{{ formatDateWithTimeHourMinute(withdrawal.created_at) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Penarikan Komisi</td>
                                        <td>:</td>
                                        <td>{{ formatPrice(withdrawal.total_withdrawal) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Biaya Admin Penarikan</td>
                                        <td>:</td>
                                        <td>{{ formatPrice(withdrawal.admin_fee) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Komisi Diterima</td>
                                        <td>:</td>
                                        <td>{{ formatPrice(withdrawal.total_paid) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Dikirim ke Bank</td>
                                        <td>:</td>
                                        <td>{{ withdrawal.user_bank.bank_name + ' - ' + withdrawal.user_bank.account_number + ' - ' + withdrawal.user_bank.account_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status Pengajuan {{ form.status }}</td>
                                        <td>:</td>
                                        <td>
                                            <span class="badge bg-warning" v-if="withdrawal.status == 'pending'">Pending</span>
                                            <span class="badge bg-danger" v-if="withdrawal.status == 'failed'">Ditolak</span>
                                            <span class="badge bg-success" v-if="withdrawal.status == 'approved'">Disetujui</span>
                                        </td>
                                    </tr>
                                    <tr v-if="withdrawal.status == 'approved'">
                                        <td>Tanggal Disetujui</td>
                                        <td>:</td>
                                        <td>{{ withdrawal.date_approved ? formatDateWithTimeHourMinute(withdrawal.date_approved) : '-' }}</td>
                                    </tr>
                                    <tr v-if="withdrawal.status == 'approved'">
                                        <td>Bukti Transfer</td>
                                        <td>:</td>
                                        <td>
                                            <img v-bind:src="'/storage/upload_files/withdrawal_payment_confirmation/' + withdrawal.file" style="width: 300px;"/>
                                        </td>
                                    </tr>
                                    <tr v-if="withdrawal.status == 'pending'">
                                        <td>Ubah Status Transaksi</td>
                                        <td>:</td>
                                        <td>
                                            <select class="form-control" v-model="form.status" :class="{ 'is-invalid': errors.status }" >
                                                <option value="">[ Pilih Status Transaksi ]</option>
                                                <option value="pending">Pending</option>
                                                <option value="approved">Disetujui</option>
                                                <option value="failed">Ditolak</option>
                                            </select>
                                            <div v-if="errors.status" class="invalid-feedback">
                                                {{ errors.status }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="withdrawal.status == 'pending'">
                                        <td>Bukti Transfer</td>
                                        <td>:</td>
                                        <td>
                                            <input type="file" class="form-control" @input="form.file = $event.target.files[0]" :class="{ 'is-invalid': errors.file }" placeholder="file">
                                            <div v-if="errors.file" class="invalid-feedback">
                                                {{ errors.file }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="withdrawal.status == 'pending'">
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutUser from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    import { reactive } from 'vue';
    
    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    // import Swal
    import Swal from 'sweetalert2';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutUser,

        // register components
        components: {
            Link,
            Head,
        },

        //props
        props: {
            errors: Object,
            withdrawal: Object,
        },
        setup(props) {
            const form = reactive({
                status: props.withdrawal.status,
                file: '',
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/affiliates/withdrawals/${props.withdrawal.id}`, {
                    // data
                    forceFormData: true,
                    _method: 'put',
                    status: form.status,
                    file: form.file,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Status Pengajuan Berhasil Diupdate!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
            }
        },
        // initialization composition API
        methods: {
            formatPrice(value) {
                let val = Math.floor(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                return 'Rp.' + val
            },
        }
    }
</script>
