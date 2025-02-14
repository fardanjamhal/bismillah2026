<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Detail Course</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Detail Course</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Detail Course</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="row">
                        <!-- Search Form -->
                        <div class="col-lg-6 col-md-6">
                            <form @submit.prevent="handleSearch">
                                <div class="position-relative mb-1">
                                    <input
                                        type="text"
                                        v-model="search"
                                        class="form-control ps-5 radius-20"
                                        placeholder="Cari Berdasarkan Judul Section Course..."
                                        size="40"
                                        maxlength="100"
                                    >
                                    <span class="position-absolute top-50 product-show translate-middle-y">
                                        <i class="bx bx-search"></i>
                                    </span>
                                </div>
                            </form>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-lg-6 col-md-6">
                            <div class="d-lg-flex align-items-center gap-3">
                                <div class="ms-auto">
                                    <Link href="/admin/courses" class="btn btn-primary btn-sm mx-3">
                                        Kembali
                                    </Link>
                                    <Link 
                                        :href="`/admin/courses/${course.id}/course-sections/create`" 
                                        class="btn btn-primary btn-sm"
                                    >
                                        <i class="bx bxs-plus-square"></i> Tambah Section
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--end breadcrumb-->
            
            <div v-if="courseSections.data.length > 0" class="card mb-3 shadow-none border border-1" v-for="(courseSection, index) in courseSections.data" :key="index">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="ms-auto">
                            <button @click.prevent="destroyCourseSection(courseSection.id)" class="btn btn-danger btn-sm">Hapus Section</button>
                            <Link :href="`/admin/courses/${course.id}/course-sections/${courseSection.id}/edit`" class="btn btn-warning btn-sm mx-1">Edit Section</Link>
                            <Link :href="`/admin/courses/${course.id}/course-details/create?course_section_id=${courseSection.id}`" class="btn btn-primary btn-sm">Tambah Detail Course</Link>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="6"><h6>Section {{ courseSection.order }} :  {{ courseSection.title }}</h6></th>
                                </tr>
                                <tr>
                                    <th width="20">No</th>
                                    <th>Judul</th>
                                    <th width="100">Urutan</th>
                                    <th width="100">Intro</th>
                                    <th width="100">Status</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="courseSection.course_detail.length > 0" v-for="(courseDetail, index) in courseSection.course_detail" :key="index">
                                    <td>
                                        {{ ++index }} 
                                    </td>
                                    <td>{{ courseDetail.title }}</td>
                                    <td>{{ courseDetail.order }}</td>
                                    <td>
                                        <span v-if="courseDetail.intro == 1" class="badge bg-success">Opened</span>
                                        <span v-else class="badge bg-danger">Locked</span>
                                    </td>
                                    <td>
                                            <div class="btn-group">
                                                <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-danger': courseDetail.is_active == 0, 'btn-primary': courseDetail.is_active == 1}">{{ courseDetail.is_active == 1 ? 'active' : 'inactive' }}</button>
                                                <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-danger': courseDetail.is_active == 0, 'btn-primary': courseDetail.is_active == 1, 'split-bg-danger': courseDetail.is_active == 0, 'split-bg-primary': courseDetail.is_active == 1}" class="dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">	<span class="visually-hidden">Toggle Dropdown</span></button>
                                                <ul class="dropdown-menu">
                                                    <li v-if="courseDetail.is_active != 1"><Link class="dropdown-item" :href="`/admin/courses/${course.id}/course-details/${courseDetail.id}/change-course-detail-status?is_active=1`">Active</Link></li>
                                                    <li v-if="courseDetail.is_active != 0"><Link class="dropdown-item" :href="`/admin/courses/${course.id}/course-details/${courseDetail.id}/change-course-detail-status?is_active=0`">Inactive</Link></li>
                                                </ul>
                                            </div>
                                        </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true">Action</button>
                                            <ul class="dropdown-menu" data-popper-placement="top-start" data-popper-reference-hidden="" data-popper-escaped="">
                                                <li><Link class="dropdown-item" :href="`/admin/courses/${course.id}/course-details/${courseDetail.id}`">Detail</Link></li>
                                                <li><Link class="dropdown-item" :href="`/admin/courses/${course.id}/course-details/${courseDetail.id}/edit`">Edit</Link></li>
                                                <li><a class="dropdown-item" href="#" @click.prevent="destroyCourseDetail(courseDetail.id)">Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td colspan="6" align="center">Detail Course Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div v-else class="row row-cols-12 row-cols-md-12 row-cols-lg-12 row-cols-xl-12">
                <div class="col">
                    <div class="card border-bottom border-3 border-0">
                        <div class="card-body">
                            <h6 class="card-title text-center">Data Section atau Detail Course Tidak Tersedia</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4" v-if="courseSections.data.length > 0">
                <Pagination :links="courseSections.links" align="end" />
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
            course: Object,
            courseSections: Object,
        },

        // initialization composition API
        setup(props) {
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get(`/admin/courses/${props.course.id}/course-details`, {
                    search: search.value,
                })
            }

            const destroyCourseDetail = (id) => {
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

                        Inertia.delete(`/admin/courses/${props.course.id}/course-details/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Detail Course Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            const destroyCourseSection = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Ketika Anda menghapus section, detail course yang ada dalam section tersebut akan terhapus secara otomatis. Pastikan detail course sudah dipindahkan ke section lain.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/courses/${props.course.id}/course-sections/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Section Course Berhasil Dihapus!.',
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
                destroyCourseDetail,
                destroyCourseSection
            }
        }
    }
</script>
