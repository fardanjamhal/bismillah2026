<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tambah Berita</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Berita</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Berita</li>
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
                            <Link href="/admin/news" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Judul</label>
                            <input type="text" class="form-control" v-model="form.title" :class="{ 'is-invalid': errors.title }" placeholder="Judul">
                            <div v-if="errors.title" class="invalid-feedback">
                                {{ errors.title }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Thumbnail</label>
                            <input type="file" class="form-control" @input="form.thumbnail = $event.target.files[0]" :class="{ 'is-invalid': errors.thumbnail }" placeholder="Thumbnail">
                            <div v-if="errors.thumbnail" class="invalid-feedback">
                                {{ errors.thumbnail }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea class="form-control" v-model="form.sort_description" :class="{ 'is-invalid': errors.sort_description }" rows="10" placeholder="Deskripsi Singkat"></textarea>
                            <div v-if="errors.sort_description" class="invalid-feedback">
                                {{ errors.sort_description }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <ckeditor :editor="editor" v-model="form.description" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.description }"></ckeditor>
                            <div v-if="errors.description" class="invalid-feedback">
                                {{ errors.description }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select v-model="form.is_published" :class="{ 'is-invalid': errors.is_published }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Published</option>
                                <option value="0">Unpublished</option>
                            </select>
                            <div v-if="errors.is_published" class="invalid-feedback">
                                {{ errors.is_published }}
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
    
    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
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
            news: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                title: props.news.title,
                thumbnail: props.news.thumbnail,
                sort_description: props.news.sort_description,
                description: props.news.description,
                is_published: props.news.is_published,
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/news/${props.news.id}`, {
                    forceFormData: true,
                    _method: 'put',
                    // data
                    title: form.title,
                    thumbnail: form.thumbnail,
                    sort_description: form.sort_description,
                    description: form.description,
                    is_published: form.is_published,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Kategori Berhasil Disimpan!.',
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