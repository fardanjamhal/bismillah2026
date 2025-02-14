<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Pengajuan Penarikan Komisi</title>
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
                            <li class="breadcrumb-item active" aria-current="page">Data Pengajuan Penarikan Komisi</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <form @submit.prevent="handleSearch">
                        <div class="position-relative mb-3">
                            <input
                                type="text"
                                v-model="search"
                                class="form-control ps-5 radius-20"
                                placeholder="Cari Berdasarkan Nama Data Referrer...."
                                size="40"
                                maxlength="100"
                            >
                            <span class="position-absolute top-50 product-show translate-middle-y">
                                <i class="bx bx-search"></i>
                            </span>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:10pt;">
                            <thead class="table-success">
                                <tr>
                                    <th>No</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'email')?.translate }}</th>
                                    <th>Pengajuan</th>
                                    <th>Jumlah</th>
                                    <th>Admin</th>
                                    <th>Diterima</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(withdrawal, index) in withdrawals.data" :key="index">
                                    <td>
                                        {{ ++index + (withdrawals.current_page - 1) * withdrawals.per_page }}
                                    </td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ withdrawal.user.name ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">{{ withdrawal.user.email ?? '-' }}</td>
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
                                        <div class="d-flex order-actions">
                                            <Link :href="`/admin/affiliates/withdrawals/${withdrawal.id}`" class="ms-1"><i class='bx bx-grid-alt'></i></Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!withdrawals.data.length">
                                    <td align="center" colspan="9">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="withdrawals.links" align="end" />

                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import sweet alert2
    import Swal from 'sweetalert2';

    //import ref from vue
    import {
        ref
    } from 'vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Pagination
        },

        // props
        props: {
            withdrawals: Object
        },

        // initialization composition API
        setup() {
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get('/admin/affiliates/withdrawals', {
                    //send params "search" with value from state "search"
                    search: search.value,
                })
            }

            return {
                search,
                handleSearch,
            }
        },
        methods: {
            formatPrice(value) {
                let val = Math.floor(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                return 'Rp.' + val
            },
        }
    }
</script>
