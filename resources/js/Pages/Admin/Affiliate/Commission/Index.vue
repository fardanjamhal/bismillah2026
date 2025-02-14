<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Komisi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Komisi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Komisi</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2 border-warning mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Transaksi Referrer</p>
                                            <h4 class="my-1 text-warning">{{ commissionCount }}</h4>
                                        </div>
                                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class="bx bxs-user-plus"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2 border-primary mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Komisi Referrer</p>
                                            <h4 class="my-1 text-primary">{{ formatPrice(summaryUserCommission.total_commission) }}</h4>
                                        </div>
                                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class="bx bx-money"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2 border-danger mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Penarikan Referrer</p>
                                        <h4 class="my-1 text-danger">{{ formatPrice(summaryUserCommission.total_withdrawals) }}</h4>
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
                                        <p class="mb-0 text-secondary">Komisi Belum Ditarik</p>
                                        <h4 class="my-1 text-success">{{ formatPrice(summaryUserCommission.current_balance) }}</h4>

                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class="bx bx-credit-card-front"></i>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <form @submit.prevent="handleSearch">
                            <div class="position-relative">
                                <input
                                    type="text"
                                    v-model="search"
                                    class="form-control ps-5 radius-20"
                                    placeholder="Cari Berdasarkan Data Referrer...."
                                    size="40"
                                    maxlength="100"
                                >
                                <span class="position-absolute top-50 product-show translate-middle-y">
                                    <i class="bx bx-search"></i>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:10pt;">
                            <thead class="table-success">
                                <tr>
                                    <th>No</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'email')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'username' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'username')?.translate }}</th>
                                    <th>Total Komisi</th>
                                    <th>Total Penarikan</th>
                                    <th>Komisi Saat Ini</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(commission, index) in userCommissions.data" :key="index">
                                    <td>
                                        {{ ++index + (userCommissions.current_page - 1) * userCommissions.per_page }}
                                    </td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ commission.user.name ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">{{ commission.user.email ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'username' && field.is_active == '1')">{{ commission.user.username ?? '-' }}</td>
                                    <td>{{ formatPrice(commission.total_commission) }}</td>
                                    <td>{{ formatPrice(commission.total_withdrawals) }}</td>
                                    <td>{{ formatPrice(commission.current_balance) }}</td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <Link :href="`/admin/affiliates/commissions/${commission.id}`" class="ms-1"><i class='bx bx-grid-alt'></i></Link>
                                            <!-- <Link :href="`/admin/affiliates/commissions/${commission.id}/edit`" class="ms-1"><i class='bx bxs-edit'></i></Link> -->
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!userCommissions.data.length">
                                    <td align="center" colspan="8">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="userCommissions.links" align="end" />

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
            userCommissions: Object,
            summaryUserCommission: Object,
            commissionCount: Object,
        },

        // initialization composition API
        setup() {
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get('/admin/affiliates/commissions', {
                    //send params "search" with value from state "search"
                    search: search.value,
                })
            }

            const destroy = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/affiliates/commissions/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Sub Kategori Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            return {
                search,
                handleSearch,
                destroy
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
