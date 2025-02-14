<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Berita</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Berita</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Berita</li>
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
                                    placeholder="Cari Berdasarkan Judul...."
                                    size="40"
                                    maxlength="100"
                                >
                                <span class="position-absolute top-50 product-show translate-middle-y">
                                    <i class="bx bx-search"></i>
                                </span>
                            </div>
                        </form>
                        <div class="ms-auto">
                            <Link href="/admin/news/create" class="btn btn-primary btn-sm mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i> Tambah Berita</Link>
                        </div>
                    </div>
                    <table class="table mb-0" style="font-size:10pt;">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Thumbnail</th>
                                <th>Judul</th>
                                <th width="200px">Dibuat</th>
                                <th>Created at</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(newsData, index) in news.data" :key="index">
                                <td>{{ ++index + (news.current_page - 1) * news.per_page }}</td>
                                <td>
                                    <div v-if="newsData.thumbnail">
                                        <img v-bind:src="'/storage/upload_files/news/' + newsData.thumbnail" style="width:90px;"/>
                                    </div>
                                    <div v-else>
                                        -
                                    </div>
                                </td>
                                <td>{{ newsData.title }}</td>
                                <td>{{ newsData.user ? newsData.user.name : '-' }}</td>
                                <td>{{ formatDateWithTime(newsData.created_at) }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-danger': newsData.is_published == 0, 'btn-primary': newsData.is_published == 1}">{{ newsData.is_published == 1 ? 'Published' : 'Unpublished' }}</button>
                                        <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-danger': newsData.is_published == 0, 'btn-primary': newsData.is_published == 1, 'split-bg-danger': newsData.is_published == 0, 'split-bg-primary': newsData.is_published == 1}" class="dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">	<span class="visually-hidden">Toggle Dropdown</span></button>
                                        <ul class="dropdown-menu">
                                            <li v-if="newsData.is_published != 1"><Link class="dropdown-item" :href="`/admin/news/${newsData.id}/change-status?is_published=1`">Published</Link></li>
                                            <li v-if="newsData.is_published != 0"><Link class="dropdown-item" :href="`/admin/news/${newsData.id}/change-status?is_published=0`">Unpublished</Link></li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                        <div class="dropdown">
											<button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true">Action</button>
											<ul class="dropdown-menu" data-popper-placement="top-start" data-popper-reference-hidden="" data-popper-escaped="">
												<li><Link class="dropdown-item" :href="`/admin/news/${newsData.id}`">Detail</Link></li>
                                                <li><Link class="dropdown-item" :href="`/admin/news/${newsData.id}/edit`">Edit</Link></li>
                                                <li><a class="dropdown-item" href="#" @click.prevent="destroy(newsData.id)">Hapus</a></li>
											</ul>
										</div>
                                    </td>
                            </tr>
                            <tr>
                                <td align="center" colspan="7" v-if="!news.data.length">Data Tidak Tersedia</td>
                            </tr>
                        </tbody>
                    </table>

                    <Pagination :links="news.links" align="end" />

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

    import { ref } from 'vue';

    // import Head from Inertia
    import { Head } from '@inertiajs/inertia-vue3';

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
            news: Object
        },

        // initialization composition API
        setup() {
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get('/admin/news', {
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

                        Inertia.delete(`/admin/news/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Berita Berhasil Dihapus!.',
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
