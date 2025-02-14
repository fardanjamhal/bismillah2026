<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Edit Modul / Materi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Modul / Materi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Modul / Materi</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

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
                            <Link href="/admin/courses" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Kategori Peminatan</label>
                            <select v-model="form.category_id" :class="{ 'is-invalid': errors.category_id }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option v-for="(category, index) in categories" :key="index" :value="category.id">
                                    {{ category.name }}</option>
                            </select>
                            <div v-if="errors.category_id" class="invalid-feedback">
                                {{ errors.category_id }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Kategori Member (Khusus Untuk Membership Bulanan, Kosongkan Jika Tidak Ada)</label>
                            <multiselect
                                v-model="form.member_category_ids"
                                :options="memberCategories"
                                :multiple="true"
                                label="name"
                                :close-on-select="true"
                                :allow-empty="true"
                                track-by="name"
                                placeholder="[ Pilih ]"
                            ></multiselect>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Judul</label>
                            <input type="text" class="form-control" v-model="form.title" :class="{ 'is-invalid': errors.title }" placeholder="Judul">
                            <div v-if="errors.title" class="invalid-feedback">
                                {{ errors.title }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Thumbnail (Optional)</label>
                            <input type="file" class="form-control" @input="form.thumbnail = $event.target.files[0]" :class="{ 'is-invalid': errors.thumbnail }" placeholder="Thumbnail">
                            <div v-if="errors.thumbnail" class="invalid-feedback">
                                {{ errors.thumbnail }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Video Intro (Link Youtube, Google Drive atau lainnya)</label>
                            <input type="text" class="form-control" v-model="form.video_intro" :class="{ 'is-invalid': errors.video_intro }" placeholder="Link">
                            <div v-if="errors.video_intro" class="invalid-feedback">
                                {{ errors.video_intro }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Prasyarat</label>
                            <textarea cols="30" rows="5" class="form-control" v-model="form.prerequisite" :class="{ 'is-invalid': errors.prerequisite }" placeholder="Prasyarat"></textarea>
                            <div v-if="errors.prerequisite" class="invalid-feedback">
                                {{ errors.prerequisite }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea cols="30" rows="5" class="form-control" v-model="form.sort_description" :class="{ 'is-invalid': errors.sort_description }" placeholder="Deskripsi Singkat"></textarea>
                            <div v-if="errors.sort_description" class="invalid-feedback">
                                {{ errors.sort_description }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><b>Deskripsi</b></label>
                            <ckeditor :editor="editor" v-model="form.description" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.title }"></ckeditor>
                            <div v-if="errors.description" class="invalid-feedback">
                                {{ errors.description }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Urutan Ditampilkan</label>
                            <input type="text" class="form-control" v-model="form.order" :class="{ 'is-invalid': errors.order }" placeholder="Urutan Ditampilkan">
                            <div v-if="errors.order" class="invalid-feedback">
                                {{ errors.order }}
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
                            <label class="form-label">Harga Sebelum Diskon</label>
                            <input type="number" class="form-control" v-model="form.price_before_discount" :class="{ 'is-invalid': errors.price_before_discount }" placeholder="Harga Sebelum Diskon" min="0">
                            <div v-if="errors.price_before_discount" class="invalid-feedback">
                                {{ errors.price_before_discount }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.price_type == 2">
                            <label class="form-label">Harga Sesudah Diskon</label>
                            <input type="number" class="form-control" v-model="form.price_after_discount" :class="{ 'is-invalid': errors.price_after_discount }" placeholder="Harga Sesudah Diskon" min="0">
                            <div v-if="errors.price_after_discount" class="invalid-feedback">
                                {{ errors.price_after_discount }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select v-model="form.status" :class="{ 'is-invalid': errors.status }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="active">Aktive</option>
                                <option value="inactive">Inactive</option>
                                <option value="inprogress">Inprogress</option>
                            </select>
                            <div v-if="errors.status" class="invalid-feedback">
                                {{ errors.status }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Goals</label>
                            <div v-for="(goal, index) in form.goals" :key="index" class="input-group mb-3">
                                <input type="text" class="form-control" v-model="form.goals[index]" :class="{ 'is-invalid': errors[`goals.${index}`] }" placeholder="Goal">
                                <button class="btn" :class="index === form.goals.length - 1 ? 'btn-success' : 'btn-danger'" type="button" @click="handleGoalClick(index)">
                                    <i :class="index === form.goals.length - 1 ? 'bx bx-plus' : 'bx bx-minus'"></i>
                                </button>
                                <div v-if="errors[`goals.${index}`]" class="invalid-feedback">
                                    {{ errors[`goals.${index}`] }}
                                </div>
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

    import Multiselect from '@suadelabs/vue3-multiselect'

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
            categories: Object,
            course: Object,
            courseGoals: Object,
            memberCategories: Object,
            memberCategorySelected: Object,
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
                            'bold', 'italic', 'strikethrough', 'subscript', 'superscript',
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
            const form = reactive({
                category_id: props.course.category_id,
                title: props.course.title,
                thumbnail: props.course.thumbnail,
                video_intro: props.course.video_intro,
                prerequisite: props.course.prerequisite,
                sort_description: props.course.sort_description,
                description: props.course.description,
                order: props.course.order,
                price_type: props.course.price_type,
                price_before_discount: props.course.price_before_discount,
                price_after_discount: props.course.price_after_discount,
                status: props.course.status,
                goals: props.courseGoals.length > 0 ? props.courseGoals :  [''],
                member_category_ids: props.memberCategorySelected,
            });

            const handleGoalClick = (index) => {
                if (index === form.goals.length - 1) {
                    // Add new goal if the last input is clicked
                    form.goals.push('');
                } else {
                    // Remove the clicked goal if it's not the last one
                    form.goals.splice(index, 1);
                }
            };

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/courses/${props.course.id}`, {
                    forceFormData: true,
                    _method: 'put',
                    // data
                    category_id: form.category_id,
                    title: form.title,
                    thumbnail: form.thumbnail,
                    video_intro: form.video_intro,
                    prerequisite: form.prerequisite,
                    sort_description: form.sort_description,
                    description: form.description,
                    order: form.order,
                    price_type: form.price_type,
                    price_before_discount: form.price_before_discount,
                    price_after_discount: form.price_after_discount,
                    status: form.status,
                    goals: form.goals,
                    member_category_ids: form.member_category_ids
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Course Berhasil Diupdate!.',
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
                handleGoalClick
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