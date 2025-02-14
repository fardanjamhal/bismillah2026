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
                            <li class="breadcrumb-item active" aria-current="page">Penarikan</li>
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
                                    <Link href="/user/affiliates/balances" class="nav-link">
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
                                    <Link href="/user/affiliates/withdrawals" class="nav-link active">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-credit-card-front font-18 me-1"></i></div>
                                            <div class="tab-title m-1">Penarikan</div>
                                        </div>
                                    </Link>
                                </li>
                            </ul>
                            <div class="tab-content py-3">
                                <div class="tab-pane fade active show" role="tabpanel">
                                    <Link href="/user/affiliates/withdrawals/create" class="btn btn-primary btn-sm mb-3">Ajukan Penarikan Komisi</Link>
                                    <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                                        <div v-html="$page.props.session.error"></div>
                                    </div>
                                    <div v-if="$page.props.session.success" class="alert alert-success border-0">
                                        <div v-html="$page.props.session.success"></div>
                                    </div>
                                    <div class="alert alert-warning border-0">
                                        Anda bisa melakukan penarikan dengan minimal saldo <b>{{ formatPrice(setting.minimum_withdrawal) }}</b>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table mb-0" style="font-size:10pt;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Pengajuan</th>
                                                    <th>Jumlah</th>
                                                    <th>Admin</th>
                                                    <th>Diterima</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
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
                                            <td>
                                                <Link :href="`/user/affiliates/withdrawals/${withdrawal.id}`" class="btn btn-primary btn-sm">Detail</Link>
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
            dataStatus: Object,
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

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>