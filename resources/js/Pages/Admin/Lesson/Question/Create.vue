<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tambah Soal</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Soal</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Soal</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

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
                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link :href="`/admin/question-titles/${questionTitle.id}/questions`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div v-if="questionTitle.add_value_category == 1 && questionTitle.assessment_type == 1" class="col-12">
                            <label class="form-label">Kategori Penilaian</label>
                            <select v-model="form.value_category_id" :class="{ 'is-invalid': errors.value_category_id }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option v-for="(valueCategory, index) in valueCategories" :key="index" :value="valueCategory.id">
                                    {{ valueCategory.name }}
                                </option>
                            </select>
                            <div v-if="errors.value_category_id" class="invalid-feedback">
                                {{ errors.value_category_id }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Bagian Ke</label>
                            <input type="text" class="form-control" v-model="form.section" :class="{ 'is-invalid': errors.section }" placeholder="Bagian Ke" :readonly="questionTitle.total_section == 1">
                            <div v-if="errors.section" class="invalid-feedback">
                                {{ errors.section }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tambahkan Audio</label>
                            <select v-model="form.audio_input" :class="{ 'is-invalid': errors.audio_input }" class="form-select">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                            <div v-if="errors.audio_input" class="invalid-feedback">
                                {{ errors.audio_input }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.audio_input == 1">
                            <label class="form-label">Audio</label>
                            <input type="file" class="form-control" @input="form.audio = $event.target.files[0]" :class="{ 'is-invalid': errors.audio }" placeholder="Audio">
                            <div v-if="errors.audio" class="invalid-feedback">
                                {{ errors.audio }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.audio_input == 1">
                            <label class="form-label">Batasan Audio Bisa Diputar</label>
                            <input type="number" class="form-control" v-model="form.audio_played_limit" :class="{ 'is-invalid': errors.audio_played_limit }" placeholder="Batasan Audio Bisa Diputar">
                            <div v-if="errors.audio_played_limit" class="invalid-feedback">
                                {{ errors.audio_played_limit }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.audio_input == 1">
                            <label class="form-label">Batas Waktu Menjawab (Detik), Input 0 Jika Tidak Ada Batasan</label>
                            <input type="number" class="form-control" v-model="form.audio_answer_time" :class="{ 'is-invalid': errors.audio_answer_time }" placeholder="Batas Waktu Menjawab Sesudah Audio Di Putar">
                            <div v-if="errors.audio_answer_time" class="invalid-feedback">
                                {{ errors.audio_answer_time }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><b>Soal</b></label>
                            <ckeditor :editor="editor" v-model="form.question" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.question }"></ckeditor>
                            <div v-if="errors.question">
                                {{ errors.question }}
                            </div>
                        </div>

                        <hr>

                        <div v-if="1 <= questionTitle.total_choices" class="col-12">
                            <label class="form-label"><b>Pilihan A</b></label>
                            <ckeditor :editor="editor" v-model="form.option_1" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.option_1 }"></ckeditor>
                            <div v-if="errors.option_1">
                                {{ errors.option_1 }}
                            </div>
                        </div>

                        <div v-if="2 <= questionTitle.total_choices" class="col-12">
                            <label class="form-label"><b>Pilihan B</b></label>
                            <ckeditor :editor="editor" v-model="form.option_2" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.option_2 }"></ckeditor>
                            <div v-if="errors.option_2">
                                {{ errors.option_2 }}
                            </div>
                        </div>

                        <div v-if="3 <= questionTitle.total_choices" class="col-12">
                            <label class="form-label"><b>Pilihan C</b></label>
                            <ckeditor :editor="editor" v-model="form.option_3" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.option_3 }"></ckeditor>
                            <div v-if="errors.option_3">
                                {{ errors.option_3 }}
                            </div>
                        </div>

                        <div v-if="4 <= questionTitle.total_choices" class="col-12">
                            <label class="form-label"><b>Pilihan D</b></label>
                            <ckeditor :editor="editor" v-model="form.option_4" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.option_4 }"></ckeditor>
                            <div v-if="errors.option_4">
                                {{ errors.option_4 }}
                            </div>
                        </div>

                        <div v-if="5 <= questionTitle.total_choices" class="col-12">
                            <label class="form-label"><b>Pilihan E</b></label>
                            <ckeditor :editor="editor" v-model="form.option_5" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.option_5 }"></ckeditor>
                            <div v-if="errors.option_5">
                                {{ errors.option_5 }}
                            </div>
                        </div>

                        <div class="col-12" v-if="questionTitle.assessment_type == 4">
                            <label class="form-label">Poin Jawaban A</label>
                            <input type="number" class="form-control" v-model="form.point_1" :class="{ 'is-invalid': errors.point_1 }" placeholder="Poin Jawaban A">
                            <div v-if="errors.point_1" class="invalid-feedback">
                                {{ errors.point_1 }}
                            </div>
                        </div>

                        <div class="col-12" v-if="questionTitle.assessment_type == 4">
                            <label class="form-label">Poin Jawaban B</label>
                            <input type="number" class="form-control" v-model="form.point_2" :class="{ 'is-invalid': errors.point_2 }" placeholder="Poin Jawaban B">
                            <div v-if="errors.point_2" class="invalid-feedback">
                                {{ errors.point_2 }}
                            </div>
                        </div>

                        <div class="col-12" v-if="questionTitle.assessment_type == 4">
                            <label class="form-label">Poin Jawaban C</label>
                            <input type="number" class="form-control" v-model="form.point_3" :class="{ 'is-invalid': errors.point_3 }" placeholder="Poin Jawaban C">
                            <div v-if="errors.point_3" class="invalid-feedback">
                                {{ errors.point_3 }}
                            </div>
                        </div>

                        <div class="col-12" v-if="questionTitle.assessment_type == 4">
                            <label class="form-label">Poin Jawaban D</label>
                            <input type="number" class="form-control" v-model="form.point_4" :class="{ 'is-invalid': errors.point_4 }" placeholder="Poin Jawaban D">
                            <div v-if="errors.point_4" class="invalid-feedback">
                                {{ errors.point_4 }}
                            </div>
                        </div>

                        <div class="col-12" v-if="questionTitle.assessment_type == 4">
                            <label class="form-label">Poin Jawaban E</label>
                            <input type="number" class="form-control" v-model="form.point_5" :class="{ 'is-invalid': errors.point_5 }" placeholder="Poin Jawaban E">
                            <div v-if="errors.point_5" class="invalid-feedback">
                                {{ errors.point_5 }}
                            </div>
                        </div>

                        <div class="col-12" v-if="questionTitle.assessment_type != 4">
                            <label class="form-label">Jawaban</label>
                            <multiselect
                                :class="{ 'is-invalid': errors.answer }"
                                v-model="form.answer"
                                :options="form.answers"
                                :multiple="true"
                                label="name"
                                :close-on-select="true"
                                :allow-empty="true"
                                track-by="id"
                                placeholder="[ Pilih ]"
                            ></multiselect>
                            <div v-if="errors.answer" class="invalid-feedback">
                                {{ errors.answer }}
                            </div>
                        </div>

                        <div class="col-12" v-if="questionTitle.assessment_type == 3">
                            <label class="form-label">Poin Setiap Jawaban Benar</label>
                            <input type="number" class="form-control" v-model="form.correct_point" :class="{ 'is-invalid': errors.correct_point }" placeholder="Poin Setiap Jawaban Benar">
                            <div v-if="errors.correct_point" class="invalid-feedback">
                                {{ errors.correct_point }}
                            </div>
                        </div>

                        <div class="col-12" v-if="questionTitle.assessment_type == 3">
                            <label class="form-label">Poin Setiap Jawaban Salah</label>
                            <input type="number" class="form-control" v-model="form.wrong_point" :class="{ 'is-invalid': errors.wrong_point }" placeholder="Poin Setiap Jawaban Salah">
                            <div v-if="errors.wrong_point" class="invalid-feedback">
                                {{ errors.wrong_point }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><b>Link Video Youtube atau Video di Google Drive (Optional)</b></label>
                            <input type="text" class="form-control" v-model="form.discussion_video" placeholder="Link Video Youtube atau Video di Google Drive">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><b>Pembahasan</b></label>
                            <ckeditor :editor="editor" v-model="form.discussion" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.discussion }"></ckeditor>
                            <div v-if="errors.discussion">
                                {{ errors.discussion }}
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

    import Multiselect from '@suadelabs/vue3-multiselect'

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Multiselect
        },

        //props
        props: {
            errors: Object,
            questionTitle: Object,
            valueCategories: Object,
            optionAlphabet: Array,
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

        // initialization composition API
        setup(props) {
            var multipleChoice = ['A', 'B', 'C', 'D', 'E'];

            let answers = [];

            for (let i = 1; i <= props.questionTitle.total_choices; i++) {
                answers.push({
                    id: i,
                    name: multipleChoice[i - 1]
                });
            }

            const form = reactive({
                value_category_id: '',
                question: '',

                audio_input: 0,
                audio: '',
                audio_played_limit: '',
                audio_answer_time: '',

                option_1: '',
                option_2: '',
                option_3: '',
                option_4: '',
                option_5: '',

                point_1: '0',
                point_2: '0',
                point_3: '0',
                point_4: '0',
                point_5: '0',

                correct_point: '1',
                wrong_point: '0',
                answer: [],
                section: props.questionTitle.total_section == 1 ? 1 : '',
                discussion_video: '',
                discussion: '',

                answers: answers,
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/question-titles/${props.questionTitle.id}/questions`, {
                    // data
                    assessment_type: props.questionTitle.assessment_type,
                    add_value_category: props.questionTitle.add_value_category,
                    question_title_id: props.questionTitle.id,
                    value_category_id: form.value_category_id,
                    question: form.question,

                    audio_input: form.audio_input,
                    audio: form.audio,
                    audio_played_limit: form.audio_played_limit,
                    audio_answer_time: form.audio_answer_time,
                    
                    option_1: form.option_1,
                    option_2: form.option_2,
                    option_3: form.option_3,
                    option_4: form.option_4,
                    option_5: form.option_5,

                    point_1: form.point_1,
                    point_2: form.point_2,
                    point_3: form.point_3,
                    point_4: form.point_4,
                    point_5: form.point_5,

                    correct_point: form.correct_point,
                    wrong_point: form.wrong_point,
                    
                    answer: form.answer,
                    section: form.section,
                    discussion_video: form.discussion_video,
                    discussion: form.discussion,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Soal Berhasil Disimpan!.',
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