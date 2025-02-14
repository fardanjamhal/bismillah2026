<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Import User</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">User</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Import User</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->            

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div v-if="$page.props.session.error" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Gagal</strong>
                        <ul class="mt-3">
                            <li>{{ $page.props.session.error }}</li>
                        </ul>
                    </div>

                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <a href="/assets/import_format/format-import-user.xlsx" class="btn btn-success mx-1 btn-sm mt-lg-0" download><i class="bx bxs-file"></i>Download Format Import</a>
                            <Link href="/admin/users" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">File</label>
                            <input type="file" class="form-control" @input="form.file = $event.target.files[0]" :class="{ 'is-invalid': errors.file }" placeholder="file" id="fileInput">
                            <div v-if="errors.file" class="invalid-feedback">
                                {{ errors.file }}
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm px-5">Import</button>
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

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
        },

        //props
        props: {
            errors: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                file: '',
            });

            const submit = () => {
                // send data to server
                Inertia.post('/admin/users/import', {
                    forceFormData: true,
                    // data yang dikirim
                    file: form.file,
                }, {
                    onFinish: visit => {
                        form.file = '';
                        document.querySelector('#fileInput').value = '';
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
