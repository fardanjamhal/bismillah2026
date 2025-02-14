<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Detail Video Pembelajaran</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Detail Video Pembelajaran</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Detail Video Pembelajaran</li>
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
                                    placeholder="Cari Berdasarkan Judul Materi /Modul...."
                                    size="40"
                                    maxlength="100"
                                >
                                <span class="position-absolute top-50 product-show translate-middle-y">
                                    <i class="bx bx-search"></i>
                                </span>
                            </div>
                        </form>
                        <div class="ms-auto">
                            <Link href="/admin/video-modules" class="btn btn-primary btn-sm mx-1"> Kembali</Link>
                            <Link :href="`/admin/video-modules/${module.id}/detail-video-modules/create`" class="btn btn-primary btn-sm"><i class="bx bxs-plus-square"></i> Tambah Detail Video Pembelajaran</Link>
                        </div>
                    </div>
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Link</th>
                                <th>Urutan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(detailModule, index) in detailVideoModules.data" :key="index">
                                <td>
                                    {{ ++index + (detailVideoModules.current_page - 1) * detailVideoModules.per_page }}
                                </td>
                                <td>{{ detailModule.title }}</td>
                                <td><a :href="detailModule.link" target="_blank">{{ detailModule.link }}</a></td>
                                <td>{{ detailModule.order }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <Link :href="`/admin/video-modules/${module.id}/detail-video-modules/${detailModule.id}/edit`"><i class='bx bxs-edit'></i></Link>
                                        <a href="#" @click.prevent="destroy(detailModule.id)" class="ms-1"><i class="bx bxs-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!detailVideoModules.data.length">
                                <td align="center" colspan="5">Data Tidak Tersedia</td>
                            </tr>
                        </tbody>
                    </table>

                    <Pagination :links="detailVideoModules.links" align="end" />

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
            module: Object,
            detailVideoModules: Object,
        },

        // initialization composition API
        setup(props) {
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get(`/admin/video-modules/${props.module.id}/detail-video-modules`, {
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

                        Inertia.delete(`/admin/video-modules/${props.module.id}/detail-video-modules/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Detail Video Pembelajaran Berhasil Dihapus!.',
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
        }
    }
</script>
