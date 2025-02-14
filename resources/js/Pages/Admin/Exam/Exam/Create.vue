<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tambah Latihan Soal</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Latihan Soal</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Latihan Soal</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link href="/admin/exams" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2 my-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Kategori Peminatan</label>
                            <select @change="lessonCategoryData" v-model="form.category_id" :class="{ 'is-invalid': errors.category_id }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.name }}</option>
                            </select>
                            <div v-if="errors.category_id" class="invalid-feedback">
                                {{ errors.category_id }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Sub Kategori Peminatan</label>
                            <multiselect
                                v-model="form.sub_category_ids"
                                :options="form.subCategories"
                                :multiple="true"
                                label="name"
                                :close-on-select="true"
                                :allow-empty="true"
                                track-by="id"
                                placeholder="[ Pilih ]"
                            ></multiselect>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Kategori Member<i><b>(Khusus untuk Membership Bulanan, jika dikosongkan maka akan menjadi gratis)</b></i></label>
                            <multiselect
                                v-model="form.member_category_ids"
                                :options="memberCategories"
                                :multiple="true"
                                label="name"
                                :close-on-select="true"
                                :allow-empty="true"
                                track-by="id"
                                placeholder="[ Pilih ]"
                            ></multiselect>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Kategori Mata Pelajaran</label>
                            <select @change="lessonData" v-model="form.lesson_category_id" :class="{ 'is-invalid': errors.lesson_category_id }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option v-for="lessonCategory in form.lessonCategories" :value="lessonCategory.id">{{ lessonCategory.name }}</option>
                            </select>
                            <div v-if="errors.lesson_category_id" class="invalid-feedback">
                                {{ errors.lesson_category_id }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mata Pelajaran</label>
                            <select @change="questionTitleData" v-model="form.lesson_id" :class="{ 'is-invalid': errors.lesson_id }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option v-for="lesson in form.lessons" :value="lesson.id">{{ lesson.name }}</option>
                            </select>
                            <div v-if="errors.lesson_id" class="invalid-feedback">
                                {{ errors.lesson_id }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Paket Soal</label>
                            <select v-model="form.question_title_id" :class="{ 'is-invalid': errors.question_title_id }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option v-for="questionTitle in form.questionTitles" :value="questionTitle.id">{{ questionTitle.name }}</option>
                            </select>
                            <div v-if="errors.question_title_id" class="invalid-feedback">
                                {{ errors.question_title_id }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Piih Mode Pengambilan Soal</label>
                            <select v-model="form.question_selection_mode" :class="{ 'is-invalid': errors.question_selection_mode }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Semua Soal</option>
                                <option value="2">Sebagian Soal</option>
                            </select>
                            <div v-if="errors.question_selection_mode" class="invalid-feedback">
                                {{ errors.question_selection_mode }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.question_selection_mode == 2">
                            <label class="form-label">Jumlah Soal Yang Diambil</label>
                            <input type="number" class="form-control" v-model="form.number_of_questions" :class="{ 'is-invalid': errors.number_of_questions }" placeholder="Jumlah Soal Yang Diambil">
                            <div v-if="errors.number_of_questions" class="invalid-feedback">
                                {{ errors.number_of_questions }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Judul Latihan Soal</label>
                            <input type="text" class="form-control" v-model="form.title" :class="{ 'is-invalid': errors.title }" placeholder="Judul Latihan Soal">
                            <div v-if="errors.title" class="invalid-feedback">
                                {{ errors.title }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi Latihan Soal</label>
                            <ckeditor :editor="editor" v-model="form.description" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.title }"></ckeditor>
                            <div v-if="errors.description">
                                {{ errors.description }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Durasi Latihan Soal (Dalam Menit Per Sesi atau Per Kolom)</label>
                            <input type="number" class="form-control" v-model="form.duration" :class="{ 'is-invalid': errors.duration }" placeholder="Durasi">
                            <div v-if="errors.duration" class="invalid-feedback">
                                {{ errors.duration }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Soal Acak</label>
                            <select v-model="form.random_question" :class="{ 'is-invalid': errors.random_question }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            <div v-if="errors.random_question" class="invalid-feedback">
                                {{ errors.random_question }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Jawaban Acak</label>
                            <select v-model="form.random_answer" :class="{ 'is-invalid': errors.random_answer }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            <div v-if="errors.random_answer" class="invalid-feedback">
                                {{ errors.random_answer }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tampilkan Jawaban Pilihan Ganda</label>
                            <select v-model="form.show_answer" :class="{ 'is-invalid': errors.show_answer }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            <div v-if="errors.show_answer" class="invalid-feedback">
                                {{ errors.show_answer }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tampilkan Navigasi Nomor Soal</label>
                            <select v-model="form.show_question_number_navigation" :class="{ 'is-invalid': errors.show_question_number_navigation }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            <div v-if="errors.show_question_number_navigation" class="invalid-feedback">
                                {{ errors.show_question_number_navigation }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tampilkan Nomor Soal</label>
                            <select v-model="form.show_question_number" :class="{ 'is-invalid': errors.show_question_number }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            <div v-if="errors.show_question_number" class="invalid-feedback">
                                {{ errors.show_question_number }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Pertanyaan Selanjutnya Secara Otomatis</label>
                            <select v-model="form.next_question_automatically" :class="{ 'is-invalid': errors.next_question_automatically }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            <div v-if="errors.next_question_automatically" class="invalid-feedback">
                                {{ errors.next_question_automatically }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tampilkan Button Sebelum & Selanjutnya</label>
                            <select v-model="form.show_prev_next_button" :class="{ 'is-invalid': errors.show_prev_next_button }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                            <div v-if="errors.show_prev_next_button" class="invalid-feedback">
                                {{ errors.show_prev_next_button }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tipe Pilihan Ganda</label>
                            <select v-model="form.type_option" :class="{ 'is-invalid': errors.type_option }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Button Option dan Soal Menyatu (Normal)</option>
                                <option value="2">Button Option ke Samping dan jawaban menyatu dengan soal (kecermatan)</option>
                            </select>
                            <div v-if="errors.type_option" class="invalid-feedback">
                                {{ errors.type_option }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tampilan Button Akhiri Ujian / Sesi Selanjutnya</label>
                            <select v-model="form.button_type_finish" :class="{ 'is-invalid': errors.button_type_finish }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Ditampilkan Pada Setiap Soal</option>
                                <option value="2">Hanya Ditampilkan Pada Soal Terakhir</option>
                                <option value="3">Tidak Ditampilkan & Menunggu Waktu Ujian Selesai</option>
                            </select>
                            <div v-if="errors.button_type_finish" class="invalid-feedback">
                                {{ errors.button_type_finish }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Peserta Dapat Mengulangi Ujian</label>
                            <select v-model="form.repeat_the_exam" :class="{ 'is-invalid': errors.repeat_the_exam }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="0">Peserta Tidak Dapat Mengulangi Ujian</option>
                                <option value="1">Peserta Dapat Mengulangi Ujian Ketika Sudah Selesai Mengerjakan</option>
                                <option value="2">Peserta Dapat Mengulangi Ujian Walau Belum Menyelesaikan Ujian</option>
                            </select>
                            <div v-if="errors.repeat_the_exam" class="invalid-feedback">
                                {{ errors.repeat_the_exam }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Peserta Dapat Melihat Ranking Ujian</label>
                            <select v-model="form.show_ranking_exam" :class="{ 'is-invalid': errors.show_ranking_exam }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                            <div v-if="errors.show_ranking_exam" class="invalid-feedback">
                                {{ errors.show_ranking_exam }}
                            </div>
                        </div>

                        <div class="col-12" v-if="$page.props.setting.block_system_type == 1">
                            <label class="form-label">Maksimal Toleransi Keluar Tes</label>
                            <input type="number" class="form-control" v-model="form.total_tolerance" :class="{ 'is-invalid': errors.total_tolerance }" placeholder="Maksimal Toleransi Keluar Tes" min="0">
                            <div v-if="errors.total_tolerance" class="invalid-feedback">
                                {{ errors.total_tolerance }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tampilkan Koreksi Jawaban & Pembahasan</label>
                            <select v-model="form.show_answer_discussion" :class="{ 'is-invalid': errors.show_answer_discussion }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                            <div v-if="errors.show_answer_discussion" class="invalid-feedback">
                                {{ errors.show_answer_discussion }}
                            </div>
                        </div>
                      
                        <div class="col-12">
                            <label class="form-label">Tipe Harga</label>
                            <select v-model="form.price_type" :class="{ 'is-invalid': errors.price_type }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Gratis</option>
                                <option value="2">Berbayar</option>
                            </select>
                            <div v-if="errors.price_type" class="invalid-feedback">
                                {{ errors.price_type }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.price_type == 2">
                            <label class="form-label">Harga Latihan Soal Sebelum Diskon</label>
                            <input type="number" class="form-control" v-model="form.price_before_discount" :class="{ 'is-invalid': errors.price_before_discount }" placeholder="Harga Latihan Soal Sebelum Diskon" min="0">
                            <div v-if="errors.price_before_discount" class="invalid-feedback">
                                {{ errors.price_before_discount }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.price_type == 2">
                            <label class="form-label">Harga Latihan Soal Sesudah Diskon</label>
                            <input type="number" class="form-control" v-model="form.price_after_discount" :class="{ 'is-invalid': errors.price_after_discount }" placeholder="Harga Latihan Soal Sesudah Diskon" min="0">
                            <div v-if="errors.price_after_discount" class="invalid-feedback">
                                {{ errors.price_after_discount }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Waktu Akses Mulai Ujian (Optional)</label>
                            <input type="datetime-local" class="form-control" v-model="form.exam_start_time" :class="{ 'is-invalid': errors.exam_start_time }" placeholder="Waktu Mulai Ujian">
                            <div v-if="errors.exam_start_time" class="invalid-feedback">
                                {{ errors.exam_start_time }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Waktu Berakhir Akses Ujian (Optional)</label>
                            <input type="datetime-local" class="form-control" v-model="form.exam_end_time" :class="{ 'is-invalid': errors.exam_end_time }" placeholder="Waktu Selesai Ujian">
                            <div v-if="errors.exam_end_time" class="invalid-feedback">
                                {{ errors.exam_end_time }}
                            </div>
                        </div>
    
                        <div class="col-12">
                            <label class="form-label">Status Latihan Soal</label>
                            <select v-model="form.exam_status" :class="{ 'is-invalid': errors.exam_status }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="active">Aktive</option>
                                <option value="inactive">Inactive</option>
                                <option value="inprogress">Inprogress</option>
                            </select>
                            <div v-if="errors.exam_status" class="invalid-feedback">
                                {{ errors.exam_status }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tanggal Rilis Latihan Soal</label>
                            <input type="datetime-local" class="form-control" v-model="form.release_date" :class="{ 'is-invalid': errors.release_date }" placeholder="Tanggal Rilis">
                            <div v-if="errors.release_date" class="invalid-feedback">
                                {{ errors.release_date }}
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
                        </div>
                    </form>
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

    import Multiselect from '@suadelabs/vue3-multiselect'

    //import reactive
    import { reactive } from 'vue';

    // import Swal
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    import { 
        ClassicEditor,
        Essentials, 
        Alignment,
        Bold, 
        Italic, 
        Font, 
        Paragraph, 
        BlockQuote, 
        List, ListProperties,
        Heading ,
        Strikethrough, Subscript, Superscript, Underline,
        AutoLink,
        TodoList,
        Indent,
        Image, ImageCaption, ImageStyle, ImageToolbar, LinkImage, ImageInsert, SimpleUploadAdapter, ImageResizeEditing, ImageResizeHandles, ImageResize,
        CodeBlock,
        Table, TableToolbar, TableCellProperties, TableProperties,
    } from 'ckeditor5';

    import MathType from '@wiris/mathtype-ckeditor5/dist/index.js';

    //import axios
    import axios from 'axios';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Multiselect
        },

        data() {
            return {
                editor: ClassicEditor, 
                editorData: '<p>Initial content</p>',
                editorConfig: {
                    plugins: [
                        Essentials, 
                        Alignment,
                        Bold, 
                        Italic, 
                        Font, 
                        Paragraph, 
                        BlockQuote, 
                        List, ListProperties,
                        Heading ,
                        Strikethrough, Subscript, Superscript, Underline,
                        AutoLink,
                        TodoList,
                        Indent,
                        Image, ImageCaption, ImageStyle, ImageToolbar, LinkImage, ImageInsert, SimpleUploadAdapter, ImageResizeEditing, ImageResizeHandles, ImageResize,
                        CodeBlock,
                        Table, TableToolbar, TableCellProperties, TableProperties,
                        MathType
                    ],
                    toolbar: {
                        items: [ 
                            'undo', 'redo','|',
                            'heading','|',
                            'alignment', 'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor','|',
                            'link', 'insertTable', 'insertImage', 'blockQuote', 'codeBlock','|',
                            'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent', '|',
                            'bold', 'italic', 'strikethrough', 'subscript', 'superscript','|',
                            'MathType', 'ChemType'
                        ],
                        shouldNotGroupWhenFull: false
                    },
                    list: {
                        properties: {
                            styles: true,
                            startIndex: true,
                            reversed: true
                        }
                    },
                    image: {
                        insert: {
                            integrations: [ 'upload', 'url' ],
                            type: 'auto'
                        },
                        toolbar: [
                            'imageStyle:inline','imageStyle:alignLeft','imageStyle:alignRight','|',
                            'imageStyle:alignBlockLeft','imageStyle:block','imageStyle:alignBlockRight','|',
                            'toggleImageCaption','imageTextAlternative','|',
                            'linkImage', 'resizeImage',
                        ],
                        resizeOptions: [
                            {name: 'resizeImage:original',value: null,icon: 'original'},
                            {name: 'resizeImage:custom',value: 'custom',icon: 'custom'},
                            {name: 'resizeImage:25',value: '25',icon: 'medium'},
                            {name: 'resizeImage:50',value: '50',icon: 'medium'},
                            {name: 'resizeImage:75',value: '75',icon: 'large'},
                            {name: 'resizeImage:100',value: '100',icon: 'large'}
                        ],
                        shouldInsertWidthHeightAttributes: false,
                    },
                    simpleUpload: {
                        uploadUrl: '/upload',
                        withCredentials: true,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Authorization': 'Bearer <JSON Web Token>'
                        },
                    },
                    table: {
                        contentToolbar: [
                            'tableColumn', 'tableRow', 'mergeTableCells',
                            'tableProperties', 'tableCellProperties'
                        ],
                        tableProperties: {},
                        tableCellProperties: {}
                    },
                    alignment: {
                        options: [ 'left', 'right', 'center', 'justify']
                    },
                    fontSize: {
                        options: [11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 36, 48, 72
                    ],
                    supportAllValues: true
                    },
                    language: 'en'
                }
            }
        },

        //props
        props: {
            errors: Object,
            categories: Object,
            memberCategories: Object
        },

        // initialization composition API
        setup() {
            const form = reactive({
                category_id: '',
                lesson_category_id: '',
                lesson_id: '',
                question_title_id: '',
                question_selection_mode: 1,
                number_of_questions: '',
                title: '',
                description: '',
                duration: '',
                random_question: '',
                random_answer: '',
                show_answer: '',
                show_question_number_navigation: '',
                show_question_number: '',
                next_question_automatically: '',
                show_prev_next_button: '',
                type_option: '',
                button_type_finish: '',
                access_type: '',
                repeat_the_exam: '',
                show_ranking_exam: '',
                exam_start_time: '',
                exam_end_time: '',
                exam_status: '',
                release_date: '',
                show_answer_discussion: '',
                total_tolerance: '',
                price_type: '',
                price_before_discount: '',
                price_after_discount: '',

                member_category_ids: [],
                sub_category_ids: [],

                // get form API
                lessonCategories: [],
                lessons: [],
                questionTitles: [],
                subCategories: []
            });

            const lessonCategoryData = () => {
                form.lesson_category_id = '';
                form.lesson_id = '';
                form.question_title_id = '';
                
                axios.get(`/admin/categories/${form.category_id}/lesson-categories`).then(response => {
                form.lessonCategories = response.data;
                }).catch(error => console.error(error));

                axios.get(`/admin/categories/${form.category_id}/sub-categories`).then(response => {
                form.sub_category_ids = [];
                form.subCategories = response.data;
                }).catch(error => console.error(error));
            }

            const lessonData = () => {
                form.lesson_id = '';
                form.question_title_id = '';
                axios.get(`/admin/lesson-categories/${form.lesson_category_id}/lessons`).then(response => {
                form.lessons = response.data;
                }).catch(error => console.error(error));
            }

            const questionTitleData = () => {
                form.question_title_id = '';
                axios.get(`/admin/lessons/${form.lesson_id}/question-titles`).then(response => {
                form.questionTitles = response.data;
                }).catch(error => console.error(error));
            }

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post('/admin/exams', {
                    // data
                    category_id: form.category_id,
                    lesson_category_id: form.lesson_category_id,
                    lesson_id: form.lesson_id,
                    question_title_id: form.question_title_id,
                    question_selection_mode: form.question_selection_mode,
                    number_of_questions: form.number_of_questions,
                    title: form.title,
                    description: form.description,

                    duration: form.duration,
                    random_question: form.random_question,
                    random_answer: form.random_answer,
                    show_answer: form.show_answer,
                    show_question_number_navigation: form.show_question_number_navigation,
                    show_question_number: form.show_question_number,
                    next_question_automatically: form.next_question_automatically,
                    show_prev_next_button: form.show_prev_next_button,
                    type_option: form.type_option,
                    button_type_finish: form.button_type_finish,

                    access_type: form.access_type,
                    repeat_the_exam: form.repeat_the_exam,
                    show_ranking_exam: form.show_ranking_exam,
                    show_answer_discussion: form.show_answer_discussion,
                    exam_start_time: form.exam_start_time,
                    exam_end_time: form.exam_end_time,
                    exam_status: form.exam_status,
                    release_date: form.release_date,
                    total_tolerance: form.total_tolerance,
                    price_type: form.price_type,
                    price_before_discount: form.price_before_discount,
                    price_after_discount: form.price_after_discount,
                    sub_category_ids: form.sub_category_ids,
                    member_category_ids: form.member_category_ids

                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Latihan Soal Berhasil Disimpan!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
                lessonCategoryData,
                lessonData,
                questionTitleData
            }
        }
    }
</script>

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>

<style>
/* Add your styles here */
.ck-editor__editable_inline {
    min-height: 350px;
}

.ck.ck-dropdown__panel {
	max-height: 240px; /* or anything else, more likely ~300px or so */
	overflow-y: auto;
}
</style>
