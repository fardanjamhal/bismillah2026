<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Metode Pembayaran</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Metode Pembayaran</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Metode Pembayaran</li>
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
                                    placeholder="Cari Berdasarkan Kode...."
                                    size="40"
                                    maxlength="100"
                                >
                                <span class="position-absolute top-50 product-show translate-middle-y">
                                    <i class="bx bx-search"></i>
                                </span>
                            </div>
                        </form>
                    <table class="table mb-0" style="font-size:10pt;">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Tampilkan<span style="color:#f8f9fa;">_</span>Saat<span style="color:#f8f9fa;">_</span>Transaksi</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(paymentMethod, index) in paymentMethods.data" :key="index">
                                <td>{{ paymentMethod.code }}</td>
                                <td>{{ paymentMethod.name }}</td>
                                <td>{{ paymentMethod.description }}</td>
                                <td>{{  paymentMethod.show_description == 1 ? 'Tampilkan' : 'Tidak Ditampilkan'  }}</td>
                                <td>{{  paymentMethod.is_active == 1 ? 'Active' : 'Inactive'  }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <Link :href="`/admin/payment-methods/${paymentMethod.code}/edit`" class="ms-1"><i class='bx bxs-edit'></i></Link>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" colspan="7" v-if="!paymentMethods.data.length">Data Tidak Tersedia</td>
                            </tr>
                        </tbody>
                    </table>

                    <Pagination :links="paymentMethods.links" align="end" />

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
            paymentMethods: Object
        },

        // initialization composition API
        setup() {
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get('/admin/payment-methods', {
                    //send params "search" with value from state "search"
                    search: search.value,
                })
            }

            return {
                search,
                handleSearch,
            }
        }
    }
</script>
