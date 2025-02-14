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
                        <div class="card-header bg-primary">
                            <h5 class="text-white mb-0">
                                Program Afiliasi
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <Link href="/user/affiliates/balances" class="nav-link active">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-money font-18 me-1"></i></div>
                                            <div class="tab-title m-1">Komisi</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/user/affiliates/term-conditions" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-info-circle font-18 me-1"></i></div>
                                            <div class="tab-title m-1">S & K</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/user/affiliates/links" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-link font-18 me-1"></i></div>
                                            <div class="tab-title m-1">Link</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/user/affiliates/withdrawals" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-credit-card-front font-18 me-1"></i></div>
                                            <div class="tab-title m-1">Penarikan</div>
                                        </div>
                                    </Link>
                                </li>
                            </ul>
                            <div class="tab-content pt-3">
                                <div class="tab-pane fade active show" role="tabpanel">
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
                    </div>
                </div>

                <div class="col-12">
                    <div class="card border-top border-0 border-3 border-primary">
                        <div class="card-header">
                            <h6 class="mb-0">
                                Monitoring Referral
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <Link href="/user/affiliates/balances" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-money font-18 me-1"></i></div>
                                            <div class="tab-title m-1">Transaksi Referral</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/user/affiliates/referrals" class="nav-link active">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-user-plus font-18 me-1"></i></div>
                                            <div class="tab-title m-1">Peserta Referral</div>
                                        </div>
                                    </Link>
                                </li>
                            </ul>
                            <div class="tab-content pt-3">
                                <div class="tab-pane fade active show" role="tabpanel">
                                    <div class="alert alert-success border-0">
                                        <i class="fa fa-exclamation-triangle"></i>Daftar peserta yang registrasi dengan kode referral Anda.
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table mb-0" style="font-size:10pt;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</th>
                                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'code')?.translate }}</th>
                                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'email')?.translate }}</th>
                                                    <th>Registrasi</th>
                                                    <th>Transaksi Selesai</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(userReferral, index) in userReferrals.data" :key="index">
                                                    <td>{{ ++index + (userReferrals.current_page - 1) * userReferrals.per_page }}</td>
                                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ userReferral.name ?? '-' }}</td>
                                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ userReferral.code ?? '-' }}</td>
                                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">{{ userReferral.email ?? '-' }}</td>
                                                    <td>{{ userReferral.created_at }}</td>
                                                    <td>
                                                        <span class="badge bg-success" v-if="userReferral.transaction_referrals_count > 0">{{ userReferral.transaction_referrals_count }} Transaksi</span>
                                                        <span class="badge bg-danger" v-else>Belum ada transaksi</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success" v-if="userReferral.is_active == 1">Aktif</span>
                                                        <span v-else class="badge bg-danger">Nonaktif</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td v-if="!userReferrals.data.length" colspan="8" align="center">Data Tidak Tersedia</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <Pagination :links="userReferrals.links" align="end" />
                                </div>
                            </div>
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
            userReferrals: Object,
        },
        methods: {
            formatPrice(value) {
                let val = Math.floor(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                return 'Rp.' + val
            },
        }
    }
</script>