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
                            <li class="breadcrumb-item active" aria-current="page">Daftar Peserta Tryout</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div v-if="$page.props.session.success" class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-white"><i class='bx bxs-check-circle'></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-white">Sukses</h6>
                        <div class="text-white">{{ $page.props.session.success }}</div>
                    </div>
                </div>
            </div>

            <div v-if="$page.props.session.error" class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-white">Gagal</h6>
                        <div class="text-white">{{ $page.props.session.error }}</div>
                    </div>
                </div>
                
            </div>
            
            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-md-12 mb-2">
                            <form @submit.prevent="handleSearch">
                                <div class="position-relative">
                                    <input
                                        type="text"
                                        v-model="search"
                                        class="form-control form-control-sm ps-5 radius-20"
                                        placeholder="Cari Peserta...."
                                        size="40"
                                        maxlength="100"
                                    >
                                    <span class="position-absolute top-50 product-show translate-middle-y">
                                        <i class="bx bx-search"></i>
                                    </span>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-2 col-md-6 mb-2">
                            <a @click.prevent="destroyExamResult(exam_group_id)" class="btn btn-danger btn-sm w-100">Hapus Semua Hasil</a>
                        </div>

                        <div class="col-lg-2 col-md-6 mb-2">
                            <div class="dropdown">
                                <button class="btn btn-success w-100 dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" target="_blank" :href="`/admin/exam-groups/${exam_group_id}/export-excel`">Export Hasil ke Excel</a></li>
                                    <li><a class="dropdown-item" target="_blank" :href="`/admin/exam-groups/${exam_group_id}/export-pdf`">Export Hasil ke PDF</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-2">
                            <a @click.prevent="regenerateAllTryoutScore(exam_group_id)" class="btn btn-primary btn-sm w-100">Hitung Ulang Seluruh Nilai</a>
                        </div>

                        <div class="col-lg-1 col-md-6">
                            <Link href="/admin/exam-groups" class="btn btn-primary w-100 btn-sm">Kembali</Link>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:10pt;">
                            <thead class="table-light">
                                <tr>
                                    <th>Peringkat</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'code')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'province_id')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'city_id')?.translate }}</th>
                                    <th>Status Pengerjaan</th>
                                    <th>Nilai</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(exam, index) in examGroupUsers.data" :key="index">
                                    <td>{{ ++index + (examGroupUsers.current_page - 1) * examGroupUsers.per_page }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ exam.user.code ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ exam.user.name ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')">{{ exam.user.student ? (exam.user.student.province ? exam.user.student.province.name : '-') : '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')">{{ exam.user.student ? (exam.user.student.city ? exam.user.student.city.name : '-') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-danger" v-if="exam.is_finished == 0">Belum Selesai Mengerjakan</span>
                                        <span class="badge bg-primary" v-else>Selesai Mengerjakan</span>
                                    </td>
                                    <td>{{ gradeFormat(exam.grade) }}</td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <a target="_blank" :href="`/admin/exam-groups/${exam.exam_group_id}/students/${exam.id}/export-pdf`" class="ms-1"><i class='bx bx-file'></i></a>
                                            <Link :href="`/admin/exam-groups/${exam.exam_group_id}/students/${exam.id}/grades`" class="ms-1"><i class='bx bx-grid-alt'></i></Link>
                                            <a href="#" @click.prevent="regenerateTryoutScore(exam.exam_group_id, exam.id)" class="ms-1"><i class='bx bx-refresh'></i></a>
                                            <a href="#" @click.prevent="destroy(exam.id)" class="ms-1"><i class="bx bxs-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="9" v-if="!examGroupUsers.data.length">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="examGroupUsers.links" align="end" />
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
            examGroupUsers: Object,
            exam_group_id: Object,
        },

        // initialization composition API
        setup(props) {

            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get(`/admin/exam-groups/${props.exam_group_id}/students`, {
                    //send params "search" with value from state "search"
                    search: search.value,
                })
            }

            const destroyExamResult = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Jika Tryout dihapus, maka anda tidak akan dapat mengembalikannya!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/exam-groups/${id}/delete-exam-group-result`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Nilai Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
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

                        Inertia.delete(`/admin/exam-groups/${id}/exam-group-users`);

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

            const regenerateTryoutScore = (examGroupId, examId) => {
                Swal.fire({
                    title: 'Konfirmasi Hitung Ulang Nilai',
                    text: "Hitung ulang nilai akan berpengaruh pada nilai yang sudah ada.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hitung Ulang'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.get(`/admin/exam-groups/${examGroupId}/students/${examId}/regenerate-scores`);

                        Swal.fire({
                            title: 'Success!',
                            text: 'Hitung Ulang Berhasil.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            const regenerateAllTryoutScore = (examGroupId) => {
                Swal.fire({
                    title: 'Konfirmasi Hitung Ulang Seluruh Nilai',
                    text: "Jika hanya mengganti beberapa silakan cari peserta dan lakukan hitung ulang. Menghitung ulang seluruh nilai harus di pastikan hanya dilakukan dalam kondisi tertentu, karena menghitung ulang seluruh nilai membutuhkan waktu yang lama dan dapat mempengaruhi performa server Anda.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hitung Ulang'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.get(`/admin/exam-groups/${examGroupId}/students/regenerate-all-scores`);

                        Swal.fire({
                            title: 'Success!',
                            text: 'Hitung Ulang Berhasil.',
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
                destroy,
                destroyExamResult,
                regenerateTryoutScore,
                regenerateAllTryoutScore
            }
        }
    }
</script>
