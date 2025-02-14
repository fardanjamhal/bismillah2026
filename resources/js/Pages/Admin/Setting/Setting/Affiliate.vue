<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Setting</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Setting</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Setting</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card border-top border-0 border-3 border-primary">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <Link href="/admin/settings/applications" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title m-1">Aplikasi</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/admin/settings/authentications" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title m-1">Autentikasi</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/admin/settings/transactions" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title m-1">Transaksi</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/admin/settings/notifications" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title m-1">Notifikasi</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/admin/settings/site-configurations" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title m-1">Konfigurasi Situs</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/admin/settings/affiliates" class="nav-link active">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title m-1">Program Afiliasi</div>
                                        </div>
                                    </Link>
                                </li>
                            </ul>
                            <div class="tab-content py-3">
                                <div class="tab-pane fade active show" role="tabpanel">
                                    <form @submit.prevent="submit">
                                        <div class="row g-3">
                                            <div class="col-12">
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

                                                <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                                                    <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                                                    <ul class="mt-3">
                                                        <li v-for="error in errors">{{ error }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Aktifkan Program Afiliasi</label>
                                                <select v-model="form.enable_affiliate_feature" :class="{ 'is-invalid': errors.enable_affiliate_feature }" placeholder="Page Name" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                                <div v-if="errors.enable_affiliate_feature" class="invalid-feedback">
                                                    {{ errors.enable_affiliate_feature }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Syarat & Ketentuan</label>
                                                <ckeditor :editor="editor" v-model="form.terms_and_condition" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.terms_and_condition }"></ckeditor>
                                                <div v-if="errors.terms_and_condition">
                                                    {{ errors.terms_and_condition }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <label class="form-label">Tipe Komisi</label>
                                                <select v-model="form.commission_type" :class="{ 'is-invalid': errors.commission_type }" placeholder="Page Name" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Persentase</option>
                                                    <option value="2">Nominal</option>
                                                </select>
                                                <div v-if="errors.commission_type" class="invalid-feedback">
                                                    {{ errors.commission_type }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <label class="form-label">Komisi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon2">{{ form.commission_type == 1 ? '%' : form.commission_type == 2 ? 'Rp.' : 'Pilih Type' }}</span>
                                                    <input type="number" class="form-control" v-model="form.commission" :class="{ 'is-invalid': errors.commission }" placeholder="Komisi">
                                                    <div v-if="errors.commission" class="invalid-feedback">
                                                        {{ errors.commission }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Minimal Penarikan Komisi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon2">Rp.</span>
                                                    <input type="number" class="form-control" v-model="form.minimum_withdrawal" :class="{ 'is-invalid': errors.minimum_withdrawal }" placeholder="Minimal Penarikan Komisi">
                                                    <div v-if="errors.admin_fee" class="invalid-feedback">
                                                        {{ errors.admin_fee }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Biaya Admin Penarikan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon2">Rp.</span>
                                                    <input type="number" class="form-control" v-model="form.admin_fee" :class="{ 'is-invalid': errors.admin_fee }" placeholder="Biaya Admin Penarikan">
                                                    <div v-if="errors.admin_fee" class="invalid-feedback">
                                                        {{ errors.admin_fee }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Tampilkan Data Affiliator / Referrer Saat Registrasi</label>
                                                <select v-model="form.show_affiliator" :class="{ 'is-invalid': errors.show_affiliator }" placeholder="Page Name" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                                <div v-if="errors.show_affiliator" class="invalid-feedback">
                                                    {{ errors.show_affiliator }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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

    import Multiselect from '@suadelabs/vue3-multiselect'

    import { Inertia } from '@inertiajs/inertia';

    import QrcodeVue from 'qrcode.vue'

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

        // register components
        components: {
            Link,
            Head,
            Multiselect,
            QrcodeVue,
        },

        //props
        props: {
            errors: Object,
            setting: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                enable_affiliate_feature: props.setting.enable_affiliate_feature ?? '',
                terms_and_condition: props.setting.terms_and_condition ?? '',
                commission_type: props.setting.commission_type ?? '',
                commission: props.setting.commission ?? '',
                minimum_withdrawal: props.setting.minimum_withdrawal ?? '',
                admin_fee: props.setting.admin_fee ?? '',
                show_affiliator: props.setting.show_affiliator ?? '',
            });
            
            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/settings/affiliates`, {
                    enable_affiliate_feature: form.enable_affiliate_feature,
                    terms_and_condition: form.terms_and_condition,
                    commission_type: form.commission_type,
                    commission: form.commission,
                    minimum_withdrawal: form.minimum_withdrawal,
                    admin_fee: form.admin_fee,
                    show_affiliator: form.show_affiliator,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Settting Program Afiliasi Berhasil Diupdate!.',
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