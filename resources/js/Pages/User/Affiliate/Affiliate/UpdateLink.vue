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
                            <Link href="/user/affiliates/links" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Kode Referral</label>
                            <input type="text" class="form-control" v-model="form.referral_code" :class="{ 'is-invalid': errors.referral_code }" placeholder="Kode Referral">
                            <div v-if="errors.referral_code" class="invalid-feedback">
                                {{ errors.referral_code }}
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
            referralLink: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                referral_code: props.referralLink.referral_code,
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/user/affiliates/links/${props.referralLink.id}`, {
                    _method: 'put',
                    referral_code: form.referral_code,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Referral Berhasil Diupdate!.',
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
