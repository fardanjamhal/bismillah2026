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
                    <Link v-if="grade.exam_group_id" :href="`/user/exam-groups/${grade.category_id}/lesson-categories/${grade.lesson_category_id}/exams/${grade.exam_group_id}`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                    <Link v-else href="/user/grades" class="btn btn-primary btn-sm">Kembali</Link>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white" data-bs-toggle="collapse" href="#collapseTryOutInformation" aria-expanded="false" @click="toggleCollapseTryOutInformation">
                        Informasi Tryout
                        <a class="float-end" data-bs-toggle="collapse" href="#collapseTryOutInformation" aria-expanded="false">
                            <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutInformation, 'bx bx-chevron-up': !collapseTryOutInformation }"></i>
                        </a>
                    </h5>
                </div>
                <div class="collapse" :class="{ 'show': collapseTryOutInformation, 'fade in': true}"  id="collapseTryOutInformation">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="220px">Peminatan</th>
                                        <td width="10px">:</td>
                                        <td><span class="badge bg-primary">{{ grade.category.name }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Kategori Mata Pelajaran</th>
                                        <td>:</td>
                                        <td>{{ grade.lesson_category.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Judul Ujian</th>
                                        <td>:</td>
                                        <td>{{ grade.exam.title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <td>:</td>
                                        <td>
                                            {{ grade.lesson.name }}
                                            <br>
                                            <Link :href="`/user/grades/${grade.id}/questions`" v-if="grade.exam.show_answer_discussion == 1"><span class="badge bg-warning text-dark">Klik untuk pembahasan</span></Link>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Durasi</th>
                                        <td>:</td>
                                        <td>{{ grade.exam.duration }} Menit</td>
                                    </tr>
                                    <tr>
                                        <th>Waktu Mulai</th>
                                        <td>:</td>
                                        <td>{{ formatDateWithTime(grade.start_time) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Waktu Selesai</th>
                                        <td>:</td>
                                        <td>{{ formatDateWithTime(grade.end_time) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nilai</th>
                                        <td>:</td>
                                        <td><h5>{{ gradeFormat(grade.grade) }}</h5></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" v-if="grade.grade_details && grade.exam.question_title.add_value_category == 1 && ( grade.exam.question_title.assessment_type == 1 || grade.exam.question_title.assessment_type == 2)">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white" data-bs-toggle="collapse" href="#collapseTryOutDetailValueByKategory" aria-expanded="false" @click="toggleCollapseTryOutDetailValueByKategory">
                        Detail Nilai Per Kategori
                        <a class="float-end" data-bs-toggle="collapse" href="#collapseTryOutDetailValueByKategory" aria-expanded="false">
                            <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutDetailValueByKategory, 'bx bx-chevron-up': !collapseTryOutDetailValueByKategory }"></i>
                        </a>
                    </h5>
                </div>
                <div class="collapse" :class="{ 'show': collapseTryOutDetailValueByKategory, 'fade in': true}" id="collapseTryOutDetailValueByKategory">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>ASPEK PENILAIAN</th>
                                        <th v-if="grade.exam.question_title.total_section == 1">TOTAL BENAR</th>
                                        <th v-if="grade.exam.question_title.total_section == 1">TOTAL SALAH</th>
                                        <th v-if="grade.grade_details.some(item => 'rs' in item)">RS</th>
                                        <th v-if="grade.grade_details.some(item => 'ws' in item)">WS</th>
                                        <th>KATEGORI</th>
                                        <th>SKOR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(gradeDetail, index) in grade.grade_details" :key="index">
                                        <td>{{ ++index }}</td>
                                        <td>{{ gradeDetail.grade_category_name }}</td>
                                        <td v-if="grade.exam.question_title.total_section == 1">
                                            <Link v-if="grade.exam.show_answer_discussion == 1 && gradeDetail.total_correct > 0" :href="`/user/grades/${grade.id}/questions?valueCategoryId=${gradeDetail.value_category_id}&isCorrect=Y`">{{ gradeDetail.total_correct }}</Link>
                                            <div v-else>{{ gradeDetail.total_correct }}</div>
                                        </td>
                                        <td v-if="grade.exam.question_title.total_section == 1">
                                            <Link v-if="grade.exam.show_answer_discussion == 1 && gradeDetail.total_wrong > 0" :href="`/user/grades/${grade.id}/questions?valueCategoryId=${gradeDetail.value_category_id}&isCorrect=N`">{{ gradeDetail.total_wrong }}</Link>
                                            <div v-else>{{ gradeDetail.total_wrong }}</div>
                                        </td>
                                        <td v-if="grade.grade_details.some(item => 'rs' in item)">{{ gradeDetail.rs ?? '-' }}</td>
                                        <td v-if="grade.grade_details.some(item => 'ws' in item)">{{ gradeDetail.ws ?? '-' }}</td>
                                        <td>{{ gradeDetail.grade_category }}</td>
                                        <td>
                                            <span>{{ gradeDetail.grade < 0 ? 0 : gradeDetail.grade }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th :colspan="computedColspan">TOTAL SKOR</th>
                                        <th>{{ grade.final_score }}</th>
                                    </tr>
                                    <tr>
                                        <th :colspan="computedColspan">KONVERSI NILAI AKHIR</th>
                                        <th>{{ gradeFormat(grade.grade) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" v-else>
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white" data-bs-toggle="collapse" href="#collapseTryOutDetailValueByKategory" aria-expanded="false" @click="toggleCollapseTryOutDetailValueByKategory">
                        Detail Nilai
                        <a class="float-end" data-bs-toggle="collapse" href="#collapseTryOutDetailValueByKategory" aria-expanded="false">
                            <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutDetailValueByKategory, 'bx bx-chevron-up': !collapseTryOutDetailValueByKategory }"></i>
                        </a>
                    </h5>
                </div>
                <div class="collapse" :class="{ 'show': collapseTryOutDetailValueByKategory, 'fade in': true}" id="collapseTryOutDetailValueByKategory">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>MATA PELAJARAN</th>
                                        <th v-if="grade.exam.question_title.assessment_type != 4">BENAR</th>
                                        <th v-if="grade.exam.question_title.assessment_type != 4">SALAH</th>
                                        <th>SKOR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ grade.lesson.name }}</td>
                                        <td v-if="grade.exam.question_title.assessment_type != 4">
                                            <Link v-if="grade.exam.show_answer_discussion == 1 && grade.total_correct > 0" :href="`/user/grades/${grade.id}/questions?isCorrect=Y`">{{ grade.total_correct }}</Link>
                                            <div v-else>{{ grade.total_correct }}</div>
                                        </td>
                                        <td v-if="grade.exam.question_title.assessment_type != 4">
                                            <Link v-if="grade.exam.show_answer_discussion == 1 && grade.total_wrong > 0" :href="`/user/grades/${grade.id}/questions?isCorrect=N`">{{ grade.total_wrong }}</Link>
                                            <div v-else>{{ grade.total_wrong }}</div>
                                        </td>
                                        <td>{{ gradeFormat(grade.grade) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" v-if="grade.exam.question_title.total_section > 1 && grade.total_correct_per_section">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white" data-bs-toggle="collapse" href="#collapseTryOutGraphicBySection" aria-expanded="false" @click="toggleCollapseTryOutGraphicBySection">
                        Grafik Ketahanan
                        <a class="float-end" data-bs-toggle="collapse" href="#collapseTryOutGraphicBySection" aria-expanded="false">
                            <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutGraphicBySection, 'bx bx-chevron-up': !collapseTryOutGraphicBySection }"></i>
                        </a>
                    </h5>
                </div>
                <div class="collapse" :class="{ 'show': collapseTryOutGraphicBySection, 'fade in': true}" id="collapseTryOutGraphicBySection">
                    <div class="card-body">
                        <apexchart :width="chart.width" :height="chart.height" :type="chart.type" :options="chart.options" :series="chart.series"></apexchart>
                    </div>
                </div>
            </div>

            <div class="card" v-if="(grade.exam && grade.exam.show_ranking_exam == 1) || (grade.exam_group && grade.exam_group.show_ranking_exam == 1)">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white" data-bs-toggle="collapse" href="#collapseTryOutRanking" aria-expanded="false" @click="toggleCollapseTryOutRanking">
                        Nilai Peserta
                        <a class="float-end" data-bs-toggle="collapse" href="#collapseTryOutRanking" aria-expanded="false">
                            <i class="text-white btn btn-danger btn-sm" :class="{ 'bx bx-chevron-down': collapseTryOutRanking, 'bx bx-chevron-up': !collapseTryOutRanking }"></i>
                        </a>
                    </h5>
                </div>
                <div class="collapse" :class="{ 'show': collapseTryOutRanking, 'fade in': true}" id="collapseTryOutRanking">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="text-start">
                                <form @submit.prevent="handleSearch">
                                    <div class="position-relative">
                                        <input
                                            type="text"
                                            v-model="search"
                                            class="form-control ps-5 radius-20"
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
                        </div>
                        <hr>
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
                                    <tr>
                                    <td align="center" colspan="7" v-if="!rankingExams.data.length">Data Tidak Tersedia</td>
                                </tr>
                                </tbody>
                            </table>

                            <Pagination :links="rankingExams.links" align="end" />

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

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';
    
    import { ref } from 'vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    import { Inertia } from '@inertiajs/inertia';

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
            answers: Object,
        },
        setup(props) {
            const collapseTryOutInformation = ref(localStorage.getItem('collapseTryOutInformation') !== null ? JSON.parse(localStorage.getItem('collapseTryOutInformation')) : true);
            const collapseTryOutDetailValueByKategory = ref(localStorage.getItem('collapseTryOutDetailValueByKategory') !== null ? JSON.parse(localStorage.getItem('collapseTryOutDetailValueByKategory')) : true);
            const collapseTryOutGraphicBySection = ref(localStorage.getItem('collapseTryOutGraphicBySection') !== null ? JSON.parse(localStorage.getItem('collapseTryOutGraphicBySection')) : true);
            const collapseTryOutRanking = ref(localStorage.getItem('collapseTryOutRanking') !== null ? JSON.parse(localStorage.getItem('collapseTryOutRanking')) : true);

            const toggleCollapseTryOutInformation = () => {
                collapseTryOutInformation.value = !collapseTryOutInformation.value;
                localStorage.setItem('collapseTryOutInformation', JSON.stringify(collapseTryOutInformation.value));
            }

            const toggleCollapseTryOutDetailValueByKategory = () => {
                collapseTryOutDetailValueByKategory.value = !collapseTryOutDetailValueByKategory.value;
                localStorage.setItem('collapseTryOutDetailValueByKategory', JSON.stringify(collapseTryOutDetailValueByKategory.value));
            }

            const toggleCollapseTryOutGraphicBySection = () => {
                collapseTryOutGraphicBySection.value = !collapseTryOutGraphicBySection.value;
                localStorage.setItem('collapseTryOutGraphicBySection', JSON.stringify(collapseTryOutGraphicBySection.value));
            }

            const toggleCollapseTryOutRanking = () => {
                collapseTryOutRanking.value = !collapseTryOutRanking.value;
                localStorage.setItem('collapseTryOutRanking', JSON.stringify(collapseTryOutRanking.value));
            }

            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get(`/user/grades/${props.grade.id}`, {
                    search: search.value,
                })
            }

            return {
                collapseTryOutInformation,
                collapseTryOutDetailValueByKategory,
                collapseTryOutGraphicBySection,
                collapseTryOutRanking,

                toggleCollapseTryOutInformation,
                toggleCollapseTryOutDetailValueByKategory,
                toggleCollapseTryOutGraphicBySection,
                toggleCollapseTryOutRanking,
                search,
                handleSearch
            }
        },
        computed: {
            computedColspan() {
                let colspan = this.grade.exam.question_title.total_section > 1 ? 3 : 5;

                if (this.grade.grade_details.some(item => 'rs' in item)) {
                    colspan += 1;
                }

                if (this.grade.grade_details.some(item => 'ws' in item)) {
                    colspan += 1;
                }

                return colspan;
            }
        }
    }
</script>
