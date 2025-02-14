<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Voucher</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Voucher</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Voucher</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <form @submit.prevent="handleSearch">
                            <div class="position-relative">
                                <input
                                    type="text"
                                    v-model="search"
                                    class="form-control ps-5 radius-20"
                                    placeholder="Cari Berdasarkan Nama Voucher...."
                                    size="40"
                                    maxlength="100"
                                >
                                <span class="position-absolute top-50 product-show translate-middle-y">
                                    <i class="bx bx-search"></i>
                                </span>
                            </div>
                        </form>
                        <div class="ms-auto">
                            <Link href="/admin/vouchers/create" class="btn btn-primary btn-sm mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i> Tambah Voucher</Link>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:10pt;">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Nama</th>
                                    <th>Periode</th>
                                    <th>Harga</th>
                                    <th>Kategori Member</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(voucher, index) in vouchers.data" :key="index">
                                    <td>{{ ++index + (vouchers.current_page - 1) * vouchers.per_page }}</td>
                                    <td><span class="badge bg-primary">{{ voucher.category ? voucher.category.name : '-'}}</span></td>
                                    <td>{{ voucher.title }}</td>
                                    <td>{{ voucher.active_period }} {{ voucher.period_type == 'day' ? 'Hari' : 'Bulan' }}</td>
                                    <td>{{ formatPrice(voucher.price_after_discount) }}</td>
                                    <td>
                                        <div v-if="voucher.member_categories.length">
                                            <div v-for="(member_category, index) in voucher.member_categories" :key="index" style="display: inline;">
                                                <span class="badge bg-success ms-1">{{ member_category.name }}</span>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <span class="badge bg-success ms-1">Tidak ada</span>
                                        </div>
                                    </td>
                                    <td align="right">
                                        <div class="btn-group">
                                            <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-danger': voucher.is_active == 0, 'btn-primary': voucher.is_active == 1}">{{ voucher.is_active == 1 ? 'Active' : 'Inactive' }}</button>
                                            <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-danger': voucher.is_active == 0, 'btn-primary': voucher.is_active == 1, 'split-bg-danger': voucher.is_active == 0, 'split-bg-primary': voucher.is_active == 1}" class="dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">	<span class="visually-hidden">Toggle Dropdown</span></button>
                                            <ul class="dropdown-menu">
                                                <li v-if="voucher.is_active != 1"><Link class="dropdown-item" :href="`/admin/vouchers/${voucher.id}/change-voucher-status?is_active=1`">Active</Link></li>
                                                <li v-if="voucher.is_active != 0"><Link class="dropdown-item" :href="`/admin/vouchers/${voucher.id}/change-voucher-status?is_active=0`">Inactive</Link></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
											<button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true">Action</button>
											<ul class="dropdown-menu" data-popper-placement="top-start" data-popper-reference-hidden="" data-popper-escaped="">
												<li><Link class="dropdown-item" :href="`/admin/vouchers/${voucher.id}/edit`">Edit</Link></li>
                                                <li><a class="dropdown-item" href="#" @click.prevent="destroy(voucher.id)">Hapus</a></li>
											</ul>
										</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="9" v-if="!vouchers.data.length">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="vouchers.links" align="end" />

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
            vouchers: Object
        },

        // initialization composition API
        setup() {
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get('/admin/vouchers', {
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

                        Inertia.delete(`/admin/vouchers/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Voucher Berhasil Dihapus!.',
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
                let val = (value/1).toFixed(2).replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        }
    }
</script>
