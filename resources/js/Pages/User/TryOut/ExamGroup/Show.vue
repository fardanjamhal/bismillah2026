<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tryout</title>
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
                            <li class="breadcrumb-item active" aria-current="page">Detail Tryout</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-12">
                    <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                        <div v-html="$page.props.session.error"></div>
                    </div>
                    <div v-if="$page.props.session.success" class="alert alert-success border-0">
                        <div v-html="$page.props.session.success"></div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white" data-bs-toggle="collapse" href="#collapseTryOutGroupInformation" aria-expanded="false" @click="toggleCollapseTryOutGroupInformation">
                                Informasi Tryout
                                <a class="float-end" data-bs-toggle="collapse" href="#collapseTryOutGroupInformation" aria-expanded="false">
                                    <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutGroupInformation, 'bx bx-chevron-up': !collapseTryOutGroupInformation }"></i>
                                </a>
                            </h5>
                        </div>
                        <div class="collapse" :class="{ 'show': collapseTryOutGroupInformation, 'fade in': true}" id="collapseTryOutGroupInformation">
                            <div class="card-body">
                                <div class="ck-content" v-html="examGroup.description"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white" data-bs-toggle="collapse" href="#collapseTryOutGroupDescription" aria-expanded="false" @click="toggleCollapseTryOutGroupDescription">
                                Deskripsi Tryout
                                <a class="float-end" data-bs-toggle="collapse" href="#collapseTryOutGroupDescription" aria-expanded="false">
                                    <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutGroupDescription, 'bx bx-chevron-up': !collapseTryOutGroupDescription }"></i>
                                </a>
                            </h5>
                        </div>
                        <div class="collapse" :class="{ 'show': collapseTryOutGroupDescription, 'fade in': true}" id="collapseTryOutGroupDescription">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <tbody>
                                            <!-- <tr>
                                                <th>Nama</th>
                                                <td>:</td>
                                                <td>{{ $page.props.auth.user.name }}</td>
                                            </tr>
                                            <tr>
                                                <th width="300px">Email</th>
                                                <td width="2px">:</td>
                                                <td>{{ $page.props.auth.user.email ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Username</th>
                                                <td>:</td>
                                                <td>{{ $page.props.auth.user.username ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">&nbsp;</td>
                                            </tr> -->
                                            <tr>
                                                <th width="300px">Peminatan</th>
                                                <td width="2px">:</td>
                                                <td><span class="badge bg-primary">{{ examGroup.category.name }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>Sub peminatan khusus untuk</th>
                                                <td>:</td>
                                                <td>
                                                    <div v-if="examGroup.sub_categories.length">
                                                        <div v-for="(sub_category, index) in examGroup.sub_categories" :key="index" style="display: inline;">
                                                            <span>{{ sub_category.name }}</span>
                                                            <span v-if="index < examGroup.sub_categories.length - 1">, </span>
                                                        </div>
                                                    </div>
                                                    <div v-else>
                                                        <span>Seluruh Peminatan {{ examGroup.category.name }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Kategori Mata Pelajaran</th>
                                                <td>:</td>
                                                <td><span class="badge bg-success">{{ examGroup.lesson_category.name }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>Judul Tryout</th>
                                                <td>:</td>
                                                <td>{{ examGroup.title }}</td>
                                            </tr>
                                            <tr v-if="$page.props.auth.user.member_type == 2">
                                                <th>Untuk Kategori Member</th>
                                                <td>:</td>
                                                <td>
                                                    <div v-if="examGroup.member_categories.length">
                                                        <div v-for="(member_category, index) in examGroup.member_categories" :key="index" style="display: inline;">
                                                            <span class="badge bg-success mx-1">{{ member_category.name }}</span>
                                                        </div>
                                                    </div>
                                                    <div v-else>
                                                        <span class="badge bg-success">Seluruh Member & Non Member</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-if="examGroup.exam_start_time">
                                                <th>Waktu Akses Mulai Tryout</th>
                                                <td>:</td>
                                                <td>{{ formatDateWithTimeHourMinute(examGroup.exam_start_time) }}</td>
                                            </tr>
                                            <tr v-if="examGroup.exam_end_time">
                                                <th>Waktu Berakhir Akses Tryout</th>
                                                <td>:</td>
                                                <td>{{ formatDateWithTimeHourMinute(examGroup.exam_end_time) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white" data-bs-toggle="collapse" href="#collapseTryOutGroupExam" aria-expanded="false" @click="toggleCollapseTryOutGroupExam">
                                Detail Soal
                                <a class="float-end" data-bs-toggle="collapse" href="#collapseTryOutGroupExam" aria-expanded="false">
                                    <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutGroupExam, 'bx bx-chevron-up': !collapseTryOutGroupExam }"></i>
                                </a>
                            </h5>
                        </div>
                        <div class="collapse"  :class="{ 'show': collapseTryOutGroupExam, 'fade in': true}" id="collapseTryOutGroupExam">
                            <div class="card-body">
                                <Link :href="`/user/exam-groups/histories`" class="btn btn-success btn-sm float-end">Riwayat Tryout</Link>

                                <table class="table mb-0" v-if="examGroup.exam_group_type == 2">
                                    <tr>
                                        <td align="center" colspan="3" v-if="!examGroup.exam.length">Data Tidak Tersedia</td>
                                    </tr>
                                    <tbody v-for="(exam, index) in examGroup.exam" :key="index">
                                        <tr>
                                            <th>Judul</th>
                                            <td width="2px">:</td>
                                            <td>{{ exam.title }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Soal</th>
                                            <td>:</td>
                                            <td>{{ exam.question_selection_mode == 2 ? exam.number_of_questions : exam.question_title.question_count }} Soal</td>
                                        </tr>
                                        <tr>
                                            <th>Durasi</th>
                                            <td>:</td>
                                            <td>{{ exam.duration }} Menit {{ exam.question_title.total_section > 1 ? '/ Kolom (total '+ exam.question_title.total_section +' Kolom)' : '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>:</td>
                                            <td>
                                                <div class="text-left" v-if="!exam.grade[0]">
                                                    <span class="badge bg-warning text-dark">Belum Mengerjakan</span>
                                                </div>
                                                <div class="text-left" v-else-if="exam.grade[0] && exam.grade[0].is_finished == 0">
                                                    <span class="badge bg-warning text-dark">Belum selesai mengerjakan</span>
                                                </div>
                                                <div class="text-left" v-else-if="exam.grade[0] && exam.grade[0].is_finished == 1">
                                                    <span class="badge bg-primary">Sudah Mengerjakan</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Action</th>
                                            <td>:</td>
                                            <td>
                                                <span class="badge bg-warning text-dark" v-if="exam.exam_status == 'inprogress' || examGroup.exam_status == 'inprogress'">In Progress</span>
                                                <span class="badge bg-warning text-dark" v-if="exam.exam_status == 'inactive' || examGroup.exam_status == 'inactive'">Inactive</span>
                                                <div v-if="exam.exam_status == 'active' && examGroup.exam_status == 'active'">
                                                    <div class="text-left" v-if="!exam.grade[0]">
                                                        <div v-if="examGroup.exam_start_time || examGroup.exam_end_time">
                                                            <Link v-if="isExamActive($page.props.setting.timezone)" :href="`/user/exams/${exam.id}/exam-start`" class="btn btn-sm btn-primary">Mulai Kerjakan</Link>
                                                            <span v-else class="badge bg-danger">Tryout Tidak Bisa di Akses</span>
                                                        </div>
                                                        <div v-else>
                                                            <Link :href="`/user/exams/${exam.id}/exam-start`" class="btn btn-sm btn-primary">Mulai Kerjakan</Link>
                                                        </div>
                                                    </div>
                                                    <div class="text-left" v-else-if="exam.grade[0] && exam.grade[0].is_finished == 0">
                                                        <a href="#" @click.prevent="repeatExam()" class="btn btn-sm btn-secondary m-1" v-if="exam.repeat_the_exam == 2">Ulangi</a>
                                                        <Link :href="`/user/exams/${exam.id}/exam-start`" class="btn btn-sm btn-warning">Lanjut Mengerjakan</Link>
                                                    </div>
                                                    <div class="text-left" v-else-if="exam.grade[0] && exam.grade[0].is_finished == 1">
                                                        <a href="#" @click.prevent="repeatExam(exam.id)" class="btn btn-sm btn-secondary m-1" v-if="exam.repeat_the_exam == 1 || exam.repeat_the_exam == 2">Ulangi</a>
                                                        <Link :href="`/user/grades/${exam.grade[0].id}`" class="btn btn-sm btn-success m-1">Lihat Nilai</Link>
                                                        <Link :href="`/user/grades/${exam.grade[0].id}/questions`" v-if="exam.show_answer_discussion == 1" class="btn btn-sm btn-primary m-1">Pembahasan</Link>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="3">&nbsp;</th>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table mb-0" v-else>
                                    <tr>
                                        <td align="center" colspan="3" v-if="!examGroup.exam.length">Data Tidak Tersedia</td>
                                    </tr>
                                    
                                    <tbody v-for="(exam, index) in examGroup.exam" :key="index">
                                        <tr>
                                            <th>No</th>
                                            <td width="2px">:</td>
                                            <td>{{ index + 1 }}</td>
                                        </tr>
                                        <tr>
                                            <th>Judul</th>
                                            <td>:</td>
                                            <td>{{ exam.title }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Soal</th>
                                            <td>:</td>
                                            <td>{{ exam.question_selection_mode == 2 ? exam.number_of_questions : exam.question_title.question_count }} Soal</td>
                                        </tr>
                                        <tr v-if="examGroupUser && examGroupUser.is_finished == 1 && exam.show_answer_discussion == 1">
                                            <th>Action</th>
                                            <td>:</td>
                                            <td>
                                                <div class="text-left">
                                                    <Link :href="`/user/grades/${exam.grade[0].id}/questions`" v-if="exam.show_answer_discussion == 1" class="btn btn-sm btn-primary px-5">Pembahasan</Link>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="3">&nbsp;</th>
                                        </tr>
                                    </tbody>

                                    <tbody>
                                        <tr>
                                            <th>Durasi</th>
                                            <td>:</td>
                                            <td>{{ examGroup.duration }} Menit</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>:</td>
                                            <td>
                                                <div class="text-left" v-if="!examGroupUser">
                                                    <span class="badge bg-warning text-dark">Belum Mengerjakan</span>
                                                </div>
                                                <div class="text-left" v-else-if="examGroupUser && examGroupUser.is_finished == 0">
                                                    <span class="badge bg-warning text-dark">Belum selesai mengerjakan</span>
                                                </div>
                                                <div class="text-left" v-else-if="examGroupUser && examGroupUser.is_finished == 1">
                                                    <span class="badge bg-primary">Sudah Mengerjakan</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Action</th>
                                            <td>:</td>
                                            <td>
                                                <span class="badge bg-warning text-dark" v-if="examGroup.exam_status == 'inprogress'">In Progress</span>
                                                <span class="badge bg-warning text-dark" v-if="examGroup.exam_status == 'inactive'">Inactive</span>
                                                <div v-if="examGroup.exam_status == 'active'">
                                                    <div class="text-left" v-if="!examGroupUser">
                                                        <div v-if="examGroup.exam_start_time || examGroup.exam_end_time">
                                                            <Link v-if="isExamActive($page.props.setting.timezone)" :href="`/user/exam-groups/${examGroup.id}/exam-start`" class="btn btn-sm btn-primary">Mulai Kerjakan</Link>
                                                            <span v-else class="badge bg-danger">Tryout Tidak Bisa di Akses</span>
                                                        </div>
                                                        <div v-else>
                                                            <Link :href="`/user/exam-groups/${examGroup.id}/exam-start`" class="btn btn-sm btn-primary px-5">Mulai Kerjakan</Link>
                                                        </div> 
                                                    </div>
                                                    <div class="text-left" v-else-if="examGroupUser && examGroupUser.is_finished == 0">
                                                        <a href="#" @click.prevent="repeatExam()" class="btn btn-sm btn-secondary m-1" v-if="examGroup.repeat_the_exam == 2">Ulangi</a>
                                                        <Link :href="`/user/exam-groups/${examGroup.id}/exam-start`" class="btn btn-sm btn-warning px-5">Lanjut Mengerjakan</Link>
                                                    </div>
                                                    <div class="text-left" v-else-if="examGroupUser && examGroupUser.is_finished == 1">
                                                        <a href="#" @click.prevent="repeatExamGroup(examGroup.id)" class="btn btn-sm btn-secondary m-1" v-if="examGroup.repeat_the_exam == 1 || examGroup.repeat_the_exam == 2">Ulangi</a>
                                                        <Link :href="`/user/exam-groups/histories/${examGroupUser.id}`" class="btn btn-sm btn-success m-1">Lihat Nilai</Link>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    import moment from 'moment-timezone';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { ref } from 'vue';

    //import sweet alert2
    import Swal from 'sweetalert2';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
        },

        // props
        props: {
            examGroup: Object,
            lessonCategory: Object,
            examGroupUser: Object,
        },
        setup(props) {
            const collapseTryOutGroupInformation = ref(localStorage.getItem('collapseTryOutGroupInformation') !== null ? JSON.parse(localStorage.getItem('collapseTryOutGroupInformation')) : true);
            const collapseTryOutGroupDescription = ref(localStorage.getItem('collapseTryOutGroupDescription') !== null ? JSON.parse(localStorage.getItem('collapseTryOutGroupDescription')) : true);
            const collapseTryOutGroupExam = ref(localStorage.getItem('collapseTryOutGroupExam') !== null ? JSON.parse(localStorage.getItem('collapseTryOutGroupExam')) : true);

            const toggleCollapseTryOutGroupInformation = () => {
                collapseTryOutGroupInformation.value = !collapseTryOutGroupInformation.value;
                localStorage.setItem('collapseTryOutGroupInformation', JSON.stringify(collapseTryOutGroupInformation.value));
            }

            const toggleCollapseTryOutGroupDescription = () => {
                collapseTryOutGroupDescription.value = !collapseTryOutGroupDescription.value;
                localStorage.setItem('collapseTryOutGroupDescription', JSON.stringify(collapseTryOutGroupDescription.value));
            }

            const toggleCollapseTryOutGroupExam = () => {
                collapseTryOutGroupExam.value = !collapseTryOutGroupExam.value;
                localStorage.setItem('collapseTryOutGroupExam', JSON.stringify(collapseTryOutGroupExam.value));
            }

            const repeatExam = (id) => {
                Swal.fire({
                    title: 'Konfirmasi Ulangi Pengerjaan',
                    text: "Mengulangi Ujian Dapat Mempengaruhi Nilai Anda.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ulangi Ujian'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        localStorage.setItem("myAnswers", []);
                        Inertia.get(`/user/exams/${id}/exam-start?repeat=1`);

                        Swal.fire({
                            title: 'Success!',
                            text: 'Silakan Untuk Mengerjakan Kembali.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            const repeatExamGroup = (id) => {
                Swal.fire({
                    title: 'Konfirmasi Ulangi Pengerjaan',
                    text: "Mengulangi Ujian Dapat Mempengaruhi Nilai Anda.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ulangi Ujian'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        localStorage.setItem("myAnswers", []);
                        Inertia.get(`/user/exam-groups/${id}/exam-start?repeat=1`);

                        Swal.fire({
                            title: 'Success!',
                            text: 'Silakan Untuk Mengerjakan Kembali.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            const isExamActive = (timezone) => {
                const now = moment().tz(timezone);
                const startTime = moment(props.examGroup.exam_start_time);
                const endTime = moment(props.examGroup.exam_end_time);
                return (
                    (props.examGroup.exam_start_time && now.isSameOrAfter(startTime) && props.examGroup.exam_end_time && now.isSameOrBefore(endTime)) ||
                    (props.examGroup.exam_start_time && now.isSameOrAfter(startTime) && (props.examGroup.exam_end_time == null || props.examGroup.exam_end_time == undefined)) ||
                    (props.examGroup.exam_end_time && now.isSameOrBefore(endTime) && (props.examGroup.exam_start_time == null || props.examGroup.exam_start_time == undefined))
                );
            }

            return {
                repeatExam,
                repeatExamGroup,

                collapseTryOutGroupInformation,
                collapseTryOutGroupDescription,
                collapseTryOutGroupExam,

                toggleCollapseTryOutGroupInformation,
                toggleCollapseTryOutGroupDescription,
                toggleCollapseTryOutGroupExam,

                isExamActive
            }
        }
    }
</script>
