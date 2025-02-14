<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Video Pembelajaran</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Video Pembelajaran</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Video Pembelajaran</li>
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
                                    placeholder="Cari Berdasarkan Judul Video Pembelajaran...."
                                    size="40"
                                    maxlength="100"
                                >
                                <span class="position-absolute top-50 product-show translate-middle-y">
                                    <i class="bx bx-search"></i>
                                </span>
                            </div>
                        </form>
                        <div class="ms-auto">
                            <Link href="/admin/video-modules/create" class="btn btn-primary btn-sm mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i> Tambah Video Pembelajaran</Link>
                        </div>
                    </div>
                    <table class="table mb-0" style="font-size:10pt;">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Thumbnail</th>
                                <th>Judul</th>
                                <th>Urutan</th>
                                <th>Total Modul</th>
                                <th>Kategori Member</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(videoModule, index) in videoModules.data" :key="index">
                                <td>
                                    {{ ++index + (videoModules.current_page - 1) * videoModules.per_page }}
                                </td>
                                <td><span class="badge bg-primary">{{ videoModule.category.name }}</span></td>
                                <td>
                                    <div v-if="videoModule.thumbnail">
                                        <img v-bind:src="'/storage/upload_files/video_modules/' + videoModule.thumbnail" style="width:90px;"/>
                                    </div>
                                    <div v-else>
                                        <img v-bind:src="'/assets/images/img_not_available.jpg'" style="width:90px;"/>
                                    </div>
                                </td>
                                <td>{{ videoModule.title }}</td>
                                <td>{{ videoModule.order }}</td>
                                <td>{{ videoModule.detail_video_module_count }}</td>
                                <td>
                                    <div v-if="videoModule.member_categories.length">
                                        <div v-for="(member_category, index) in videoModule.member_categories" :key="index" style="display: inline;">
                                            <span class="badge bg-success ms-1">{{ member_category.name }}</span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <span class="badge bg-success ms-1">Seluruh Member & Non Member</span>
                                    </div>
                                </td>
                                <td align="right">
                                    <div class="btn-group">
                                        <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-warning': videoModule.status === 'inprogress', 'btn-danger': videoModule.status === 'inactive', 'btn-primary': videoModule.status === 'active'}">{{ capitalizeWords(videoModule.status) }}</button>
                                        <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-warning': videoModule.status === 'inprogress', 'btn-danger': videoModule.status === 'inactive', 'btn-primary': videoModule.status === 'active', 'split-bg-warning': videoModule.status === 'inprogress', 'split-bg-danger': videoModule.status === 'inactive', 'split-bg-primary': videoModule.status === 'active'}" class="dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">	<span class="visually-hidden">Toggle Dropdown</span></button>
                                        <ul class="dropdown-menu">
                                            <li v-if="videoModule.status !== 'active'"><Link class="dropdown-item" :href="`/admin/video-modules/${videoModule.id}/change-video-module-status?status=active`">Active</Link></li>
                                            <li v-if="videoModule.status !== 'inactive'"><Link class="dropdown-item" :href="`/admin/video-modules/${videoModule.id}/change-video-module-status?status=inactive`">Inactive</Link></li>
                                            <li v-if="videoModule.status !== 'inprogress'"><Link class="dropdown-item" :href="`/admin/video-modules/${videoModule.id}/change-video-module-status?status=inprogress`">In Progress</Link></li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true">Action</button>
                                        <ul class="dropdown-menu" data-popper-placement="top-start" data-popper-reference-hidden="" data-popper-escaped="">
                                            <li><Link class="dropdown-item" :href="`/admin/video-modules/${videoModule.id}/detail-video-modules`">Detail</Link></li>
                                            <li><Link class="dropdown-item" :href="`/admin/video-modules/${videoModule.id}/edit`">Edit</Link></li>
                                            <li><a class="dropdown-item" href="#" @click.prevent="destroy(videoModule.id)">Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!videoModules.data.length">
                                <td align="center" colspan="9">Data Tidak Tersedia</td>
                            </tr>
                        </tbody>
                    </table>

                    <Pagination :links="videoModules.links" align="end" />

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
            videoModules: Object
        },

        // initialization composition API
        setup() {
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get('/admin/video-modules', {
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

                        Inertia.delete(`/admin/video-modules/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Video Pembelajaran Berhasil Dihapus!.',
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
