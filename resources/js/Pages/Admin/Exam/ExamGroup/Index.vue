<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Tryout</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Tryout</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Tryout</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Filter Data</h6>
                        </div>
                    </div>
                </div>

                <div class="card-body"> <!-- Change 'text-center' to 'text-end' -->
                    <form>
                        <div class="row">
                            <div class="col-md-6 col-lg-3 mb-1">
                                <label for="search">Judul Tryout</label>
                                <input type="text" v-model="form.search" class="form-control form-control-sm" id="search" placeholder="Cari Berdasarkan Judul Tryout....">
                            </div>

                            <div class="col-md-6 col-lg-3 mb-1">
                                <label for="category">Kategori Peminatan</label>
                                <select @change="lessonCategoryData" v-model="form.category_id" class="form-control form-control-sm">
                                    <option value="">[ Pilih]</option>
                                    <option v-for="(category, index) in categories" :key="index" :value="category.id">
                                        {{ category.name }}</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-2 mb-1">
                                <label for="lesson_category_id">Kategori Mata Pelajaran</label>
                                <select v-model="form.lesson_category_id" class="form-control form-control-sm">
                                    <option value="">[ Pilih ]</option>
                                    <option v-for="lessonCategory in form.lessonCategories" :value="lessonCategory.id">{{ lessonCategory.name }}</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-1">
                                <label for="category">Waktu</label>
                                <select v-model="form.order" class="form-select form-select-sm">
                                    <option value="">[ Pilih]</option>
                                    <option value="DESC">Terbaru</option>
                                    <option value="ASC">Terlama</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-2 mb-1">
                                <label for="end_date">Action</label><br>
                                <div class="input-group">
                                    <button @click.prevent="handleSearch" class="btn btn-primary btn-sm">
                                        <i class="bx bx-filter"></i>Filter
                                    </button>

                                    <Link href="/admin/exam-groups" class="btn btn-danger btn-sm">
                                        <i class="bx bx-refresh"></i>reset
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Tryout</h6>
                        </div>
                        <div class="ms-auto">
                            <Link href="/admin/exam-groups/create" class="btn btn-primary btn-sm me-2">
                                <i class="bx bxs-plus-square"></i>Tambah Tryout
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:10pt;">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Peminatan</th>
                                    <th>Kategori Mata Pelajaran</th>
                                    <th>Judul Tryout</th>
                                    <th>Jumlah Tes</th>
                                    <th>Peserta</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(exam, index) in examGroups.data" :key="index">
                                    <td>{{ ++index + (examGroups.current_page - 1) * examGroups.per_page }}</td>
                                    <td><span class="badge bg-primary">{{ exam.category.name }}</span></td>
                                    <td><span class="badge bg-success">{{ exam.lesson_category.name }}</span></td>
                                    <td>{{ exam.title }}</td>
                                    <td>{{ exam.exam_count }}</td>
                                    <td>
                                        <span class="badge bg-danger" v-if="exam.exam_group_user_count == 0">{{ exam.exam_group_user_count }}</span>
                                        <span class="badge bg-primary" v-else>{{ exam.exam_group_user_count }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-warning': exam.exam_status === 'inprogress', 'btn-danger': exam.exam_status === 'inactive', 'btn-primary': exam.exam_status === 'active'}">{{ capitalizeWords(exam.exam_status) }}</button>
                                            <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-warning': exam.exam_status === 'inprogress', 'btn-danger': exam.exam_status === 'inactive', 'btn-primary': exam.exam_status === 'active', 'split-bg-warning': exam.exam_status === 'inprogress', 'split-bg-danger': exam.exam_status === 'inactive', 'split-bg-primary': exam.exam_status === 'active'}" class="dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">	<span class="visually-hidden">Toggle Dropdown</span></button>
                                            <ul class="dropdown-menu">
                                                <li v-if="exam.exam_status !== 'active'"><Link class="dropdown-item" :href="`/admin/exam-groups/${exam.id}/change-exam-status?exam_status=active`">Active</Link></li>
                                                <li v-if="exam.exam_status !== 'inactive'"><Link class="dropdown-item" :href="`/admin/exam-groups/${exam.id}/change-exam-status?exam_status=inactive`">Inactive</Link></li>
                                                <li v-if="exam.exam_status !== 'inprogress'"><Link class="dropdown-item" :href="`/admin/exam-groups/${exam.id}/change-exam-status?exam_status=inprogress`">In Progress</Link></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
											<button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true">Action</button>
											<ul class="dropdown-menu" data-popper-placement="top-start" data-popper-reference-hidden="" data-popper-escaped="">
												<li><Link class="dropdown-item" :href="`/admin/exam-groups/${exam.id}/students`">Nilai Peserta</Link></li>
												<li><Link class="dropdown-item" :href="`/admin/exam-groups/${exam.id}/exam-group-details`">Detail</Link></li>
                                                <li><Link class="dropdown-item" :href="`/admin/exam-groups/${exam.id}/edit`">Edit</Link></li>
                                                <li><a class="dropdown-item" href="#" @click.prevent="destroy(exam.id)">Hapus</a></li>
											</ul>
										</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="9" v-if="!examGroups.data.length">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="examGroups.links" align="end" />
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

    import { reactive } from 'vue';

    //import ref from vue
    import {
        ref
    } from 'vue';

    //import axios
    import axios from 'axios';

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
            examGroups: Object,
            categories: Object,
        },

        // initialization composition API
        setup() {
            // define state search
            const form = reactive({
                search: ref("" || (new URL(document.location)).searchParams.get('search')),
                category_id: ref((new URL(document.location)).searchParams.get('category_id') || ''),
                lesson_category_id: ref((new URL(document.location)).searchParams.get('lesson_category_id') || ''),
                order: ref((new URL(document.location)).searchParams.get('order') || ''),

                lessonCategories: [],
            });

            const lessonCategoryData = (category_id = "", lesson_category_id = "") => {
                form.lesson_category_id = category_id != form.category_id ? '' : form.lesson_category_id;
                form.lesson_id = lesson_category_id != form.lesson_category_id ? '' : form.lesson_id;
            
                axios.get(`/admin/categories/${form.category_id}/lesson-categories`).then(response => {
                    form.lessonCategories = response.data;
                }).catch(error => console.error(error));
            }

            // define state search
            const handleSearch = () => {
                Inertia.get('/admin/exam-groups', {
                    search: form.search,
                    category_id: form.category_id,
                    lesson_category_id: form.lesson_category_id,
                    order: form.order,
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

                        Inertia.delete(`/admin/exam-groups/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Tryout Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            if(form.category_id) {
                lessonCategoryData(form.category_id, form.lesson_category_id);
            }

            return {
                form,
                handleSearch,
                destroy,
                lessonCategoryData,
            }
        }
    }
</script>
