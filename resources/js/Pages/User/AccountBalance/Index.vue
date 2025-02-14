<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Top Up Saldo</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Top Up Saldo</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Top Up Saldo</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                <ul class="mt-3">
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>
            <div class="card">                
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">
                        Saldo Sekarang
                    </h5>
                </div>
                <div class="card-body">
                    <h3>{{ formatPrice($page.props.auth.user.account_balance) }}</h3>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-sm" @click.prevent="reedemAccountBalance()">Redeem Saldo</button>
                </div>
            </div>

            <form @submit.prevent="submit">
                <div class="card border-top border-0 border-3 border-primary">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Top Up Saldo
                        </h5>
                    </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Pilih Nominal Top Up</label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 mb-3">
                                    <input value="10000" type="radio" class="btn-check" name="options-outlined" id="primary-outlined-1" autocomplete="off" @click="clickBalance(10000)">
                                    <label class="btn btn-outline-primary w-100" for="primary-outlined-1">Rp. 10.000</label>
                                </div>
                                <div class="col-lg-3 col-md-3 mb-3">
                                    <input value="30000" type="radio" class="btn-check" name="options-outlined" id="primary-outlined-2" autocomplete="off" @click="clickBalance(30000)">
                                    <label class="btn btn-outline-primary w-100" for="primary-outlined-2">Rp. 30.000</label>
                                </div>
                                <div class="col-lg-3 col-md-3 mb-3">
                                    <input value="50000" type="radio" class="btn-check" name="options-outlined" id="primary-outlined-3" autocomplete="off" @click="clickBalance(50000)">
                                    <label class="btn btn-outline-primary w-100" for="primary-outlined-3">Rp. 50.000</label>
                                </div>
                                <div class="col-lg-3 col-md-3 mb-3">
                                    <input value="100000" type="radio" class="btn-check" name="options-outlined" id="primary-outlined-4" autocomplete="off" @click="clickBalance(100000)">
                                    <label class="btn btn-outline-primary w-100" for="primary-outlined-4">Rp. 100.000</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Atau Input Nominal Manual</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input type="number" min="0" class="form-control" placeholder="Nominal Top Up" v-model="form.top_up_balance" :class="{ 'is-invalid': errors.top_up_balance }">
                                <div v-if="errors.top_up_balance" class="invalid-feedback">
                                    {{ errors.top_up_balance }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary btn-sm px-4">Top Up Saldo Sekarang</button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </form>
        
            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-3">Riwayat Top Up Saldo</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Total Topup Saldo</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!transactions.data.length">
                                    <td colspan="8" align="center">Belum ada transaksi</td>
                                </tr>
                                <tr v-for="(transaction, index) in transactions.data" :key="index">
                                    <td>{{ ++index + (transactions.current_page - 1) * transactions.per_page }}</td>
                                    <td>{{ transaction.code }}</td>
                                    <td>{{ transaction.description }}</td>
                                    <td>{{ transaction.created_at }}</td>
                                    <td>{{ formatPrice(transaction.total_payment) }}</td>
                                    <td>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'account_balance'">Saldo Akun</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'automatic_transfer_midtrans'">Transfer Otomatis</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'manual_transfer'">Transfer Manual</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'not_payment_method'">Tanpa Metode Pembayaran</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning" v-if="transaction.transaction_status == 'pending'">Pending</span>
                                        <span class="badge bg-primary" v-if="transaction.transaction_status == 'paid'">Paid</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'expired'">Expired</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'failed'">Failed</span>
                                        <span class="badge bg-success" v-if="transaction.transaction_status == 'done'">Done</span>
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <Link :href="`/user/transactions/${transaction.id}`"><i class='bx bx-grid-alt'></i></Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <Pagination :links="transactions.links" align="end" />

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout user
    import LayoutUser from '../../../Layouts/Layout.vue';

    //import component pagination
    import Pagination from '../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive } from 'vue';

    import { Inertia } from '@inertiajs/inertia';

    import axios from 'axios';

    // import Swal
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    export default {
        // layout
        layout: LayoutUser,

        // register components
        components: {
            Link,
            Head,
            Pagination
        },

        // props
        props: {
            errors: Object,
            banks: Object,
            transactions: Object,
        },
        setup() {
            const form = reactive({
                top_up_balance: '',
            });

            const clickBalance = (value) => {
                form.top_up_balance = value;
            }


            const submit = () => {
                // send data to server
                Inertia.post('/user/account-balances', {
                    // data
                    top_up_balance: form.top_up_balance,
                });
            }

            const reedemAccountBalance = () => {
                Swal.fire({
                    title: 'Reedem Voucher',
                    text: "Masukan kode voucher yang kamu miliki",
                    input: 'text',
                    icon: 'info',
                    inputAttributes: {
                        autocapitalize: 'false'
                    },
                    showCancelButton: true,
                    allowOutsideClick: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: (code) => {
                        axios.get(`/user/account-balances/reedem-voucher`, {
                            params: {
                                code: code
                            }
                        })
                        .then(response => {
                            if(response.data.success == true) {
                                Swal.fire({
                                    title: 'Reedem Saldo Berhasil.',
                                    text: response.data.message,
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonText: 'Oke',
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        Inertia.get('/user/account-balances');
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: response.data.message,
                                    icon: 'error',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ulangi',
                                    cancelButtonText: 'Batal',
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        reedemAccountBalance();
                                    }
                                })
                            }
                        })
                        .catch(error => {
                            console.error('Request failed:', error);
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                    },
                })
            }

            // return form state and submit method
            return {
                form,
                submit,
                clickBalance,
                reedemAccountBalance
            }
        },

        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            copyText(text) {
                const el = document.createElement('textarea');
                el.value = text; 
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el); 
            }
        }
    }
</script>
