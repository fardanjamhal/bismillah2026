<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tambah Bank</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Bank</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Bank</li>
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
                    <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                        <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
                    </div>
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link href="/user/affiliates/user-banks" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Bank</label>
                            <input type="text" class="form-control" v-model="form.bank_name" :class="{ 'is-invalid': errors.bank_name }" placeholder="Nama Bank, Contoh: BCA, BNI, BRI, Mandiri">
                            <div v-if="errors.bank_name" class="invalid-feedback">
                                {{ errors.bank_name }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control" v-model="form.account_number" :class="{ 'is-invalid': errors.account_number }" placeholder="Nomor Rekening">
                            <div v-if="errors.account_number" class="invalid-feedback">
                                {{ errors.account_number }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Akun</label>
                            <input type="text" class="form-control" v-model="form.account_name" :class="{ 'is-invalid': errors.account_name }" placeholder="Nama Akun">
                            <div v-if="errors.account_name" class="invalid-feedback">
                                {{ errors.account_name }}
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
    import LayoutUser from '../../../../Layouts/Layout.vue';

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
        layout: LayoutUser,

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
        setup() {
            const form = reactive({
                bank_name: '',
                account_number: '',
                account_name: '',
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post('/user/affiliates/user-banks', {
                    bank_name: form.bank_name,
                    account_number: form.account_number,
                    account_name: form.account_name,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Bank Berhasil Disimpan!.',
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
