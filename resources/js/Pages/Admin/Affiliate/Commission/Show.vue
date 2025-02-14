<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Program Afiliasi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Program Afiliasi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Komisi</li>
                        </ol>
                    </nav>
                </div>  
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card border-top border-0 border-3 border-primary">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">Detail Referrer</h6>
                                </div>
                                <div class="ms-auto">
                                    <Link href="/admin/affiliates/commissions" class="btn btn-primary btn-sm me-2">
                                        Kembali
                                    </Link>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                                <div class="col">
                                    <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2 mb-3 border-warning">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-secondary">Jumlah Transaksi Selesai</p>
                                                <h4 class="my-1 text-warning">{{ userCommission.commission_count }}</h4>
                                            </div>
                                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class="bx bxs-user-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div> 
                                <div class="col">
                                    <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2 mb-3 border-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p class="mb-0 text-secondary">Total Komisi</p>
                                                    <h4 class="my-1 text-primary">{{ formatPrice(userCommission.user_commission ? userCommission.user_commission.total_commission : 0) }}</h4>
                                                </div>
                                                <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class="bx bx-money"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2 mb-3 border-danger">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-secondary">Total Penarikan</p>
                                                <h4 class="my-1 text-danger">{{ formatPrice(userCommission.user_commission ? userCommission.user_commission.total_withdrawals : 0) }}</h4>
                                            </div>
                                            <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class="bx bx-transfer-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2 mb-3 border-success">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-secondary">Komisi Saat Ini</p>
                                                <h4 class="my-1 text-success">{{ formatPrice(userCommission.user_commission ? userCommission.user_commission.current_balance : 0) }}</h4>

                                            </div>
                                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class="bx bx-credit-card-front"></i>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card border-top border-0 border-3 border-primary">
                        <div class="card-header">
                            <h6 class="mb-0">
                                Daftar Transaksi Referrer
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0" style="font-size:10pt;">
                                    <thead class="table-success">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Total</th>
                                            <th>Komisi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(commission, index) in commissions.data" :key="index">
                                            <td>{{ ++index + (commissions.current_page - 1) * commissions.per_page }}</td>
                                            <td>{{ commission.transaction ? commission.transaction.code : '-' }}</td>
                                            <td>{{ commission.transaction && commission.transaction.user ? commission.transaction.user.name : '-' }}</td>
                                            <td>{{ commission.transaction ? commission.transaction.created_at : '-' }}</td>
                                            <td>{{ commission.transaction ? commission.transaction.description : '-' }}</td>
                                            <td>{{ formatPrice(commission.transaction.total_payment) }}</td>
                                            <td>{{ formatPrice(commission.amount) }}</td>
                                            <td>
                                                <span class="badge bg-warning" v-if="commission.transaction.transaction_status == 'pending'">Pending</span>
                                                <span class="badge bg-danger" v-if="commission.transaction.transaction_status == 'expired'">Expired</span>
                                                <span class="badge bg-primary" v-if="commission.transaction.transaction_status == 'paid'">Paid</span>
                                                <span class="badge bg-danger" v-if="commission.transaction.transaction_status == 'failed'">Failed</span>
                                                <span class="badge bg-success" v-if="commission.transaction.transaction_status == 'done'">Done</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td v-if="!commissions.data.length" colspan="8" align="center">Data Tidak Tersedia</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <Pagination :links="commissions.links" align="end" />
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card border-top border-0 border-3 border-primary">
                        <div class="card-header">
                            <h6 class="mb-0">
                                Daftar Pengajuan Penarikan Komisi Referrer
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0" style="font-size:10pt;">
                                    <thead class="table-success">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Jumlah Penarikan</th>
                                            <th>Biaya Admin</th>
                                            <th>Komisi Diterima</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <tr v-for="(withdrawal, index) in withdrawals.data" :key="index">
                                    <td>{{ ++index + (withdrawals.current_page - 1) * withdrawals.per_page }}</td>
                                    <td>{{ formatDateWithTimeHourMinute(withdrawal.created_at) }}</td>
                                    <td>{{ formatPrice(withdrawal.total_withdrawal) }}</td>
                                    <td>{{ formatPrice(withdrawal.admin_fee) }}</td>
                                    <td>{{ formatPrice(withdrawal.total_paid) }}</td>
                                    <td>
                                        <span class="badge bg-warning" v-if="withdrawal.status == 'pending'">Pending</span>
                                        <span class="badge bg-danger" v-if="withdrawal.status == 'failed'">Ditolak</span>
                                        <span class="badge bg-success" v-if="withdrawal.status == 'approved'">Disetujui</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td v-if="!withdrawals.data.length" colspan="7" align="center">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                                </table>
                            </div>
                            <Pagination :links="withdrawals.links" align="end" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->

</template>

<script>
    //import layout
    import LayoutUser from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    import Pagination from '../../../../Components/Pagination.vue';

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

        //props
        props: {
            errors: Object,
            setting: Object,
            userCommission: Object,
            commissions: Object,
            withdrawals: Object,
        },
        methods: {
            formatPrice(value) {
                let val = Math.floor(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                return 'Rp.' + val
            },
        }
    }
</script>