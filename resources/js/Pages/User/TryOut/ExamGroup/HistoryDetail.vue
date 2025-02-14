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
                            <li class="breadcrumb-item active" aria-current="page">Detail Riwayat Tryout</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="text-start">
                            <h5 class="mb-0">Informasi Tryout</h5>
                        </div>
                        <div class="ms-auto">
                            <Link href="/user/exam-groups/histories" class="btn btn-primary btn-sm">Kembali</Link>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered">
                            <tbody>
                                <tr>
                                    <th width="210px">Peminatan</th>
                                    <td width="10px">:</td>
                                    <td><span class="badge bg-primary">{{ history.exam_group.category.name }}</span></td>
                                </tr>
                                <tr>
                                    <th>Kategori Mata Pelajaran</th>
                                    <td>:</td>
                                    <td>{{ history.exam_group.lesson_category.name }}</td>
                                </tr>
                                <tr>
                                    <th>Judul Tryout</th>
                                    <td>:</td>
                                    <td>{{ history.exam_group.title }}</td>
                                </tr>
                                <tr>
                                    <th :rowspan="history.exam_group.grade.length + 1">Mata Pelajaran</th>
                                    <td :rowspan="history.exam_group.grade.length + 1">:</td>
                                </tr>
                                <tr v-for="(grade, index) in history.exam_group.grade" :key="index">
                                    <td>
                                        {{ grade.lesson.name }}
                                        <br>
                                        <Link :href="`/user/grades/${grade.id}/questions`" v-if="grade.exam.show_answer_discussion == 1"><span class="badge bg-warning text-dark">Klik untuk pembahasan</span></Link>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Mengerjakan</th>
                                    <td>:</td>
                                    <td>{{ formatDate(history.created_at) }}</td>
                                </tr>
                                <tr>
                                    <th>Status Ujian</th>
                                    <td>:</td>
                                    <td>
                                        <span class="badge bg-danger" v-if="history.is_finished == 0">Belum Selesai Mengerjakan</span>
                                        <span class="badge bg-primary" v-else>Selesai Mengerjakan</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary" v-if="history.is_finished == 1 && history.passed != null">
                <div class="card-body">
                    <div class="text-center">
                        <div class="alert border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2" :class="{'alert-success': history.passed == 1, 'alert-danger': history.passed == 0}">
                            <h3 class="mb-3">Nilai Akhir Tryout</h3>
                            <h3>{{ gradeFormat(history.grade) }}</h3>
                            <h4>{{ history.description }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="text-start">
                            <h5 class="mb-0">Detail Penilaian</h5>
                        </div>
                        <div class="ms-auto" v-if="history.exam_group.certificate_print_user == 1">
                            <a target="_blank" :href="`/user/exam-groups/${history.id}/export-pdf`" class="btn btn-danger btn-sm m-1">Cetak Hasil Tryout</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered">
                            <thead>
                                <tr style="font-weight: bold;">
                                    <td width="10%">No</td>
                                    <td width="35%">Kategori Penilaian</td>
                                    <td width="25%"> {{ history.exam_group.minimal_grade_type == 1 ? 'Ambang Batas' : 'Keterangan' }}</td>
                                    <td width="5%" align="right">Nilai</td>
                                </tr>
                            </thead>
                            <template v-for="(grade, index) in history.exam_group.grade" :key="index">
                                <tbody>
                                    <tr v-if="grade.exam.question_title.assessment_type == 1 || grade.exam.question_title.assessment_type == 2">
                                        <th colspan="5" style="background:#eee;"><h6>{{ grade.lesson.name }}</h6></th>
                                    </tr>

                                    <tr v-if="history.exam_group.assessment_type != 4">
                                        <td>{{ ++index }}</td>
                                        <td>{{ grade.lesson.name }}</td>
                                        <td>{{ history.exam_group.assessment_type == 2 ? grade.exam.percentage_grade + '%' : grade.exam.question_title.passing_grade }}</td>
                                        <td align="right">{{ gradeFormat(grade.grade) }}</td>
                                    </tr>

                                    <tr v-if="history.exam_group.assessment_type == 4 && grade.grade_details" v-for="(gradeDetail, index) in grade.grade_details" :key="index">
                                        <td>{{ ++index }}</td>
                                        <td>{{ gradeDetail.grade_category_name }}</td>
                                        <td>{{ gradeDetail.grade_category }}</td>
                                        <td align="right">{{ gradeFormat(gradeDetail.grade) }}</td>
                                    </tr>
                                </tbody>
                            </template>
                            <tfoot v-if="history.is_finished == 1">
                                <tr>
                                    <th colspan="3"><h5>Nilai Akhir</h5></th>
                                    <td align="right">
                                        <h5>{{ gradeFormat(history.grade) }}</h5>
                                    </td> 
                                </tr>
                                <tr v-if="history.exam_group.minimal_grade_type != 0">
                                    <th colspan="3"><h5>Keterangan</h5></th>
                                    <td colspan="2" align="right">
                                        <h5>{{ history.description ?? '-' }}</h5>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>   

            <div class="card" v-if="history.exam_group.show_ranking_exam == 1">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">
                        Nilai Peserta
                    </h5>
                </div>
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
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">No</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')" rowspan="2" class="text-center align-middle">
                                        {{ $page.props.setting.authentication_field.find(field => field.name == 'code')?.translate }}
                                    </th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')" rowspan="2" class="text-center align-middle">
                                        {{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}
                                    </th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')" rowspan="2" class="text-center align-middle">
                                        {{ $page.props.setting.authentication_field.find(field => field.name == 'province_id')?.translate }}
                                    </th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')" rowspan="2" class="text-center align-middle">
                                        {{ $page.props.setting.authentication_field.find(field => field.name == 'city_id')?.translate }}
                                    </th>
                                    <th :colspan="allExams.length" class="text-center align-middle">Detail Nilai</th> 
                                    <th rowspan="2" class="text-center align-middle">Nilai Akhir</th>
                                    <th rowspan="2" class="text-center align-middle">Keterangan</th>
                                </tr>
                                <tr>
                                    <th v-for="exam in allExams" :key="exam.id" class="text-center align-middle">
                                        {{ exam.lesson ? exam.lesson.short_name : '-' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(grade, index) in grades.data" :key="index">
                                    <td class="text-center"><span class="badge bg-primary">{{ ++index + (grades.current_page - 1) * grades.per_page }}</span></td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ grade.user.code ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ grade.user.name ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')">{{ grade.user.student ? (grade.user.student.province ? grade.user.student.province.name : '-') : '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')">{{ grade.user.student ? (grade.user.student.city ? grade.user.student.city.name : '-') : '-' }}</td>
                                    <td class="text-center" v-for="exam in allExams" :key="'exam_' + exam.id">
                                        {{ gradeFormat(grade.user.grade.find(g => g.lesson_id === exam.lesson_id)?.grade) ?? '-' }}
                                    </td>
                                    <td class="text-center">{{ gradeFormat(grade.grade) }}</td>
                                    <td class="text-center">{{ grade.description ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="15" v-if="!grades.data.length">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>

                        <Pagination :links="grades.links" align="end" />
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

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    //import ref from vue
    import { ref } from 'vue';

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
            history: Object,
            gradeCount: Number,
            grades: Number,
            allExams: Object,
        },
        // initialization composition API
        setup(props) {

            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get(`/user/exam-groups/histories/${props.history.id}`, {
                    //send params "search" with value from state "search"
                    search: search.value,
                })
            }

            return {
                search,
                handleSearch,
            }
        }
    }
</script>
