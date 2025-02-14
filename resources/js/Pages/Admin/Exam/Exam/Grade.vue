<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Riwayat Tryout</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="d-flex justify-content-between">
                <div class="ms-start">
                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Riwayat Tryout</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Detail Riwayat Latihan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="ms-auto mb-3">
                    <Link v-if="exam.exam_group_id" :href="`/admin/exam-groups/${exam.exam_group_id}/exam-group-details/${exam.id}`" class="btn btn-primary btn-sm">Kembali</Link>
                    <Link v-else :href="`/admin/exams/${exam.id}`" class="btn btn-primary btn-sm">Kembali</Link>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">
                        Informasi Tryout
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="3">Informasi Peserta</th>
                                </tr>
                                <tr>
                                    <td width="300px">Nama</td>
                                    <td width="10px">:</td>
                                    <td>{{ grade.user.name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{ grade.user.email }}</td>
                                </tr>
                                
                                <tr>
                                    <th colspan="3">Informasi Latihan Soal</th>
                                </tr>
                                <tr>
                                    <td>Peminatan</td>
                                    <td>:</td>
                                    <td><span class="badge bg-primary">{{ grade.category.name }}</span></td>
                                </tr>
                                <tr>
                                    <td>Kategori Mata Pelajaran</td>
                                    <td>:</td>
                                    <td>{{ grade.lesson_category.name }}</td>
                                </tr>
                                <tr>
                                    <td>Judul Ujian</td>
                                    <td>:</td>
                                    <td>{{ grade.exam.title }}</td>
                                </tr>
                                <tr>
                                    <td>Mata Pelajaran</td>
                                    <td>:</td>
                                    <td>
                                        {{ grade.lesson.name }}
                                        <span v-if="grade.is_finished == 1">
                                            <br>
                                            <Link :href="`/admin/exams/${exam.id}/grades/${grade.id}/answer-corrections`"><span class="badge bg-warning text-dark">Klik untuk pembahasan</span></Link>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Durasi</td>
                                    <td>:</td>
                                    <td>{{ grade.exam.duration }} Menit {{ grade.exam.question_title.total_section > 1 ? '/ Kolom' : '' }}</td>
                                </tr>
                                <tr>
                                    <td>Waktu Mulai</td>
                                    <td>:</td>
                                    <td>{{ formatDateWithTime(grade.start_time) }}</td>
                                </tr>
                                <tr>
                                    <td>Waktu Selesai</td>
                                    <td>:</td>
                                    <td>
                                        <span v-if="grade.is_finished == 1">
                                            {{ formatDateWithTime(grade.end_time) }}
                                        </span>
                                        <span v-else v-html="'<span class=\'badge bg-warning text-dark\'>Belum selesai mengerjakan</span>'"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nilai</td>
                                    <td>:</td>
                                    <td><h5>{{ gradeFormat(grade.grade) }}</h5></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card" v-if="grade.grade_details && grade.exam.question_title.add_value_category == 1 && ( grade.exam.question_title.assessment_type == 1 || grade.exam.question_title.assessment_type == 2)">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">
                        Detail Nilai Per Kategori
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>ASPEK PENILAIAN</th>
                                    <th v-if="grade.exam.question_title.total_section == 1">TOTAL BENAR</th>
                                    <th v-if="grade.exam.question_title.total_section == 1">TOTAL SALAH</th>
                                    <th>KATEGORI</th>
                                    <th>SKOR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="grade.grade_details.length" v-for="(gradeDetail, index) in grade.grade_details" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td>{{ gradeDetail.grade_category_name }}</td>
                                    <td v-if="grade.exam.question_title.total_section == 1">
                                        <Link :href="`/admin/exams/${exam.id}/grades/${grade.id}/answer-corrections?valueCategoryId=${gradeDetail.value_category_id}&isCorrect=Y`">{{ gradeDetail.total_correct }}</Link>
                                    </td>
                                    <td v-if="grade.exam.question_title.total_section == 1">
                                        <Link :href="`/admin/exams/${exam.id}/grades/${grade.id}/answer-corrections?valueCategoryId=${gradeDetail.value_category_id}&isCorrect=N`">{{ gradeDetail.total_wrong }}</Link>
                                    </td>
                                    <td>{{ gradeDetail.grade_category }}</td>
                                    <td>
                                        <span v-if="grade.lesson.id == '4b1978c5-420c-454b-ba69-bd1be91ea40d'"></span>
                                        <span v-else>{{ gradeDetail.grade < 0 ? 0 : gradeDetail.grade }}</span>
                                    </td>
                                </tr>
                                <tr v-if="grade.grade_details.length">
                                    <th v-if="grade.exam.question_title.total_section == 1" colspan="5">TOTAL SKOR</th>
                                    <th v-else colspan="3">TOTAL SKOR</th>
                                    <th>{{ gradeFormat(grade.final_score) }}</th>
                                </tr>
                                <tr v-if="grade.grade_details.length">
                                    <th v-if="grade.exam.question_title.total_section == 1" colspan="5">KONVERSI NILAI AKHIR</th>
                                    <th v-else colspan="3">KONVERSI NILAI AKHIR</th>
                                    <th>{{ gradeFormat(grade.grade) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card" v-if="grade.exam.question_title.total_section > 1 && grade.total_correct_per_section">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">
                        Grafik Ketahanan
                    </h5>
                </div>
                <div class="card-body">
                    <apexchart :width="chart.width" :height="chart.height" :type="chart.type" :options="chart.options" :series="chart.series"></apexchart>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">
                        Peringkat Peserta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'code')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'province_id')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'city_id')?.translate }}</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(rankingExam, index) in rankingExams.data" :key="index">
                                    <td><span class="badge bg-primary">{{ ++index + (rankingExams.current_page - 1) * rankingExams.per_page }}</span></td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ rankingExam.user.code ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ rankingExam.user.name ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')">{{ rankingExam.user.student && rankingExam.user.student.province ? rankingExam.user.student.province.name :  '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')">{{ rankingExam.user.student && rankingExam.user.student.city ? rankingExam.user.student.city.name :  '-' }}</td>
                                    <th>{{ gradeFormat(rankingExam.grade) }}</th>
                                </tr>
                            </tbody>
                        </table>
                        <Pagination :links="rankingExams.links" align="end" />
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

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';
    
    import { ref } from 'vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

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
            grade: Object,
            rankingExams: Object,
            chart: Object,
            exam: Object,
            answers: Object,
        }
    }
</script>
