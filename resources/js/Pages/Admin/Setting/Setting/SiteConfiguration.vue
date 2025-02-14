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
                                    <Link href="/admin/settings/site-configurations" class="nav-link active">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title m-1">Konfigurasi Situs</div>
                                        </div>
                                    </Link>
                                </li>
                                <li v-if="$page.props.setting.enable_affiliate_feature == 1" class="nav-item" role="presentation">
                                    <Link href="/admin/settings/affiliates" class="nav-link">
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
                                                <label class="form-label">Page Name</label>
                                                <input type="text" class="form-control" v-model="form.page_name" :class="{ 'is-invalid': errors.page_name }" placeholder="Page Name">
                                                <div v-if="errors.page_name" class="invalid-feedback">
                                                    {{ errors.page_name }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Meta Title</label>
                                                <input type="text" class="form-control" v-model="form.meta_title" :class="{ 'is-invalid': errors.meta_title }" placeholder="Meta Title">
                                                <div v-if="errors.meta_title" class="invalid-feedback">
                                                    {{ errors.meta_title }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Meta Description</label>
                                                <textarea class="form-control" v-model="form.meta_description" :class="{ 'is-invalid': errors.meta_description }" placeholder="Meta Description" rows="5"></textarea>
                                                <div v-if="errors.meta_description" class="invalid-feedback">
                                                    {{ errors.meta_description }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Meta Keywords</label>
                                                <textarea class="form-control" v-model="form.meta_keywords" :class="{ 'is-invalid': errors.meta_keywords }" placeholder="Meta Keywords" rows="5"></textarea>
                                                <div v-if="errors.meta_keywords" class="invalid-feedback">
                                                    {{ errors.meta_keywords }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Meta URL</label>
                                                <input type="text" class="form-control" v-model="form.meta_url" :class="{ 'is-invalid': errors.meta_url }" placeholder="Meta URL">
                                                <div v-if="errors.meta_url" class="invalid-feedback">
                                                    {{ errors.meta_url }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Meta Image</label>
                                                <input type="file" class="form-control" @input="form.meta_image = $event.target.files[0]" :class="{ 'is-invalid': errors.meta_image }" placeholder="Meta Image">
                                                <div v-if="errors.meta_image" class="invalid-feedback">
                                                    {{ errors.meta_image }}
                                                </div>
                                                <br>
                                                <div v-if="setting.meta_image">
                                                    <img  v-bind:src="'/storage/upload_files/settings/' + setting.meta_image" style="width:120px;"/>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Meta Favicon</label>
                                                <input type="file" class="form-control" @input="form.meta_favicon = $event.target.files[0]" :class="{ 'is-invalid': errors.meta_favicon }" placeholder="Meta Favicon">
                                                <div v-if="errors.meta_favicon" class="invalid-feedback">
                                                    {{ errors.meta_favicon }}
                                                </div>
                                                <br>
                                                <div v-if="setting.meta_favicon">
                                                    <img  v-bind:src="'/storage/upload_files/settings/' + setting.meta_favicon" style="width:120px;"/>
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

    export default {
        // layout
        layout: LayoutAdmin,

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
                page_name: props.setting.page_name,
                meta_title: props.setting.meta_title,
                meta_description: props.setting.meta_description,
                meta_keywords: props.setting.meta_keywords,
                meta_url: props.setting.meta_url,
                meta_image: props.setting.meta_image,
                meta_favicon: props.setting.meta_favicon,
            });
            
            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/settings/site-configurations`, {
                    forceFormData: true,
                    // data
                    page_name: form.page_name,
                    meta_title: form.meta_title,
                    meta_description: form.meta_description,
                    meta_keywords: form.meta_keywords,
                    meta_url: form.meta_url,
                    meta_image: form.meta_image,
                    meta_favicon: form.meta_favicon,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Setting Aplikasi Berhasil Diupdate!.',
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